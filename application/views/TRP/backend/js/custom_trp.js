
function ChangeContent(type) {
    tab_selected = type;
    switch (parseInt(type, 10)) {
        case 1:
            showCompanyInformation();
            break;
        case 2:
            showProfileSetting();
            break;
        case 3:
            showUserList();
            break;
        case 4:
            showUserForm();
            break;
        case 5:
            ShowPenddingApprove();
            break;
        case 6:
            ShowApproved();
            break;
        case 7:
            ShowRejected();
            break;

        case 8:
            ShowAllEros();
            break;
        case 9:
            ShowService();
            break;
        case 10:
            showAllService();
            break;
        case 11:
            showCompliance();
            break;
        case 13:
        	ShowPendingRegistration();
            break;    
        case 14:
        	showReviewMaterials();
            break;
        case 15:
        	showPaymentInfoSetting();
            break;   
        case 16:
        	showBankFeesInfoSetting();
            break;
        case 17:
        	showRecentApplication();
            break;  
        case 18:
            ShowAllAccounts();
            break;
        case 19:
            ShowPenddingApproveAccounts();
            break; 
        case 20:
            ShowApprovedAccounts();
            break; 
        case 21:
            ShowRejectedAccounts();
            break;
        case 22:
        	showPendingFundsApplication();
            break;
        case 23:
        	showReadyToPrintApplication();
            break;
        case 24:
        	showAllApplication();
            break; 
        case 25:
        	showPrintedApplication();
            break;  
        case 26:
        	showVoidedApplication();
            break;  
        case 27:
        	showPendingInsurance();
            break;
        case 28:
        	showPendingBenefits();
            break;
        case 29:
        	showActiveInsurance();
            break;
        case 30:
        	showCancelledInsurance();
            break;
        case 31:
        	showActiveBenefits();
            break;
        case 32:
        	showCancelledBenefits();
            break;
        case 33:
        	showBankProductFundedReport();
            break;
        case 34:
        	showBankProductUnfundedReport();
            break;

    }
}

function editPaidApplicationForVoid(id, parent) {

    var ids = id;

    if (confirm("Are you sure you want to void this application payment?")) {
        $.ajax({
            url: url_base_path__+"admin/clientcenter/makePaymentAsVoid?id="+ids,
            cache: false,
            //type: "POST",
            dataType: 'json',
            success: function(data){
                if (typeof (data) == 'object') {
                    //  Console.log('test');
                    //	alert(message);
                    // $("#body_check_for_print").empty().append(message);
                    //var obj = jQuery.parseJSON(data);

                    //dataClientACH = data;
                    //loadClientsACH();

                    // $('#generatedFileForWellsFargos').css('display', 'none');
                    // $('#dataFileUplaodResult').css('display', 'none');
                    // $('#generatedACHFileForWellsFargos').css('display', 'block');

                }else{
                    alert(data);
                    // Console.log('test3');
                    //  $('#generatemessage').html(data);
                    //  $('#generatedFileForWellsFargos').css('display', 'none');
                    //  $('#dataFileUplaodResult').css('display', 'none');
                    //  $('#generatedACHFileForWellsFargos').css('display', 'block');

                }

                //  $(".print_check").css('display','block');
                return false;
            }
        });
    }
}

function editEro(uid, parent) {
    var $parent = $("#" + parent);
    for (var k = 0; k < dataClient.length; k++) {
        var obj_ero = dataClient[k];
        if (obj_ero.uid == uid) {
            $parent.find("#efin").val(obj_ero.user_efin);
            $parent.find("#parent_efin").val(obj_ero.p_efin);
            $parent.find("#image_show").empty().append(obj_ero.image);
            $parent.find("#company_name").val(obj_ero.company_name);
            
            $parent.find("#service_bureau_efin").val(obj_ero.service_bureau_num);    
            (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
            (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
    	
            
            $parent.find("#address_1").val(obj_ero.business_addr_1);
            $parent.find("#address_2").val(obj_ero.business_addr_2);
            $parent.find("#com_phoneno").val(obj_ero.business_phone);
            $parent.find("#zipcode").val(obj_ero.business_zip);
            $parent.find("#city").val(obj_ero.business_city);
            $parent.find("#state").val(obj_ero.business_state);
            (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
            $parent.find("#address_1_m").val(obj_ero.mail_addr_1);
            $parent.find("#address_2_m").val(obj_ero.mail_addr_2);
            $parent.find("#zipcode_m").val(obj_ero.mail_zip);
            $parent.find("#city_m").val(obj_ero.mail_city);
            $parent.find("#state_m").val(obj_ero.mail_state);
            $parent.find("#tax").val(obj_ero.tax_software);
            $parent.find('input:radio[name="tax"]').filter('[value="'+obj_ero.tax_software+'"]').attr('checked', true);
            //console.log(obj_ero.tax_software);
           // $parent.find("#bank_name").val(obj_ero.bank_name);
           // $parent.find("#bank_routing").val(obj_ero.bank_routing);
            //$parent.find("#bank_account").val(obj_ero.bank_account);
           // $parent.find("#addon").val(obj_ero.addon);
            //$parent.find("#file").val(obj_ero.file);
            //$parent.find("#ag").val(obj_ero.ag);
            //$parent.find("#agprep").val(obj_ero.agprep);
            $parent.find("#uid_master").val(obj_ero.uid);

            $parent.find("#username").val(obj_ero.username);
            $parent.find("#role").val(obj_ero.role);
            $parent.find("#ptin1").val(obj_ero.ptin);
            $parent.find("#first_name").val(obj_ero.firstname);
            $parent.find("#middle_name").val(obj_ero.middlename);
            $parent.find("#last_name").val(obj_ero.lastname);
            $parent.find("#email").val(obj_ero.mail);
            $parent.find("#phone").val(obj_ero.phone);
           // $parent.find("#cell_phone").val(obj_ero.mobile);
            $parent.find("#address_1_p").val(obj_ero.address);
            $parent.find("#zipcode_p").val(obj_ero.zipcode);
            $parent.find("#city_p").val(obj_ero.city);
            $parent.find("#state_p").val(obj_ero.state);
            $parent.find("#current_p").val(obj_ero.pass);
           
            $parent.find("#bank_name1").val(obj_ero.bank_name);
        	$parent.find("#bank_routing1").val(obj_ero.bank_routing);
        	$parent.find("#b_account_name1").val(obj_ero.bank_account);
        	
        	$parent.find("#tax_preparation_fee1").val(obj_ero.tax_preparation_fee);
        	$parent.find("#bank_transmission_fee1").val(obj_ero.bank_transmission_fee);
        	$parent.find("#sb_fee1").val(obj_ero.sb_fee);
        	$parent.find("#e_file_fee1").val(obj_ero.e_file_fee);
        	$parent.find("#add_on_fee1").val(obj_ero.add_on_fee);

            $parent.find("#tax_preparation_commission1_type").val(obj_ero.tax_pre_commission_type);
            $parent.find("#tax_preparation_commission1").val(obj_ero.tax_pre_commission);
            $parent.find("#add_on_commission1_type").val(obj_ero.add_on_commission_type);
            $parent.find("#add_on_commission1").val(obj_ero.add_on_commission);

        }
    }
    $parent.closest("#modal_editEro").modal('show');
}


function editEroFromAdmin(uid, parent) {
    var $parent = $("#" + parent);
    for (var k = 0; k < dataClient.length; k++) {
        var obj_ero = dataClient[k];
        if (obj_ero.uid == uid) {

            $parent.find("#ero_id").val(uid);
            $parent.find("#efin").val(obj_ero.user_efin);
            $parent.find("#parent_efin").val(obj_ero.p_efin);
            $parent.find("#image_show").empty().append(obj_ero.image);
            $parent.find("#company_name").val(obj_ero.company_name);

            $parent.find("#service_bureau_efin").val(obj_ero.service_bureau_num);
            (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
            (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;


            $parent.find("#address_1").val(obj_ero.business_addr_1);
            $parent.find("#address_2").val(obj_ero.business_addr_2);
            $parent.find("#com_phoneno").val(obj_ero.business_phone);
            $parent.find("#zipcode").val(obj_ero.business_zip);
            $parent.find("#city").val(obj_ero.business_city);
            $parent.find("#state").val(obj_ero.business_state);
            (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
            $parent.find("#address_1_m").val(obj_ero.mail_addr_1);
            $parent.find("#address_2_m").val(obj_ero.mail_addr_2);
            $parent.find("#zipcode_m").val(obj_ero.mail_zip);
            $parent.find("#city_m").val(obj_ero.mail_city);
            $parent.find("#state_m").val(obj_ero.mail_state);
            $parent.find("#tax").val(obj_ero.tax_software);
            $parent.find('input:radio[name="tax"]').filter('[value="'+obj_ero.tax_software+'"]').attr('checked', true);
            //console.log(obj_ero.tax_software);
            // $parent.find("#bank_name").val(obj_ero.bank_name);
            // $parent.find("#bank_routing").val(obj_ero.bank_routing);
            //$parent.find("#bank_account").val(obj_ero.bank_account);
            // $parent.find("#addon").val(obj_ero.addon);
            //$parent.find("#file").val(obj_ero.file);
            //$parent.find("#ag").val(obj_ero.ag);
            //$parent.find("#agprep").val(obj_ero.agprep);
            $parent.find("#uid_master").val(obj_ero.uid);

            $parent.find("#username").val(obj_ero.username);
            $parent.find("#role").val(obj_ero.role);
            $parent.find("#ptin1").val(obj_ero.ptin);
            $parent.find("#first_name").val(obj_ero.firstname);
            $parent.find("#middle_name").val(obj_ero.middlename);
            $parent.find("#last_name").val(obj_ero.lastname);
            $parent.find("#email").val(obj_ero.mail);
            $parent.find("#phone").val(obj_ero.phone);
            // $parent.find("#cell_phone").val(obj_ero.mobile);
            $parent.find("#address_1_p").val(obj_ero.address);
            $parent.find("#zipcode_p").val(obj_ero.zipcode);
            $parent.find("#city_p").val(obj_ero.city);
            $parent.find("#state_p").val(obj_ero.state);
            $parent.find("#current_p").val(obj_ero.pass);

            $parent.find("#bank_name1").val(obj_ero.bank_name);
            $parent.find("#bank_routing1").val(obj_ero.bank_routing);
            $parent.find("#b_account_name1").val(obj_ero.bank_account);

            $parent.find("#tax_preparation_fee1").val(obj_ero.tax_preparation_fee);
            $parent.find("#bank_transmission_fee1").val(obj_ero.bank_transmission_fee);
            $parent.find("#sb_fee1").val(obj_ero.sb_fee);
            $parent.find("#e_file_fee1").val(obj_ero.e_file_fee);
            $parent.find("#add_on_fee1").val(obj_ero.add_on_fee);

            $parent.find("#tax_preparation_commission1_type").val(obj_ero.tax_pre_commission_type);
            $parent.find("#tax_preparation_commission1").val(obj_ero.tax_pre_commission);
            $parent.find("#add_on_commission1_type").val(obj_ero.add_on_commission_type);
            $parent.find("#add_on_commission1").val(obj_ero.add_on_commission);

        }
    }
    $parent.closest("#modal_editEro").modal('show');
}


function editApplication(uid, parent) {
    var $parent = $("#" + parent);
    //console.log($parent);
    for (var k = 0; k < dataClient.length; k++) {
        var obj_ero = dataClient[k];
        var obj_app_product = '';
        var producthtm = '';
        var pmathodhtm = '';
        var appststus = '';
        var html = '';
        //console.log(uid);
        //console.log(obj_ero.app_id);
        if (obj_ero.app_id == uid) {
        	//alert(uid);
        	//alert(obj_ero.app_id);
            $parent.find("#first_name_lab").html(obj_ero.first_name);
            $parent.find("#first_name").val(obj_ero.first_name);
            $parent.find("#last_name_lab").html(obj_ero.last_name);
            $parent.find("#last_name").val(obj_ero.last_name);
            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
            $parent.find("#ss_number").val(obj_ero.ss_number);
            $parent.find("#dob_lab").html(obj_ero.dob);
            $parent.find("#dob").val(obj_ero.dob);            
            
            if(obj_ero.sp_first_name == ''){
            //	$("#dependent-box").css({'display','none'});
            	$parent.find("#dependent-box").css('display','none');
        	}
            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
    	
            
            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
            $parent.find("#sp_dob").val(obj_ero.sp_dob);
            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
            $parent.find("#street_address").val(obj_ero.street_address_1);
            $parent.find("#city_lab").html(obj_ero.city);
            $parent.find("#city").val(obj_ero.city);
           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
            $parent.find("#state_lab").html(obj_ero.state);
           
            $parent.find("#state").val(obj_ero.state);
            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
            $parent.find("#zip_code").val(obj_ero.zip_code);
            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
            $parent.find("#cell_phone").val(obj_ero.cell_phone);
            $parent.find("#email_add_lab").html(obj_ero.email_add);
            $parent.find("#email_add").val(obj_ero.email_add);
            
            $parent.find("#total_refund_amt_3").html('$'+obj_ero.app_total_refund_amt);
            $parent.find("#banking_fee").html('$'+obj_ero.app_total_fees);
            $parent.find("#tax_preparation_fee_3").html('$'+obj_ero.app_tax_preparation_fee);
            $parent.find("#bank_transmission_fee_3").html('$'+obj_ero.app_bank_transmission_fee);
            $parent.find("#sb_fee_3").html('$'+obj_ero.app_sb_fee);
            $parent.find("#add_on_fee_3").html('$'+obj_ero.app_add_on_fee);
            $parent.find("#product_fee").html('$'+obj_ero.app_benefit);
            
            $parent.find("#application_id").val(obj_ero.applicent_id);
            $parent.find("#app_type").val(obj_ero.status);
            
            $parent.find("#net_refund_amt_3_label").html('$'+obj_ero.app_net_refund_amt);


            $parent.find(".forDepositedFunds").css('display','none');
            $parent.find(".forPendingFunds").css('display','block');

            
            //for (var l = 0; l < obj_ero.prodcuts.length; l++) {
            	// obj_app_product = obj_ero.prodcuts[l];
            	
            	//producthtm += '<div class="col-md-2 service_img">';
            	//producthtm += ''+obj_app_product.img_source+'';
            	//producthtm += '</div>';
          //  console.log(obj_ero.audit_guard_item);
            if(obj_ero.audit_guard_item != '' && obj_ero.audit_guard_item !== null){	
            	producthtm += '<div class="col-md-3 service_img">';
            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
            			producthtm += '<i class="'+obj_ero.audit_guard_img_source+'"></i>';
            		producthtm += '</div>';
            	producthtm += '</div>';
			
            	producthtm += '<div class="col-md-7 service_title_2" style="padding:0px">';
            	producthtm += '<h4>'+obj_ero.audit_guard_item+'</h4>';
            	producthtm += '</div>';
				
            	producthtm += '<div class="col-md-2 price_2 text-right">';
            	producthtm += '<h5>$'+obj_ero.audit_guard_fee+'</h5>';
            	producthtm += '</div>';
            	producthtm += '<div style="clear: both;"></div>';
				producthtm += '<hr style="margin: 20px 0px;">';
            }	
           // }
           
            //benefits
            if(obj_ero.benefits.length > 0){
            	//for(var i = 0 ; i < obj_ero.benefits.length ; i++){
        			obj_benefits_info = obj_ero.benefits[0];
        			
	           // if(obj_ero.benefits_item != '' && obj_ero.benefits_item !== null){	
	            	producthtm += '<div class="col-md-3 service_img">';
	            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
	            			producthtm += '<i class="'+obj_benefits_info.benefits_img_source+'"></i>';
	            		producthtm += '</div>';
	            	producthtm += '</div>';
				
	            	producthtm += '<div class="col-md-7 service_title_2"  style="padding:0px">';
	            	producthtm += '<h4>'+obj_benefits_info.benefits_item+'</h4>';
	            	producthtm += '</div>';
					
	            	producthtm += '<div class="col-md-2 price_2 text-right">';
	            	producthtm += '<h5>$'+obj_benefits_info.benefits_price+'</h5>';
	            	producthtm += '</div>';
	            	producthtm += '<div style="clear: both;"></div>';
					producthtm += '<hr style="margin: 20px 0px;">';
	            //}
            }
            
            
            if(obj_ero.insurance.length > 0){
           // if(obj_ero.insurance_item != '' && obj_ero.insurance_item !== null){
            //if(obj_ero.products.length > 0){
            	for(var i = 0 ; i < obj_ero.insurance.length ; i++){           		
            		obj_insurance_products_info = obj_ero.insurance[i];
            
            	producthtm += '<div class="col-md-3 service_img"> ';
            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
            			producthtm += '<i class="'+obj_insurance_products_info.insurance_img_source+'"></i>';
            		producthtm += '</div>';
            	producthtm += '</div>';
			
            	producthtm += '<div class="col-md-9 service_title_2"  style="padding:0px">';
            	producthtm += '<h4>'+obj_insurance_products_info.insurance_item+'</h4>';
            	producthtm += '</div>';
				
            	producthtm += '<div style="clear: both;"></div>';
				producthtm += '<hr style="margin: 20px 0px;">';
            	}
            }
            
            $parent.find("#cart_details_view").empty().append(producthtm);
            
           if(obj_ero.payment_method == 'Check') {
        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp; <strong>'+obj_ero.payment_method+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
           }
           else if(obj_ero.payment_method == 'Direct Deposit') {
        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp;  Bank: <strong>'+obj_ero.app_bank_name+'</strong>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.app_account_no+'</strong>';
               pmathodhtm += '</div>';
               
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.app_routing_no+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
               
               
               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '</div>';
               
           }
           else if(obj_ero.payment_method == 'Debit Card') {
        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Card # <strong>XXX-XXXX-XXXX-'+obj_ero.card_number.substring(16,12);+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
           }
           
           
           if(obj_ero.status == '0'){
          	 appststus = 'PENDING';
          	$parent.find("#ap_status_div").removeClass('alert-success').addClass('alert-warning');
          	$parent.find("#app_status_btn").css('display','none');
          	$parent.find("#print_check_icon").removeClass('icon-print').addClass('icon-busy');
          	
           }
           else if(obj_ero.status == '1'){
          	 appststus = 'READY TO PRINT';
          	$parent.find("#ap_status_div").removeClass('alert-warning').addClass('alert-success');
          	$parent.find("#app_status_btn").css('display','block');
          	$parent.find("#print_check_icon").removeClass('icon-busy').addClass('icon-print');
           //ap_status_div
           //$("#app_status").closest("#ap_status_div").modal('show');
           }else if(obj_ero.status == '2'){
          	 appststus = 'PRINTED CHECK';
          	$parent.find("#ap_status_div").removeClass('alert-warning').addClass('alert-success');
          	$parent.find("#app_status_btn").css('display','block');
          	$parent.find("#print_check_icon").removeClass('icon-busy').addClass('icon-print');
           }else if(obj_ero.status == '3'){
            	 appststus = 'VOIDED CHECK';
               	$parent.find("#ap_status_div").removeClass('alert-success').addClass('alert-warning');
               	$parent.find("#app_status_btn").css('display','none');
               	$parent.find("#print_check_icon").removeClass('icon-print').addClass('icon-busy');
               	
                }
           
           $parent.find("#app_status").html(appststus);
           $parent.find("#app_status_btn").val(obj_ero.app_id);
           
            
           $parent.find("#disbursement_method_details").empty().append(pmathodhtm);
        
           $parent.find('#bank_insurance_additional_info').css('display','none');
           //console.log(obj_ero.i_additional.length);
           
		if(obj_ero.insurance.length > 0){
			for(var n = 0 ; n < obj_ero.insurance.length ; n++){           		
        		obj_insurance_info = obj_ero.insurance[n];
        		
			if(obj_insurance_info.i_additional.length > 0){
				
				var c = 0;
				obj_addition_info = obj_insurance_info.i_additional[0];
        	   
				html+= '<section class="panel">';
        		  html+= '<header class="panel-heading">';
        			html+= '<span class="title" style="">'+obj_addition_info.insurance_title+' Order Information</span>';
        			html+= '<span class="tools pull-right icons" style=""><i class="'+obj_insurance_info.insurance_img_source+'"></i></span>';
        		html+= '</header>';
        		html+= '<div class="panel-body" style="padding:10px 17px;">';
               
        		//if(obj_ero.products.length > 0){
                	
                	//for(var k = 0 ; k < obj_ero.products.length ; k++){           		
                		//obj_products_info = obj_ero.products[k];
                		//console.log(obj_products_info.prodcut_name);
                		//for(var i = 0 ; i < obj_insurance_info.i_additional.length ; i++){
                			
                			
	            if(obj_insurance_info.insurance_item == 'Family Individual'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_addition_info.first_name+'';
								html+= '</div>';
							html+= '</div>';
				                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_addition_info.last_name+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
				            
				           
						html+= '<div class="form-group">';
							html+= '<div class="col-md-5 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Coverage Start Date: </label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.family_coverage_date+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_addition_info.family_gender+'';
							html+= '</div>';
						html+= '</div>';
				                
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.family_tobacco_use+'';	
									html+= '</div>';
				             html+= '</div>';
				      html+= '</div>';
				      html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            //	}
					//}
	            }
	            if(obj_insurance_info.insurance_item == 'Group Health'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
					//	obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
	            	
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Company name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.company_name_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Industry (Type of Business):</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.industry_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Company Address:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.company_address_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">State:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.state_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Zip:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.zip_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Requested Lines of Coverage:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.requested_line_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Current Carrier:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.current_carrier_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Current Renewal Date:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.renewal_date_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Requested Effective Date:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.effective_date_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
						
	            //	}
	            	//}
	            }
	            if(obj_insurance_info.insurance_item == 'Life Insurance & Annuities'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
					//	obj_addition_info = obj_ero.i_additional[i];
	            //	if(obj_products_info.app_pro_id == obj_addition_info.product_id){
	            	
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.last_name+'';
								html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.first_name+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
		           
						html+= '<div class="form-group">';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.gender_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Height:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.height_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Width:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.width_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.tobacco_use_life+'';
								html+= '</div>';
							html+= '</div>';
					html+= '</div>';
					
	            //	}
	            	//}
	            }
	             if(obj_insurance_info.insurance_item == 'Auto Insurance'){
	            //	for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	// if(obj_products_info.app_pro_id == obj_addition_info.product_id){
							if(c < 1){
								//console.log('c='+c);
								c=1;
								
						html += '<div class="form-group">';
						html += '<div class="col-md-6 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].first_name+'';
							html += '</div>';
						html += '</div>';
		                
						html += '<div class="col-md-6 form-group1">';
							html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].last_name+'';
							html += '</div>';
						html += '</div>';
					html += '</div>';
		            
					html += '<div class="form-group">';
						html += '<div class="col-md-4 form-group1">';
							html += '<label for="street_address" class="control-label col-md-12">Marital Status:  </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].marital_status_auto+'';
							html += '</div>';
						html += '</div>';
						html += '<div class="col-md-4 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].gender_auto+'';
							html += '</div>';
						html += '</div>';
					 html += '</div>';
		            
					html += '<div class="form-group">';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="street_address" class="control-label col-md-12">Year:  </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].year_auto+'';
							html += '</div>';
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Make: </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].make_auto+'';
							html += '</div>';	
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Model: </label>';
						html += '<div class="input-group1 nopadding col-md-12">';
						html+= ''+obj_insurance_info.i_additional[i].model_auto+'';
						html += '</div>';	
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Coverage: </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].coverage_auto+'';
							html += '</div>';	
					html += '</div>';
		                
					html += '</div>';
							}
							
					//	for(var j = 1 ; j < obj_ero.i_additional.length ; j++){
						
							if(i < parseInt(obj_insurance_info.i_additional.length - 1)){
								var l = parseInt(i+1);
								//console.log(l);
												var obj3 = obj_insurance_info.i_additional[l];
												
							//var obj3 = obj_ero.i_additional[j];
							
							html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.first_name+'';
								html += '</div>';
							html += '</div>';
			                
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.last_name+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						
						
						html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Relationship to Applicant: </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.relation_auto+'';
								html += '</div>';	
							html += '</div>';
							html += '<div class="col-md-3 form-group1">';
								html += '<label for="street_address" class="control-label col-md-12" style="padding-right: 0px;">Marital Status:  </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.marital_status_auto+'';
									html += '</div>';
							html += '</div>';
							html += '<div class="col-md-3 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.gender_auto+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
							}
					//	}
	            //	}
	            }
	            if(obj_insurance_info.insurance_item == 'Home Insurance'){
	            //	for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
					//	obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Coverage Start Date:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.coverage_date_home+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						
	            	//}
	            	//}
	            }
	            if(obj_insurance_info.insurance_item == 'Property & Casualty'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
						html+= '<div class="col-md-12 form-group1">';
						html+= '<label for="first_name" class="control-label col-md-12">Provide a realistic estimate of your gross revenue for the current financial year:</label>';
						html+= '<div class="input-group1 nopadding col-md-7">';
		                    html+= ''+obj_addition_info.revenue_property+'';
		                    html+= '</div>';
		               html+= '</div>';
		              html+= '</div>';
		            	  html+= '<div class="" style="clear:both"></div>';
		            		  html+= '<div class="form-group">  ';
		            			  html+= '<div class="col-md-12 form-group1">';
		            				  html+= '<label for="last_name" class="control-label col-md-12">Have any claims been filed against your business during the past 3 years:</label>';
		            				html+= '<div class="input-group1 nopadding col-md-3">';
		            					html+= ''+obj_addition_info.past_claims_property+'';
		              				html+= '</div>';
		              		html+= '</div>';
		              html+= '</div>';
		            html+= '<div class="" style="clear:both"></div>'; 
		            html+= '<div class="form-group">';
		            html+= '<div class="col-md-12 form-group1">';
		            	html+= '<label for="first_name" class="control-label col-md-12">Please indicate which types of insurance you would like to receive a quote for: <br> <small>Type of insurance needed (check all that apply)</small></label>';
		            		html+= '<div class="input-group1 nopadding col-md-12">';
		            			html+= '<div class="input-group1 nopadding col-md-12">';
			                    html+= ''+obj_addition_info.insurance_type_property+'';
		              				html+= '</div>';
		              			html+= '</div>';
		              			html+= '</div>';
		              html+= '</div>';
		              
	            	//}
	            	//}
	            }
	          // } // end additional loop
	         // }
           //}
	            
	            html+= '</div>';
	            html+= '</section>';
	            $parent.find('#bank_insurance_additional_info').css('display','block');
	            $parent.find('#bank_insurance_additional_info').empty().html(html);    
           } // end additional condition 
		}// end insurance loop
		} // end insurance condition
        }
    }
    	
    	$parent.find("#first_name_lab").css('display','block');
		 $parent.find("#first_name_div").css('display','none');
		 $parent.find("#last_name_lab").css('display','block');
		 $parent.find("#last_name_div").css('display','none');
		 $parent.find("#ss_number_lab").css('display','block');
		 $parent.find("#ss_number_div").css('display','none');
		 $parent.find("#dob_lab").css('display','block');
		 $parent.find("#dob_div").css('display','none');
		 $parent.find("#sp_first_name_lab").css('display','block');
		 $parent.find("#sp_first_name").css('display','none');
		 $parent.find("#sp_last_name_lab").css('display','block');
		 $parent.find("#sp_last_name").css('display','none');
		 $parent.find("#sp_ss_number_lab").css('display','block');
		 $parent.find("#sp_ss_number").css('display','none');
		 $parent.find("#sp_dob_lab").css('display','block');
		 $parent.find("#sp_dob").css('display','none');
		 $parent.find("#street_address_lab").css('display','block');
		 $parent.find("#street_address_div").css('display','none');
		 $parent.find("#city_lab").css('display','block');
		 $parent.find("#city_div").css('display','none');
		 $parent.find("#state_lab").css('display','block');
		 $parent.find("#state_div").css('display','none');
		 $parent.find("#zip_code_lab").css('display','block');
		 $parent.find("#zip_code_div").css('display','none');
		 $parent.find("#cell_phone_lab").css('display','block');
		 $parent.find("#cell_phone_div").css('display','none');
		 $parent.find("#email_add_lab").css('display','block');
		 $parent.find("#email_add_div").css('display','none');
		 $(".info_hr").css('display','block');
		 
		 
		 $parent.find("#saveData").css('display','none');
		 
    $parent.closest("#modal_editApplication").modal('show');
}

function editApplicationAfterDeposit(uid, parent) {
    var $parent = $("#" + parent);
    //console.log($parent);
    for (var k = 0; k < dataClient.length; k++) {
        var obj_ero = dataClient[k];
        var obj_app_product = '';
        var producthtm = '';
        var pmathodhtm = '';
        var appststus = '';
        var html = '';
        //console.log(uid);
        //console.log(obj_ero.app_id);
        if (obj_ero.app_id == uid) {
        	//alert(uid);
        	//alert(obj_ero.app_id);
            $parent.find("#first_name_lab").html(obj_ero.first_name);
            $parent.find("#first_name").val(obj_ero.first_name);
            $parent.find("#last_name_lab").html(obj_ero.last_name);
            $parent.find("#last_name").val(obj_ero.last_name);
            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
            $parent.find("#ss_number").val(obj_ero.ss_number);
            $parent.find("#dob_lab").html(obj_ero.dob);
            $parent.find("#dob").val(obj_ero.dob);            
            
            if(obj_ero.sp_first_name == ''){
            //	$("#dependent-box").css({'display','none'});
            	$parent.find("#dependent-box").css('display','none');
        	}
            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
    	
            
            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
            $parent.find("#sp_dob").val(obj_ero.sp_dob);
            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
            $parent.find("#street_address").val(obj_ero.street_address_1);
            $parent.find("#city_lab").html(obj_ero.city);
            $parent.find("#city").val(obj_ero.city);
           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
            $parent.find("#state_lab").html(obj_ero.state);
           
            $parent.find("#state").val(obj_ero.state);
            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
            $parent.find("#zip_code").val(obj_ero.zip_code);
            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
            $parent.find("#cell_phone").val(obj_ero.cell_phone);
            $parent.find("#email_add_lab").html(obj_ero.email_add);
            $parent.find("#email_add").val(obj_ero.email_add);

            $parent.find("#expected_refund_amt_3").html('$'+obj_ero.app_refund_amt);
            $parent.find("#total_refund_amt_3").html('$'+obj_ero.deposit_amount);
            $parent.find("#banking_fee").html('$'+obj_ero.app_total_fees);
            $parent.find("#tax_preparation_fee_3").html('$'+obj_ero.app_tax_preparation_fee);
            $parent.find("#bank_transmission_fee_3").html('$'+obj_ero.app_bank_transmission_fee);
            $parent.find("#sb_fee_3").html('$'+obj_ero.app_sb_fee);
            $parent.find("#add_on_fee_3").html('$'+obj_ero.app_add_on_fee);
            $parent.find("#product_fee").html('$'+obj_ero.app_benefit);
            
            $parent.find("#application_id").val(obj_ero.applicent_id);
            $parent.find("#app_type").val(obj_ero.status);
            
            $parent.find("#net_refund_amt_3_label").html('$'+obj_ero.app_actual_refund_amount);


            $parent.find(".forPendingFunds").css('display','none');
            $parent.find(".forDepositedFunds").css('display','block');

            
            //for (var l = 0; l < obj_ero.prodcuts.length; l++) {
            	// obj_app_product = obj_ero.prodcuts[l];
            	
            	//producthtm += '<div class="col-md-2 service_img">';
            	//producthtm += ''+obj_app_product.img_source+'';
            	//producthtm += '</div>';
          //  console.log(obj_ero.audit_guard_item);
            if(obj_ero.audit_guard_item != '' && obj_ero.audit_guard_item !== null){	
            	producthtm += '<div class="col-md-3 service_img">';
            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
            			producthtm += '<i class="'+obj_ero.audit_guard_img_source+'"></i>';
            		producthtm += '</div>';
            	producthtm += '</div>';
			
            	producthtm += '<div class="col-md-7 service_title_2" style="padding:0px">';
            	producthtm += '<h4>'+obj_ero.audit_guard_item+'</h4>';
            	producthtm += '</div>';
				
            	producthtm += '<div class="col-md-2 price_2 text-right">';
            	producthtm += '<h5>$'+obj_ero.audit_guard_fee+'</h5>';
            	producthtm += '</div>';
            	producthtm += '<div style="clear: both;"></div>';
				producthtm += '<hr style="margin: 20px 0px;">';
            }	
           // }
           
            //benefits
            if(obj_ero.benefits.length > 0){
            	//for(var i = 0 ; i < obj_ero.benefits.length ; i++){
        			obj_benefits_info = obj_ero.benefits[0];
        			
	           // if(obj_ero.benefits_item != '' && obj_ero.benefits_item !== null){	
	            	producthtm += '<div class="col-md-3 service_img">';
	            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
	            			producthtm += '<i class="'+obj_benefits_info.benefits_img_source+'"></i>';
	            		producthtm += '</div>';
	            	producthtm += '</div>';
				
	            	producthtm += '<div class="col-md-7 service_title_2"  style="padding:0px">';
	            	producthtm += '<h4>'+obj_benefits_info.benefits_item+'</h4>';
	            	producthtm += '</div>';
					
	            	producthtm += '<div class="col-md-2 price_2 text-right">';
	            	producthtm += '<h5>$'+obj_benefits_info.benefits_price+'</h5>';
	            	producthtm += '</div>';
	            	producthtm += '<div style="clear: both;"></div>';
					producthtm += '<hr style="margin: 20px 0px;">';
	            //}
            }
            
            
            if(obj_ero.insurance.length > 0){
           // if(obj_ero.insurance_item != '' && obj_ero.insurance_item !== null){
            //if(obj_ero.products.length > 0){
            	for(var i = 0 ; i < obj_ero.insurance.length ; i++){           		
            		obj_insurance_products_info = obj_ero.insurance[i];
            
            	producthtm += '<div class="col-md-3 service_img"> ';
            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
            			producthtm += '<i class="'+obj_insurance_products_info.insurance_img_source+'"></i>';
            		producthtm += '</div>';
            	producthtm += '</div>';
			
            	producthtm += '<div class="col-md-9 service_title_2"  style="padding:0px">';
            	producthtm += '<h4>'+obj_insurance_products_info.insurance_item+'</h4>';
            	producthtm += '</div>';
				
            	producthtm += '<div style="clear: both;"></div>';
				producthtm += '<hr style="margin: 20px 0px;">';
            	}
            }
            
            $parent.find("#cart_details_view").empty().append(producthtm);
            
           if(obj_ero.payment_method == 'Check') {
        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp; <strong>'+obj_ero.payment_method+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
           }
           else if(obj_ero.payment_method == 'Direct Deposit') {
        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp;  Bank: <strong>'+obj_ero.app_bank_name+'</strong>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.app_account_no+'</strong>';
               pmathodhtm += '</div>';
               
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.app_routing_no+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
               
               
               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '</div>';
               
           }
           else if(obj_ero.payment_method == 'Debit Card') {
        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Card # <strong>XXX-XXXX-XXXX-'+obj_ero.card_number.substring(16,12);+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
           }
           
           
           if(obj_ero.status == '0'){
          	 appststus = 'PENDING';
          	$parent.find("#ap_status_div").removeClass('alert-success').addClass('alert-warning');
          	$parent.find("#app_status_btn").css('display','none');
          	$parent.find("#print_check_icon").removeClass('icon-print').addClass('icon-busy');
          	
           }
           else if(obj_ero.status == '1'){
          	 appststus = 'READY TO PRINT';
          	$parent.find("#ap_status_div").removeClass('alert-warning').addClass('alert-success');
          	$parent.find("#app_status_btn").css('display','block');
          	$parent.find("#print_check_icon").removeClass('icon-busy').addClass('icon-print');
           //ap_status_div
           //$("#app_status").closest("#ap_status_div").modal('show');
           }else if(obj_ero.status == '2'){
          	 appststus = 'PRINTED CHECK';
          	$parent.find("#ap_status_div").removeClass('alert-warning').addClass('alert-success');
          	$parent.find("#app_status_btn").css('display','block');
          	$parent.find("#print_check_icon").removeClass('icon-busy').addClass('icon-print');
           }else if(obj_ero.status == '3'){
            	 appststus = 'VOIDED CHECK';
               	$parent.find("#ap_status_div").removeClass('alert-success').addClass('alert-warning');
               	$parent.find("#app_status_btn").css('display','none');
               	$parent.find("#print_check_icon").removeClass('icon-print').addClass('icon-busy');
               	
                }
           
           $parent.find("#app_status").html(appststus);
           $parent.find("#app_status_btn").val(obj_ero.app_id);
           
            
           $parent.find("#disbursement_method_details").empty().append(pmathodhtm);
        
           $parent.find('#bank_insurance_additional_info').css('display','none');
           //console.log(obj_ero.i_additional.length);
           
		if(obj_ero.insurance.length > 0){
			for(var n = 0 ; n < obj_ero.insurance.length ; n++){           		
        		obj_insurance_info = obj_ero.insurance[n];
        		
			if(obj_insurance_info.i_additional.length > 0){
				
				var c = 0;
				obj_addition_info = obj_insurance_info.i_additional[0];
        	   
				html+= '<section class="panel">';
        		  html+= '<header class="panel-heading">';
        			html+= '<span class="title" style="">'+obj_addition_info.insurance_title+' Order Information</span>';
        			html+= '<span class="tools pull-right icons" style=""><i class="'+obj_insurance_info.insurance_img_source+'"></i></span>';
        		html+= '</header>';
        		html+= '<div class="panel-body" style="padding:10px 17px;">';
               
        		//if(obj_ero.products.length > 0){
                	
                	//for(var k = 0 ; k < obj_ero.products.length ; k++){           		
                		//obj_products_info = obj_ero.products[k];
                		//console.log(obj_products_info.prodcut_name);
                		//for(var i = 0 ; i < obj_insurance_info.i_additional.length ; i++){
                			
                			
	            if(obj_insurance_info.insurance_item == 'Family Individual'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_addition_info.first_name+'';
								html+= '</div>';
							html+= '</div>';
				                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_addition_info.last_name+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
				            
				           
						html+= '<div class="form-group">';
							html+= '<div class="col-md-5 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Coverage Start Date: </label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.family_coverage_date+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_addition_info.family_gender+'';
							html+= '</div>';
						html+= '</div>';
				                
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.family_tobacco_use+'';	
									html+= '</div>';
				             html+= '</div>';
				      html+= '</div>';
				      html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            //	}
					//}
	            }
	            if(obj_insurance_info.insurance_item == 'Group Health'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
					//	obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
	            	
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Company name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.company_name_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Industry (Type of Business):</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.industry_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Company Address:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.company_address_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">State:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.state_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Zip:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.zip_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Requested Lines of Coverage:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.requested_line_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Current Carrier:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.current_carrier_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Current Renewal Date:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.renewal_date_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Requested Effective Date:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.effective_date_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
						
	            //	}
	            	//}
	            }
	            if(obj_insurance_info.insurance_item == 'Life Insurance & Annuities'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
					//	obj_addition_info = obj_ero.i_additional[i];
	            //	if(obj_products_info.app_pro_id == obj_addition_info.product_id){
	            	
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.last_name+'';
								html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.first_name+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
		           
						html+= '<div class="form-group">';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.gender_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Height:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.height_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Width:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.width_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.tobacco_use_life+'';
								html+= '</div>';
							html+= '</div>';
					html+= '</div>';
					
	            //	}
	            	//}
	            }
	             if(obj_insurance_info.insurance_item == 'Auto Insurance'){
	            //	for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	// if(obj_products_info.app_pro_id == obj_addition_info.product_id){
							if(c < 1){
								//console.log('c='+c);
								c=1;
								
						html += '<div class="form-group">';
						html += '<div class="col-md-6 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].first_name+'';
							html += '</div>';
						html += '</div>';
		                
						html += '<div class="col-md-6 form-group1">';
							html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].last_name+'';
							html += '</div>';
						html += '</div>';
					html += '</div>';
		            
					html += '<div class="form-group">';
						html += '<div class="col-md-4 form-group1">';
							html += '<label for="street_address" class="control-label col-md-12">Marital Status:  </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].marital_status_auto+'';
							html += '</div>';
						html += '</div>';
						html += '<div class="col-md-4 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].gender_auto+'';
							html += '</div>';
						html += '</div>';
					 html += '</div>';
		            
					html += '<div class="form-group">';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="street_address" class="control-label col-md-12">Year:  </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].year_auto+'';
							html += '</div>';
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Make: </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].make_auto+'';
							html += '</div>';	
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Model: </label>';
						html += '<div class="input-group1 nopadding col-md-12">';
						html+= ''+obj_insurance_info.i_additional[i].model_auto+'';
						html += '</div>';	
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Coverage: </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].coverage_auto+'';
							html += '</div>';	
					html += '</div>';
		                
					html += '</div>';
							}
							
					//	for(var j = 1 ; j < obj_ero.i_additional.length ; j++){
						
							if(i < parseInt(obj_insurance_info.i_additional.length - 1)){
								var l = parseInt(i+1);
								//console.log(l);
												var obj3 = obj_insurance_info.i_additional[l];
												
							//var obj3 = obj_ero.i_additional[j];
							
							html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.first_name+'';
								html += '</div>';
							html += '</div>';
			                
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.last_name+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						
						
						html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Relationship to Applicant: </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.relation_auto+'';
								html += '</div>';	
							html += '</div>';
							html += '<div class="col-md-3 form-group1">';
								html += '<label for="street_address" class="control-label col-md-12" style="padding-right: 0px;">Marital Status:  </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.marital_status_auto+'';
									html += '</div>';
							html += '</div>';
							html += '<div class="col-md-3 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.gender_auto+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
							}
					//	}
	            //	}
	            }
	            if(obj_insurance_info.insurance_item == 'Home Insurance'){
	            //	for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
					//	obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Coverage Start Date:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.coverage_date_home+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						
	            	//}
	            	//}
	            }
	            if(obj_insurance_info.insurance_item == 'Property & Casualty'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
						html+= '<div class="col-md-12 form-group1">';
						html+= '<label for="first_name" class="control-label col-md-12">Provide a realistic estimate of your gross revenue for the current financial year:</label>';
						html+= '<div class="input-group1 nopadding col-md-7">';
		                    html+= ''+obj_addition_info.revenue_property+'';
		                    html+= '</div>';
		               html+= '</div>';
		              html+= '</div>';
		            	  html+= '<div class="" style="clear:both"></div>';
		            		  html+= '<div class="form-group">  ';
		            			  html+= '<div class="col-md-12 form-group1">';
		            				  html+= '<label for="last_name" class="control-label col-md-12">Have any claims been filed against your business during the past 3 years:</label>';
		            				html+= '<div class="input-group1 nopadding col-md-3">';
		            					html+= ''+obj_addition_info.past_claims_property+'';
		              				html+= '</div>';
		              		html+= '</div>';
		              html+= '</div>';
		            html+= '<div class="" style="clear:both"></div>'; 
		            html+= '<div class="form-group">';
		            html+= '<div class="col-md-12 form-group1">';
		            	html+= '<label for="first_name" class="control-label col-md-12">Please indicate which types of insurance you would like to receive a quote for: <br> <small>Type of insurance needed (check all that apply)</small></label>';
		            		html+= '<div class="input-group1 nopadding col-md-12">';
		            			html+= '<div class="input-group1 nopadding col-md-12">';
			                    html+= ''+obj_addition_info.insurance_type_property+'';
		              				html+= '</div>';
		              			html+= '</div>';
		              			html+= '</div>';
		              html+= '</div>';
		              
	            	//}
	            	//}
	            }
	          // } // end additional loop
	         // }
           //}
	            
	            html+= '</div>';
	            html+= '</section>';
	            $parent.find('#bank_insurance_additional_info').css('display','block');
	            $parent.find('#bank_insurance_additional_info').empty().html(html);    
           } // end additional condition 
		}// end insurance loop
		} // end insurance condition
        }
    }
    	
    	$parent.find("#first_name_lab").css('display','block');
		 $parent.find("#first_name_div").css('display','none');
		 $parent.find("#last_name_lab").css('display','block');
		 $parent.find("#last_name_div").css('display','none');
		 $parent.find("#ss_number_lab").css('display','block');
		 $parent.find("#ss_number_div").css('display','none');
		 $parent.find("#dob_lab").css('display','block');
		 $parent.find("#dob_div").css('display','none');
		 $parent.find("#sp_first_name_lab").css('display','block');
		 $parent.find("#sp_first_name").css('display','none');
		 $parent.find("#sp_last_name_lab").css('display','block');
		 $parent.find("#sp_last_name").css('display','none');
		 $parent.find("#sp_ss_number_lab").css('display','block');
		 $parent.find("#sp_ss_number").css('display','none');
		 $parent.find("#sp_dob_lab").css('display','block');
		 $parent.find("#sp_dob").css('display','none');
		 $parent.find("#street_address_lab").css('display','block');
		 $parent.find("#street_address_div").css('display','none');
		 $parent.find("#city_lab").css('display','block');
		 $parent.find("#city_div").css('display','none');
		 $parent.find("#state_lab").css('display','block');
		 $parent.find("#state_div").css('display','none');
		 $parent.find("#zip_code_lab").css('display','block');
		 $parent.find("#zip_code_div").css('display','none');
		 $parent.find("#cell_phone_lab").css('display','block');
		 $parent.find("#cell_phone_div").css('display','none');
		 $parent.find("#email_add_lab").css('display','block');
		 $parent.find("#email_add_div").css('display','none');
		 $(".info_hr").css('display','block');
		 
		 
		 $parent.find("#saveData").css('display','none');
		 
    $parent.closest("#modal_editApplication").modal('show');
}


function editApplicationForVoidOrReprint(uid, parent) {
    var $parent = $("#" + parent);
    //console.log($parent);

    $parent.find("#h_application_id").val(uid);

    $parent.closest("#modal_void_reprint").modal('show');
}


function voidApplication(parent) {
    var $parent = $("#" + parent);
    //console.log($parent);

    var appid = $parent.find("#h_application_id").val();
    $parent.find(".tempmessage").remove();

    if (confirm("Are you sure? Do you want to void this check?")){
        $.post(url_base_path__+"admin/clientcenter/setCheckAsVoid", {
            delete: 'yes',
            id: appid
        }, function(data) {

            $("#body_void_reprint_application_printed").prepend(data);
            //if (typeof(data) == 'object') {
            //    dataService = data;
            //}
            // loadServicesList(dataService,parent_body);
        }, "json");
        return false;
    }
}




function voidAndReprintApplication(parent) {
    var $parent = $("#" + parent);
    //console.log($parent);
    $parent.find(".tempmessage").remove();
    var appid = $parent.find("#h_application_id").val();
    if (confirm("Are you sure? Do you want to void & Re-print this check ?")){
        $.post(url_base_path__+"admin/clientcenter/setCheckAsVoidAndReprint", {
            delete: 'yes',
            id: appid
        }, function(data) {

            $("#body_void_reprint_application_printed").prepend(data);
            //if (typeof(data) == 'object') {
            //    dataService = data;
            //}
            // loadServicesList(dataService,parent_body);
        }, "json");
        return false;
    }
}



// cancel Void Check
function cancelVoidCheck() {
    $("#cancelVoidCheckBtn").click(function () {
        $("#modal_void_reprint").modal('hide');
    });
}

function editApplicationFromReporting(uid, parent) {
    var $parent = $("#" + parent);
    //console.log($parent);
    for (var k = 0; k < dataClient.length; k++) {
        var obj_ero = dataClient[k];
        var obj_app_product = '';
        var producthtm = '';
        var pmathodhtm = '';
        var appststus = '';
        
        if (obj_ero.app_id == uid) {
        	//alert(uid);
        	//alert(obj_ero.app_id);
            $parent.find("#first_name_lab").html(obj_ero.first_name);
            $parent.find("#first_name").val(obj_ero.first_name);
            $parent.find("#last_name_lab").html(obj_ero.last_name);
            $parent.find("#last_name").val(obj_ero.last_name);
            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
            $parent.find("#ss_number").val(obj_ero.ss_number);
            $parent.find("#dob_lab").html(obj_ero.dob);
            $parent.find("#dob").val(obj_ero.dob);            
            
            if(obj_ero.sp_first_name == ''){
            //	$("#dependent-box").css({'display','none'});
            	$parent.find("#dependent-box").css('display','none');
        	}
            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
    	
            
            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
            $parent.find("#sp_dob").val(obj_ero.sp_dob);
            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
            $parent.find("#street_address").val(obj_ero.street_address_1);
            $parent.find("#city_lab").html(obj_ero.city);
            $parent.find("#city").val(obj_ero.city);
           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
            $parent.find("#state_lab").html(obj_ero.state);
           
            $parent.find("#state").val(obj_ero.state);
            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
            $parent.find("#zip_code").val(obj_ero.zip_code);
            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
            $parent.find("#cell_phone").val(obj_ero.cell_phone);
            $parent.find("#email_add_lab").html(obj_ero.email_add);
            $parent.find("#email_add").val(obj_ero.email_add);
            
            $parent.find("#total_refund_amt_3").html('$'+obj_ero.app_total_refund_amt);
            $parent.find("#banking_fee").html('$'+obj_ero.app_total_fees);
            $parent.find("#tax_preparation_fee_3").html('$'+obj_ero.app_tax_preparation_fee);
            $parent.find("#bank_transmission_fee_3").html('$'+obj_ero.app_bank_transmission_fee);
            $parent.find("#sb_fee_3").html('$'+obj_ero.app_sb_fee);
            $parent.find("#add_on_fee_3").html('$'+obj_ero.app_add_on_fee);
            $parent.find("#product_fee").html('$'+obj_ero.app_benefit);
            
            $parent.find("#application_id").val(obj_ero.applicent_id);
            //$parent.find("#app_type").val(obj_ero.status);
            
            $parent.find("#net_refund_amt_3_label").html('$'+obj_ero.app_net_refund_amt);
            
            for (var l = 0; l < obj_ero.prodcuts.length; l++) {
            	 obj_app_product = obj_ero.prodcuts[l];
            	
            	//producthtm += '<div class="col-md-2 service_img">';
            	//producthtm += ''+obj_app_product.img_source+'';
            	//producthtm += '</div>';
            	
            	producthtm += '<div class="col-md-2 service_img">';
            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
            			producthtm += '<i class="'+obj_app_product.img_source+'"></i>';
            		producthtm += '</div>';
            	producthtm += '</div>';
			
            	producthtm += '<div class="col-md-8 service_title_2">';
            	producthtm += '<h4>'+obj_app_product.prodcut_name+'</h4>';
            	producthtm += '</div>';
				
            	producthtm += '<div class="col-md-2 price_2 text-right">';
            	producthtm += '<h5>$'+obj_app_product.price+'</h5>';
            	producthtm += '</div>';
            	producthtm += '<div style="clear: both;"></div>';
				producthtm += '<hr style="margin: 20px 0px;">';
            	
            }
            
            $parent.find("#cart_details_view").empty().append(producthtm);
            
           if(obj_ero.payment_method == 'Check') {
        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp; <strong>'+obj_ero.payment_method+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
           }
           else if(obj_ero.payment_method == 'Direct Deposit') {
        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp;  Bank: <strong>'+obj_ero.app_bank_name+'</strong>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.app_account_no+'</strong>';
               pmathodhtm += '</div>';
               
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.app_routing_no+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
               
               
               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '</div>';
               
           }
           else if(obj_ero.payment_method == 'Debit Card') {
        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Card # <strong>XXX-XXXX-XXXX-'+obj_ero.card_number.substring(16,12);+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
               pmathodhtm += '</div>';
               
               pmathodhtm += '<div class="alert alert-success">';
               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
               pmathodhtm += '</div>';
               pmathodhtm += '</div>';
           }
           
           
           if(obj_ero.status == '0'){
          	 appststus = 'PENDING';
          	$parent.find("#ap_status_div").removeClass('alert-success').addClass('alert-warning');
          	$parent.find("#app_status_btn").css('display','none');
          	$parent.find("#print_check_icon").removeClass('icon-print').addClass('icon-busy');
          	
           }
           else if(obj_ero.status == '1'){
          	 appststus = 'READY TO PRINT';
          	$parent.find("#ap_status_div").removeClass('alert-warning').addClass('alert-success');
          	$parent.find("#app_status_btn").css('display','block');
          	$parent.find("#print_check_icon").removeClass('icon-busy').addClass('icon-print');
           //ap_status_div
           //$("#app_status").closest("#ap_status_div").modal('show');
           }else if(obj_ero.status == '2'){
          	 appststus = 'PRINTED CHECK';
          	$parent.find("#ap_status_div").removeClass('alert-warning').addClass('alert-success');
          	$parent.find("#app_status_btn").css('display','block');
          	$parent.find("#print_check_icon").removeClass('icon-busy').addClass('icon-print');
           }else if(obj_ero.status == '3'){
            	 appststus = 'VOIDED CHECK';
               	$parent.find("#ap_status_div").removeClass('alert-success').addClass('alert-warning');
               	$parent.find("#app_status_btn").css('display','none');
               	$parent.find("#print_check_icon").removeClass('icon-print').addClass('icon-busy');
               	
                }
           
           $parent.find("#app_status").html(appststus);
           $parent.find("#app_status_btn").val(obj_ero.app_id);
           
            
           $parent.find("#disbursement_method_details").empty().append(pmathodhtm);
        
         

        }
    }
    
    	$parent.find("#first_name_lab").css('display','block');
		 $parent.find("#first_name_div").css('display','none');
		 $parent.find("#last_name_lab").css('display','block');
		 $parent.find("#last_name_div").css('display','none');
		 $parent.find("#ss_number_lab").css('display','block');
		 $parent.find("#ss_number_div").css('display','none');
		 $parent.find("#dob_lab").css('display','block');
		 $parent.find("#dob_div").css('display','none');
		 $parent.find("#sp_first_name_lab").css('display','block');
		 $parent.find("#sp_first_name").css('display','none');
		 $parent.find("#sp_last_name_lab").css('display','block');
		 $parent.find("#sp_last_name").css('display','none');
		 $parent.find("#sp_ss_number_lab").css('display','block');
		 $parent.find("#sp_ss_number").css('display','none');
		 $parent.find("#sp_dob_lab").css('display','block');
		 $parent.find("#sp_dob").css('display','none');
		 $parent.find("#street_address_lab").css('display','block');
		 $parent.find("#street_address_div").css('display','none');
		 $parent.find("#city_lab").css('display','block');
		 $parent.find("#city_div").css('display','none');
		 $parent.find("#state_lab").css('display','block');
		 $parent.find("#state_div").css('display','none');
		 $parent.find("#zip_code_lab").css('display','block');
		 $parent.find("#zip_code_div").css('display','none');
		 $parent.find("#cell_phone_lab").css('display','block');
		 $parent.find("#cell_phone_div").css('display','none');
		 $parent.find("#email_add_lab").css('display','block');
		 $parent.find("#email_add_div").css('display','none');
		 $(".info_hr").css('display','block');
		 
		 
		 $parent.find("#saveData").css('display','none');
		 
    $parent.closest("#modal_editApplication").modal('show');
}

function editInsurance(uid, parent) {
    var $parent = $("#" + parent);
    //alert(parent);
    for (var k = 0; k < dataClient.length; k++) {
        var obj_ero = dataClient[k];
        var obj_app_product = '';
        var producthtm = '';
        var pmathodhtm = '';
        var appststus = '';
        var applicenthtm = '';
        var notehtm = '';
        var html = '';
        var obj_addition_info = '';
       // console.log(obj_ero.applicent_id);
       // console.log(uid);
        
        if (obj_ero.insurance_id == uid) {
        	//alert(uid);
        	//alert(obj_ero.app_id);
            $parent.find("#first_name_lab").html(obj_ero.first_name);
            $parent.find("#first_name").val(obj_ero.first_name);
            $parent.find("#last_name_lab").html(obj_ero.last_name);
            $parent.find("#last_name").val(obj_ero.last_name);
            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
            $parent.find("#ss_number").val(obj_ero.ss_number);
            $parent.find("#dob_lab").html(obj_ero.dob);
            $parent.find("#dob").val(obj_ero.dob);            
            
            if(obj_ero.sp_first_name == ''){
            //	$("#dependent-box").css({'display','none'});
            	$parent.find("#dependent-box").css('display','none');
        	}
            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
    	
            
            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
            $parent.find("#sp_dob").val(obj_ero.sp_dob);
            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
            $parent.find("#street_address").val(obj_ero.street_address_1);
            $parent.find("#city_lab").html(obj_ero.city);
            $parent.find("#city").val(obj_ero.city);
           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
            $parent.find("#state_lab").html(obj_ero.state);
           
            $parent.find("#state").val(obj_ero.state);
            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
            $parent.find("#zip_code").val(obj_ero.zip_code);
            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
            $parent.find("#cell_phone").val(obj_ero.cell_phone);
            $parent.find("#email_add_lab").html(obj_ero.email_add);
            $parent.find("#email_add").val(obj_ero.email_add);
            //$parent.find("#application_id").val(uid);
            $parent.find("#insurance_app_id").val(uid);
            $parent.find("#insurance_applicent_id").val(obj_ero.applicent_id);
            
            
            
            var appststus='';
            // selected Insurance Prodcuct
            /*
            if(obj_ero.products.length > 0){
            	for(var i = 0 ; i < obj_ero.products.length ; i++){           		
            		obj_products_info = obj_ero.products[i];
            		
            		if(obj_products_info.insurance_status == '0')
                   	 	appststus = '<span style="font-style: italic; color:#c09853;">Pending</span>';
                     else if(obj_products_info.insurance_status == '1')
                    	 appststus = '<span style="font-style: italic; color:#468847;">Active</span>';
                     else if(obj_products_info.insurance_status == '2')
                    	 appststus = '<span style="font-style: italic; color:#c09853;">Canceled</span>';
                    
                     
                    	producthtm += '<div class="col-md-3 service_img">';
                    		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
                    			producthtm += '<i class="'+obj_products_info.img_source+'"></i>';
                    		producthtm += '</div>';
                    	producthtm += '</div>';
        			
                    	producthtm += '<div class="col-md-7 service_title_2">';
                    	producthtm += '<h4>'+obj_products_info.prodcut_name+'</h4>';
                    	producthtm += ''+appststus+'';
                    	producthtm += '</div>';
                    	
                    	producthtm += '<div class="col-md-2 price_2 text-left" style="padding: 0px; margin-top: 25px;">';
                    	producthtm += '	<button class=" btn btn-white-red" onclick="editInsuranceStatus('+obj_products_info.app_pro_id+','+obj_products_info.insurance_status+');" type="button" id="" style="" value="Edit"><i class="icon-pencil6"></i> Edit</button>';
                    	producthtm += '</div>';
                    	
                    	producthtm += '<div style="clear: both;"></div>';
            		//console.log(obj_products_info.prodcut_name);
            	}
            }
            */
            
            
            if(obj_ero.insurance_item != '' && obj_ero.insurance_item !== null){
            	
            if(obj_ero.insurance_status == '0')
           	 	appststus = '<span style="font-style: italic; color:#c09853;">Pending</span>';
             else if(obj_ero.insurance_status == '1')
            	 appststus = '<span style="font-style: italic; color:#468847;">Active</span>';
             else if(obj_ero.insurance_status == '2')
            	 appststus = '<span style="font-style: italic; color:#c09853;">Canceled</span>';
            
             
            	producthtm += '<div class="col-md-3 service_img">';
            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
            			producthtm += '<i class="'+obj_ero.insurance_img_source+'"></i>';
            		producthtm += '</div>';
            	producthtm += '</div>';
			
            	producthtm += '<div class="col-md-7 service_title_2">';
            	producthtm += '<h4>'+obj_ero.insurance_item+'</h4>';
            	producthtm += ''+appststus+'';
            	producthtm += '</div>';
            	
            	producthtm += '<div class="col-md-2 price_2 text-left" style="padding: 0px; margin-top: 25px;">';
            	producthtm += '	<button class=" btn btn-white-red" onclick="editInsuranceStatus('+uid+','+obj_ero.insurance_status+');" type="button" id="" style="" value="Edit"><i class="icon-pencil6"></i> Edit</button>';
            	producthtm += '</div>';
				
				
            	producthtm += '<div style="clear: both;"></div>';
				
            }
         
            $('#selected_insurance').html(producthtm);
            
            
            // applicent 
		            applicenthtm += ' <div>';
		            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_ero.first_name+' '+obj_ero.last_name+'</strong><span style="color:#83d19f;"> &nbsp; &nbsp;(Primary)</span> 	<br>XXX-XX-'+obj_ero.ss_number.substring(7,11);+' </div>';
		            applicenthtm += '	<div style="clear:both;"></div><hr>';
		            applicenthtm += '</div>';
            if(obj_ero.applicents.length > 0){
	            for (var l = 0; l < obj_ero.applicents.length; l++) {
	           	 obj_app_app = obj_ero.applicents[l];
	            
	           	 	applicenthtm += ' <div>';
		            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_app.first_name+' '+obj_app_app.last_name+'</strong><br>XXX-XX-'+obj_app_app.ss_number.substring(7,11);+'</div>';
		            applicenthtm += '	<div style="clear:both;"></div><hr>';
		            applicenthtm += '</div>';
	            }
            }
            $('#selected_appl_sum').empty().html(applicenthtm);
            
            
            // additional info
            
           // if(obj_ero.products.length > 0){
            	var c = 0;
            	//for(var k = 0 ; k < obj_ero.products.length ; k++){           		
            		//obj_products_info = obj_ero.products[k];
            		//console.log(obj_products_info.prodcut_name);
            	//console.log(obj_ero.i_additional.length);
            		for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
            			obj_addition_info = obj_ero.i_additional[i];
            		//	console.log(obj_addition_info);
            			if(obj_ero.insurance_item  == 'Family Individual'){
	            	
						
						
						//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								//	html+= '<input type="text"  class="form-control ui-wizard-content" disabled="disabled" value="'+obj_addition_info.first_name+'" name="first_name" id="first_name">';
								html+= ''+obj_addition_info.first_name+'';
								html+= '</div>';
							html+= '</div>';
				                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									//html+= '<input type="text" class="form-control ui-wizard-content" disabled="disabled" value="'+obj_addition_info.last_name+'" name="last_name" id="last_name">';
								html+= ''+obj_addition_info.last_name+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
				            
				           
						html+= '<div class="form-group">';
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Coverage Start Date: </label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									//html+= '<input type="text" class="form-control ui-wizard-content input-datepicker family_coverage_date" data-date-format="yyyy-mm-dd" name="family_coverage_date[]" id="family_coverage_date" value="">';
									html+= ''+obj_addition_info.family_coverage_date+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_addition_info.family_gender+'';
								//html+= '<select name="family_gender[]" id="family_gender" class="form-control family_gender ui-wizard-content">';
									//html+= '<option></option>';
									//html+= '<option value="Male">Male</option>';
									//html+= '<option value="Female">Female</option>';
								//html+= '</select>';
							html+= '</div>';
						html+= '</div>';
				                
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.family_tobacco_use+'';	
									//html+= '<select name="family_tobacco_use[]" id="family_tobacco_use" class="form-control ui-wizard-content family_tobacco_use">';
				                    	//	html+= '<option></option>';
				                    	//	html+= '<option value="Yes">Yes</option>';
				                    	//	html+= '<option value= "No">No</option>';
				                    	//html+= '</select>';
				                    html+= '</div>';
				             html+= '</div>';
				      html+= '</div>';
				      html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
					//	}
					
	            }
	            if(obj_ero.insurance_item == 'Group Health'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
						
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Company name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.company_name_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Industry (Type of Business):</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.industry_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-7 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Company Address:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.company_address_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-2 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">State:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.state_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Zip:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.zip_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-8 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Requested Lines of Coverage:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.requested_line_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Current Carrier:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.current_carrier_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Current Renewal Date:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.renewal_date_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Requested Effective Date:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.effective_date_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            	//}
	            	//}
	            }
	            
	            if(obj_ero.insurance_item == 'Life Insurance & Annuities'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
						
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.last_name+'';
								html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.first_name+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
		           
						html+= '<div class="form-group">';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.gender_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Height:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.height_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Width:</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.width_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.tobacco_use_life+'';
								html+= '</div>';
							html+= '</div>';
					html+= '</div>';
					html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            	//}
	            	//}
	            }
	            if(obj_ero.insurance_item == 'Auto Insurance'){
	            	//console.log(obj_ero.i_additional.length);
	            	
	            	//for(var w = 0 ; w < obj_ero.i_additional.length ; w++){
						//obj_addition_info = obj_ero.i_additional[w];
						
						//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
							if(c < 1){
								//console.log('c='+c);
								c=1;
						/*html += '<div class="form-group">';
						html += '<div class="col-md-6 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].first_name+'';
							html += '</div>';
						html += '</div>';
		                
						html += '<div class="col-md-6 form-group1">';
							html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].last_name+'';
							html += '</div>';
						html += '</div>';
					html += '</div>';
		            */
					html += '<div class="form-group">';
						html += '<div class="col-md-4 form-group1">';
							html += '<label for="street_address" class="control-label col-md-12">Marital Status:  </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].marital_status_auto+'';
							html += '</div>';
						html += '</div>';
						html += '<div class="col-md-4 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].gender_auto+'';
							html += '</div>';
						html += '</div>';
					 html += '</div>';
		            
					html += '<div class="form-group">';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="street_address" class="control-label col-md-12">Year:  </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].year_auto+'';
							html += '</div>';
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Make: </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].make_auto+'';
							html += '</div>';	
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Model: </label>';
						html += '<div class="input-group1 nopadding col-md-12">';
						html+= ''+obj_ero.i_additional[i].model_auto+'';
						html += '</div>';	
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Coverage: </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].coverage_auto+'';
							html += '</div>';	
					html += '</div>';
		                
					html += '</div>';
					html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
					}
							
						//	if(obj_products_info.app_pro_id == obj_addition_info.product_id){
								
								//for(var j = 1 ; j < obj_ero.i_additional.length ; j++){
									
					if(i < parseInt(obj_ero.i_additional.length - 1)){
					var l = parseInt(i+1);
					//console.log(l);
									var obj3 = obj_ero.i_additional[l];
									
							
							html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.first_name+'';
								html += '</div>';
							html += '</div>';
			                
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.last_name+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						
						
						html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Relationship to Applicant: </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.relation_auto+'';
								html += '</div>';	
							html += '</div>';
							html += '<div class="col-md-3 form-group1">';
								html += '<label for="street_address" class="control-label col-md-12" style="padding-right: 0px;">Marital Status:  </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.marital_status_auto+'';
									html += '</div>';
							html += '</div>';
							html += '<div class="col-md-3 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.gender_auto+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
					}
						//}
					//	}
						
						//}
	            	//}
	            }
	            if(obj_ero.insurance_item == 'Home Insurance'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Coverage Start Date:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.coverage_date_home+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            	//}
	            	//}
	            }
	            if(obj_ero.insurance_item == 'Property & Casualty'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
						html+= '<div class="col-md-12 form-group1">';
						html+= '<label for="first_name" class="control-label col-md-12">Provide a realistic estimate of your gross revenue for the current financial year:</label>';
						html+= '<div class="input-group1 nopadding col-md-7">';
		                    html+= ''+obj_addition_info.revenue_property+'';
		                    html+= '</div>';
		               html+= '</div>';
		              html+= '</div>';
		            	  html+= '<div class="" style="clear:both"></div>';
		            		  html+= '<div class="form-group">  ';
		            			  html+= '<div class="col-md-12 form-group1">';
		            				  html+= '<label for="last_name" class="control-label col-md-12">Have any claims been filed against your business during the past 3 years:</label>';
		            				html+= '<div class="input-group1 nopadding col-md-3">';
		            					html+= ''+obj_addition_info.past_claims_property+'';
		              				html+= '</div>';
		              		html+= '</div>';
		              html+= '</div>';
		            html+= '<div class="" style="clear:both"></div>'; 
		            html+= '<div class="form-group">';
		            html+= '<div class="col-md-12 form-group1">';
		            	html+= '<label for="first_name" class="control-label col-md-12">Please indicate which types of insurance you would like to receive a quote for: <br> <small>Type of insurance needed (check all that apply)</small></label>';
		            		html+= '<div class="input-group1 nopadding col-md-12">';
		            			html+= '<div class="input-group1 nopadding col-md-12">';
			                    html+= ''+obj_addition_info.insurance_type_property+'';
		              				html+= '</div>';
		              			html+= '</div>';
		              			html+= '</div>';
		              html+= '</div>';
		              html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            	//}
	            	//}
	            }
	              
            		} 
            	//} prodcut loap
            	$('#insurance_additional_info').empty().html(html);
           // } // cehck prodcut exist or not
            
             
            
            
            
            if(obj_ero.notes.length > 0){
	            for (var m = 0; m < obj_ero.notes.length; m++) {
	           	 obj_app_notes = obj_ero.notes[m];
	            
	           	 	notehtm += ' <div>';
	           	 	notehtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_notes.firstname+' '+obj_app_notes.lastname+'</strong> - '+obj_app_notes.note_create_date+'<br>'+obj_app_notes.note_text+'</div>';
	           	 	notehtm += '	<div style="clear:both;"></div><hr>';
	           	 	notehtm += '</div>';
	            }
            }
            
            $('#insurance_note_div').empty().html(notehtm);
            
        }
    }
    
    	 $parent.find("#first_name_lab").css('display','block');
		 $parent.find("#first_name_div").css('display','none');
		 $parent.find("#last_name_lab").css('display','block');
		 $parent.find("#last_name_div").css('display','none');
		 $parent.find("#ss_number_lab").css('display','block');
		 $parent.find("#ss_number_div").css('display','none');
		 $parent.find("#dob_lab").css('display','block');
		 $parent.find("#dob_div").css('display','none');
		 $parent.find("#sp_first_name_lab").css('display','block');
		 $parent.find("#sp_first_name").css('display','none');
		 $parent.find("#sp_last_name_lab").css('display','block');
		 $parent.find("#sp_last_name").css('display','none');
		 $parent.find("#sp_ss_number_lab").css('display','block');
		 $parent.find("#sp_ss_number").css('display','none');
		 $parent.find("#sp_dob_lab").css('display','block');
		 $parent.find("#sp_dob").css('display','none');
		 $parent.find("#street_address_lab").css('display','block');
		 $parent.find("#street_address_div").css('display','none');
		 $parent.find("#city_lab").css('display','block');
		 $parent.find("#city_div").css('display','none');
		 $parent.find("#state_lab").css('display','block');
		 $parent.find("#state_div").css('display','none');
		 $parent.find("#zip_code_lab").css('display','block');
		 $parent.find("#zip_code_div").css('display','none');
		 $parent.find("#cell_phone_lab").css('display','block');
		 $parent.find("#cell_phone_div").css('display','none');
		 $parent.find("#email_add_lab").css('display','block');
		 $parent.find("#email_add_div").css('display','none');
		 $(".info_hr").css('display','block');
		 
		 
		 $parent.find("#saveData").css('display','none');
		
    $parent.closest("#modal_edit_insuracne_product").modal('show');
}

function editBenefitsStatus(id,status){
	//var $parent = $("#body_benefits_status_edit");
	//console.log(status);
	//$parent.find('input[name="bstatus"][value="' + status + '"]').attr('checked',true);
	
	$('input:radio[name="bstatus"][value="'+status+'"]').prop('checked', true);
	$("#modal_edit_benefits_product").modal('hide');
	$("#modal_edit_benefits_status").modal('show');
}

function editInsuranceStatus(id,status){
	//var $parent = $("#body_benefits_status_edit");
	//console.log(status);
	//$parent.find('input[name="bstatus"][value="' + status + '"]').attr('checked',true);
	
	$('input:radio[name="istatus"][value="'+status+'"]').prop('checked', true);
	$('#in_product_id').val(id);
	$("#modal_edit_insuracne_product").modal('hide');	
	$("#modal_edit_insurance_status").modal('show');
	
}




function editInsuranceFromReport(uid, parent) {
    var $parent = $("#" + parent);
    //alert(parent);
    for (var k = 0; k < dataClientIR.length; k++) {
        var obj_ero = dataClientIR[k];
        var obj_app_product = '';
        var producthtm = '';
        var pmathodhtm = '';
        var appststus = '';
        var applicenthtm = '';
        
        var notehtm = '';
        var html = '';
        var obj_addition_info = '';
        
       // console.log(obj_ero.applicent_id);
       // console.log(uid);
        if (obj_ero.insurance_id == uid) {
        //if (obj_ero.applicent_id == uid) {
        	//alert(uid);
        	//alert(obj_ero.app_id);
            $parent.find("#first_name_lab").html(obj_ero.first_name);
            $parent.find("#first_name").val(obj_ero.first_name);
            $parent.find("#last_name_lab").html(obj_ero.last_name);
            $parent.find("#last_name").val(obj_ero.last_name);
            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
            $parent.find("#ss_number").val(obj_ero.ss_number);
            $parent.find("#dob_lab").html(obj_ero.dob);
            $parent.find("#dob").val(obj_ero.dob);            
            
            if(obj_ero.sp_first_name == ''){
            //	$("#dependent-box").css({'display','none'});
            	$parent.find("#dependent-box").css('display','none');
        	}
            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
    	
            
            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
            $parent.find("#sp_dob").val(obj_ero.sp_dob);
            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
            $parent.find("#street_address").val(obj_ero.street_address_1);
            $parent.find("#city_lab").html(obj_ero.city);
            $parent.find("#city").val(obj_ero.city);
           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
            $parent.find("#state_lab").html(obj_ero.state);
           
            $parent.find("#state").val(obj_ero.state);
            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
            $parent.find("#zip_code").val(obj_ero.zip_code);
            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
            $parent.find("#cell_phone").val(obj_ero.cell_phone);
            $parent.find("#email_add_lab").html(obj_ero.email_add);
            $parent.find("#email_add").val(obj_ero.email_add);
            //$parent.find("#application_id").val(uid);
            $parent.find("#insurance_app_id").val(uid);
            
         
            var appststus='';
            // selected Insurance Prodcuct
            /*
            if(obj_ero.products.length > 0){
            	for(var d = 0 ; d < obj_ero.products.length ; d++){           		
            		obj_products_info = obj_ero.products[d];
            		
            		if(obj_products_info.insurance_status == '0')
                   	 	appststus = '<span style="font-style: italic; color:#c09853;">Pending</span>';
                     else if(obj_products_info.insurance_status == '1')
                    	 appststus = '<span style="font-style: italic; color:#468847;">Active</span>';
                     else if(obj_products_info.insurance_status == '2')
                    	 appststus = '<span style="font-style: italic; color:#c09853;">Canceled</span>';
                    
                     
                    	producthtm += '<div class="col-md-3 service_img">';
                    		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
                    			producthtm += '<i class="'+obj_products_info.img_source+'"></i>';
                    		producthtm += '</div>';
                    	producthtm += '</div>';
        			
                    	producthtm += '<div class="col-md-7 service_title_2">';
                    	producthtm += '<h4>'+obj_products_info.prodcut_name+'</h4>';
                    	producthtm += ''+appststus+'';
                    	producthtm += '</div>';
                    	
                    	producthtm += '<div class="col-md-2 price_2 text-left" style="padding: 0px; margin-top: 25px;">';
                    	producthtm += '	<button class=" btn btn-white-red" onclick="editInsuranceStatus('+obj_products_info.app_pro_id+','+obj_products_info.insurance_status+');" type="button" id="" style="" value="Edit"><i class="icon-pencil6"></i> Edit</button>';
                    	producthtm += '</div>';
                    	
                    	producthtm += '<div style="clear: both;"></div>';
            		//console.log(obj_products_info.prodcut_name);
            	}
            }
            */
            
            if(obj_ero.insurance_item != '' && obj_ero.insurance_item !== null){
            	
            if(obj_ero.insurance_status == '0')
           	 	appststus = '<span style="font-style: italic; color:#c09853;">Pending</span>';
             else if(obj_ero.insurance_status == '1')
            	 appststus = '<span style="font-style: italic; color:#468847;">Active</span>';
             else if(obj_ero.insurance_status == '2')
            	 appststus = '<span style="font-style: italic; color:#c09853;">Canceled</span>';
            
             
            	producthtm += '<div class="col-md-3 service_img">';
            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
            			producthtm += '<i class="'+obj_ero.insurance_img_source+'"></i>';
            		producthtm += '</div>';
            	producthtm += '</div>';
			
            	producthtm += '<div class="col-md-7 service_title_2">';
            	producthtm += '<h4>'+obj_ero.insurance_item+'</h4>';
            	producthtm += ''+appststus+'';
            	producthtm += '</div>';
            	
            	producthtm += '<div class="col-md-2 price_2 text-left" style="padding: 0px; margin-top: 25px;">';
            	producthtm += '	<button class=" btn btn-white-red" onclick="editInsuranceStatus('+uid+','+obj_ero.insurance_status+');" type="button" id="" style="" value="Edit"><i class="icon-pencil6"></i> Edit</button>';
            	producthtm += '</div>';
				
				
            	producthtm += '<div style="clear: both;"></div>';
				
            }
         
            $('#selected_insurance').html(producthtm);
            
            
            // applicent 
		            applicenthtm += ' <div>';
		            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_ero.first_name+' '+obj_ero.last_name+'</strong><span style="color:#83d19f;"> &nbsp; &nbsp;(Primary)</span> 	<br>XXX-XX-'+obj_ero.ss_number.substring(7,11);+' </div>';
		            applicenthtm += '	<div style="clear:both;"></div><hr>';
		            applicenthtm += '</div>';
            if(obj_ero.applicents.length > 0){
            //	console.log('applicent='+obj_ero.applicents.length);
	            for (var l = 0; l < obj_ero.applicents.length; l++) {
	           	 obj_app_app = obj_ero.applicents[l];
	            
	           	 	applicenthtm += ' <div>';
		            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_app.first_name+' '+obj_app_app.last_name+'</strong><br>XXX-XX-'+obj_app_app.ss_number.substring(7,11);+'</div>';
		            applicenthtm += '	<div style="clear:both;"></div><hr>';
		            applicenthtm += '</div>';
	            }
            }
            $('#selected_appl_sum').empty().html(applicenthtm);
         //   console.log('applicent='+applicenthtm);
            
            
            // additional info
            
            //if(obj_ero.products.length > 0){
            	var c = 0;
            	//for(var k = 0 ; k < obj_ero.products.length ; k++){           		
            		//obj_products_info = obj_ero.products[k];
            		
            		for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
            			//console.log(obj_products_info.prodcut_name);
            			obj_addition_info = obj_ero.i_additional[i];
            			
            			if(obj_ero.insurance_item == 'Family Individual'){
	            	
						
						
						//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">First Name</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								//	html+= '<input type="text"  class="form-control ui-wizard-content" disabled="disabled" value="'+obj_addition_info.first_name+'" name="first_name" id="first_name">';
								html+= ''+obj_addition_info.first_name+'';
								html+= '</div>';
							html+= '</div>';
				                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Last Name</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									//html+= '<input type="text" class="form-control ui-wizard-content" disabled="disabled" value="'+obj_addition_info.last_name+'" name="last_name" id="last_name">';
								html+= ''+obj_addition_info.last_name+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
				            
				           
						html+= '<div class="form-group">';
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Coverage Start Date </label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									//html+= '<input type="text" class="form-control ui-wizard-content input-datepicker family_coverage_date" data-date-format="yyyy-mm-dd" name="family_coverage_date[]" id="family_coverage_date" value="">';
									html+= ''+obj_addition_info.family_coverage_date+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Gender</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_addition_info.family_gender+'';
								//html+= '<select name="family_gender[]" id="family_gender" class="form-control family_gender ui-wizard-content">';
									//html+= '<option></option>';
									//html+= '<option value="Male">Male</option>';
									//html+= '<option value="Female">Female</option>';
								//html+= '</select>';
							html+= '</div>';
						html+= '</div>';
				                
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use </label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.family_tobacco_use+'';	
									//html+= '<select name="family_tobacco_use[]" id="family_tobacco_use" class="form-control ui-wizard-content family_tobacco_use">';
				                    	//	html+= '<option></option>';
				                    	//	html+= '<option value="Yes">Yes</option>';
				                    	//	html+= '<option value= "No">No</option>';
				                    	//html+= '</select>';
				                    html+= '</div>';
				             html+= '</div>';
				      html+= '</div>';
				      html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
						//}
					
	            }
	            if(obj_ero.insurance_item == 'Group Health'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
						
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Company name</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.company_name_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Industry (Type of Business)</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.industry_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-7 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Company Address</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.company_address_grouphealth+'';
									html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-2 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">State</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.state_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Zip</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.zip_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-8 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Requested Lines of Coverage</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.requested_line_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-4 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Current Carrier</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.current_carrier_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
		            
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Current Renewal Date</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.renewal_date_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Requested Effective Date</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.effective_date_grouphealth+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            	//}
	           //	}
	            }
	            
	            if(obj_ero.insurance_item == 'Life Insurance & Annuities'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
						
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">First Name</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.last_name+'';
								html+= '</div>';
							html+= '</div>';
		                
							html+= '<div class="col-md-6 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Last Name</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.first_name+'';
								html+= '</div>';
							html+= '</div>';
						html+= '</div>';
		            
		           
						html+= '<div class="form-group">';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="first_name" class="control-label col-md-12">Gender</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.gender_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Height</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.height_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="street_address" class="control-label col-md-12">Width</label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.width_life+'';
								html+= '</div>';
							html+= '</div>';
							html+= '<div class="col-md-3 form-group1">';
								html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use </label>';
								html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.tobacco_use_life+'';
								html+= '</div>';
							html+= '</div>';
					html+= '</div>';
					html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            	//}
	            	//}
	            }
	            if(obj_ero.insurance_item == 'Auto Insurance'){
	            	//console.log(obj_ero.i_additional.length);
	            	
	            	//for(var w = 0 ; w < obj_ero.i_additional.length ; w++){
						//obj_addition_info = obj_ero.i_additional[w];
						
						//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
							if(c < 1){
							//	console.log('c='+c);
								c=1;
						html += '<div class="form-group">';
						html += '<div class="col-md-6 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">First Name</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].first_name+'';
							html += '</div>';
						html += '</div>';
		                
						html += '<div class="col-md-6 form-group1">';
							html += '<label for="last_name" class="control-label col-md-12">Last Name</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].last_name+'';
							html += '</div>';
						html += '</div>';
					html += '</div>';
		            
					html += '<div class="form-group">';
						html += '<div class="col-md-4 form-group1">';
							html += '<label for="street_address" class="control-label col-md-12">Marital Status  </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].marital_status_auto+'';
							html += '</div>';
						html += '</div>';
						html += '<div class="col-md-4 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Gender</label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].gender_auto+'';
							html += '</div>';
						html += '</div>';
					 html += '</div>';
		            
					html += '<div class="form-group">';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="street_address" class="control-label col-md-12">Year  </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].year_auto+'';
							html += '</div>';
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Make </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].make_auto+'';
							html += '</div>';	
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Model </label>';
						html += '<div class="input-group1 nopadding col-md-12">';
						html+= ''+obj_ero.i_additional[i].model_auto+'';
						html += '</div>';	
					html += '</div>';
					html += '<div class="col-md-3 form-group1">';
						html += '<label for="first_name" class="control-label col-md-12">Coverage </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].coverage_auto+'';
							html += '</div>';	
					html += '</div>';
		                
					html += '</div>';
					html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
					}
							
						//	if(obj_products_info.app_pro_id == obj_addition_info.product_id){
								
								//for(var j = 1 ; j < obj_ero.i_additional.length ; j++){
									
					if(i < parseInt(obj_ero.i_additional.length - 1)){
					var l = parseInt(i+1);
					//console.log(l);
									var obj3 = obj_ero.i_additional[l];
									
							
							html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">First Name</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.first_name+'';
								html += '</div>';
							html += '</div>';
			                
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="last_name" class="control-label col-md-12">Last Name</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.last_name+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						
						
						html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Relationship to Applicant </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.relation_auto+'';
								html += '</div>';	
							html += '</div>';
							html += '<div class="col-md-3 form-group1">';
								html += '<label for="street_address" class="control-label col-md-12" style="padding-right: 0px;">Marital Status  </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.marital_status_auto+'';
									html += '</div>';
							html += '</div>';
							html += '<div class="col-md-3 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Gender</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj3.gender_auto+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
					}
						//}
					//	}
						
						//}
	            	//}
	            }
	            if(obj_ero.insurance_item == 'Home Insurance'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Coverage Start Date</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.coverage_date_home+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            	//}
	            //	}
	            }
	            if(obj_ero.insurance_item == 'Property & Casualty'){
	            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
						//obj_addition_info = obj_ero.i_additional[i];
	            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
						html+= '<div class="form-group">';
						html+= '<div class="col-md-12 form-group1">';
						html+= '<label for="first_name" class="control-label col-md-12">Provide a realistic estimate of your gross revenue for the current financial year</label>';
						html+= '<div class="input-group1 nopadding col-md-7">';
		                    html+= ''+obj_addition_info.revenue_property+'';
		                    html+= '</div>';
		               html+= '</div>';
		              html+= '</div>';
		            	  html+= '<div class="" style="clear:both"></div>';
		            		  html+= '<div class="form-group">  ';
		            			  html+= '<div class="col-md-12 form-group1">';
		            				  html+= '<label for="last_name" class="control-label col-md-12">Have any claims been filed against your business during the past 3 years</label>';
		            				html+= '<div class="input-group1 nopadding col-md-3">';
		            					html+= ''+obj_addition_info.past_claims_property+'';
		              				html+= '</div>';
		              		html+= '</div>';
		              html+= '</div>';
		            html+= '<div class="" style="clear:both"></div>'; 
		            html+= '<div class="form-group">';
		            html+= '<div class="col-md-12 form-group1">';
		            	html+= '<label for="first_name" class="control-label col-md-12">Please indicate which types of insurance you would like to receive a quote for: <br> <small>Type of insurance needed (check all that apply)</small></label>';
		            		html+= '<div class="input-group1 nopadding col-md-12">';
		            			html+= '<div class="input-group1 nopadding col-md-12">';
			                    html+= ''+obj_addition_info.insurance_type_property+'';
		              				html+= '</div>';
		              			html+= '</div>';
		              			html+= '</div>';
		              html+= '</div>';
		              html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
	            	//}
	            	//}
	            }
	              
            		} 
            	//}
            		$('#insurance_additional_info').empty().html(html);
          //  }
            
             
            
            
            
            if(obj_ero.notes.length > 0){
	            for (var m = 0; m < obj_ero.notes.length; m++) {
	           	 obj_app_notes = obj_ero.notes[m];
	            
	           	 	notehtm += ' <div>';
	           	 	notehtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_notes.firstname+' '+obj_app_notes.lastname+'</strong> - '+obj_app_notes.note_create_date+'<br>'+obj_app_notes.note_text+'</div>';
	           	 	notehtm += '	<div style="clear:both;"></div><hr>';
	           	 	notehtm += '</div>';
	            }
            }
            
            $('#insurance_note_div').empty().html(notehtm);
        }
    }
    
    	 $parent.find("#first_name_lab").css('display','block');
		 $parent.find("#first_name_div").css('display','none');
		 $parent.find("#last_name_lab").css('display','block');
		 $parent.find("#last_name_div").css('display','none');
		 $parent.find("#ss_number_lab").css('display','block');
		 $parent.find("#ss_number_div").css('display','none');
		 $parent.find("#dob_lab").css('display','block');
		 $parent.find("#dob_div").css('display','none');
		 $parent.find("#sp_first_name_lab").css('display','block');
		 $parent.find("#sp_first_name").css('display','none');
		 $parent.find("#sp_last_name_lab").css('display','block');
		 $parent.find("#sp_last_name").css('display','none');
		 $parent.find("#sp_ss_number_lab").css('display','block');
		 $parent.find("#sp_ss_number").css('display','none');
		 $parent.find("#sp_dob_lab").css('display','block');
		 $parent.find("#sp_dob").css('display','none');
		 $parent.find("#street_address_lab").css('display','block');
		 $parent.find("#street_address_div").css('display','none');
		 $parent.find("#city_lab").css('display','block');
		 $parent.find("#city_div").css('display','none');
		 $parent.find("#state_lab").css('display','block');
		 $parent.find("#state_div").css('display','none');
		 $parent.find("#zip_code_lab").css('display','block');
		 $parent.find("#zip_code_div").css('display','none');
		 $parent.find("#cell_phone_lab").css('display','block');
		 $parent.find("#cell_phone_div").css('display','none');
		 $parent.find("#email_add_lab").css('display','block');
		 $parent.find("#email_add_div").css('display','none');
		 $(".info_hr").css('display','block');
		 
		 
		 $parent.find("#saveData").css('display','none');
		
    $parent.closest("#modal_edit_insuracne_product").modal('show');
}


function editCustomer(uid, parent) {
	 var $parent = $("#" + parent);
	 
	    	 $.post("customers/showDetailsAboutSelectedCustomer", {
        			senddata : 'yes',
        			cid : uid,
      		}, function(data) {
      			
      			
      			$('#summary_div').hide();
      			$('#files_div').hide();
      			$('#bank_insurance_additional_info').hide();
      			
      			$parent.find('#in_note_div').css('display','none');
	            $parent.find('#selected_applicent_div').hide();
	            $parent.find('#additional_info_div').hide();
	            $parent.find('#insurance_item_div').hide();
	            
	            $('#benefits_note').hide();
      	        $('#benefits_seleted_applicent').hide(); 
      	        $('#benefits_item_div').hide();
      	        
      	        
      			if (typeof data === 'object') {
        	 var result = data;
        	// var obj2 = result.allapplication[0];
        	// var obj3 = result.allbenefits[0];
        	// var obj4 = result.allinsurance[0];
        	 
        	 
        	 //console.log(result.allapplication.length);
        	 //console.log( message[0].app_id);
	    for (var k = 0; k < result.allapplication.length; k++) {
	        var obj_ero = result.allapplication[k];
	        //  console.log(obj_ero);
	        // var obj_app_product = '';
	        var producthtm = '';
	        var pmathodhtm = '';
	        var appststus = '';
	        var html = '';
	        //console.log(uid);
	        //console.log(obj_ero.applicent_id);
	        if (obj_ero.applicent_id == uid) {
	        	//alert(uid);
	        	//alert(obj_ero.app_id);
	            $parent.find("#first_name_lab").html(obj_ero.first_name);
	            $parent.find("#first_name").val(obj_ero.first_name);
	            $parent.find("#last_name_lab").html(obj_ero.last_name);
	            $parent.find("#last_name").val(obj_ero.last_name);
	            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
	            $parent.find("#ss_number").val(obj_ero.ss_number);
	            $parent.find("#dob_lab").html(obj_ero.dob);
	            $parent.find("#dob").val(obj_ero.dob);            
	            
	            if(obj_ero.sp_first_name == ''){
	            //	$("#dependent-box").css({'display','none'});
	            	$parent.find("#dependent-box").css('display','none');
	        	}
	            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
	            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
	           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
	           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
	    	
	            
	            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
	            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
	            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
	            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
	            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
	            $parent.find("#sp_dob").val(obj_ero.sp_dob);
	            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
	            $parent.find("#street_address").val(obj_ero.street_address_1);
	            $parent.find("#city_lab").html(obj_ero.city);
	            $parent.find("#city").val(obj_ero.city);
	           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
	            $parent.find("#state_lab").html(obj_ero.state);
	           
	            $parent.find("#state").val(obj_ero.state);
	            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
	            $parent.find("#zip_code").val(obj_ero.zip_code);
	            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
	            $parent.find("#cell_phone").val(obj_ero.cell_phone);
	            $parent.find("#email_add_lab").html(obj_ero.email_add);
	            $parent.find("#email_add").val(obj_ero.email_add);
	            
	            $parent.find("#total_refund_amt_3").html('$'+obj_ero.app_total_refund_amt);
	            $parent.find("#banking_fee").html('$'+obj_ero.app_total_fees);
	            $parent.find("#tax_preparation_fee_3").html('$'+obj_ero.app_tax_preparation_fee);
	            $parent.find("#bank_transmission_fee_3").html('$'+obj_ero.app_bank_transmission_fee);
	            $parent.find("#sb_fee_3").html('$'+obj_ero.app_sb_fee);
	            $parent.find("#add_on_fee_3").html('$'+obj_ero.app_add_on_fee);
	            $parent.find("#product_fee").html('$'+obj_ero.app_benefit);
	            
	            $parent.find("#application_id").val(obj_ero.applicent_id);
	            $parent.find("#app_type").val(obj_ero.status);
	            
	            $parent.find("#net_refund_amt_3_label").html('$'+obj_ero.app_net_refund_amt);
	            
	            if(obj_ero.audit_guard_item != '' && obj_ero.audit_guard_item !== null){	
	            	producthtm += '<div class="col-md-3 service_img">';
	            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
	            			producthtm += '<i class="'+obj_ero.audit_guard_img_source+'"></i>';
	            		producthtm += '</div>';
	            	producthtm += '</div>';
				
	            	producthtm += '<div class="col-md-7 service_title_2" style="padding:0px">';
	            	producthtm += '<h4>'+obj_ero.audit_guard_item+'</h4>';
	            	producthtm += '</div>';
					
	            	producthtm += '<div class="col-md-2 price_2 text-right">';
	            	producthtm += '<h5>$'+obj_ero.audit_guard_fee+'</h5>';
	            	producthtm += '</div>';
	            	producthtm += '<div style="clear: both;"></div>';
					producthtm += '<hr style="margin: 20px 0px;">';
	            }	
	           // }
	           
	            //benefits
	            if(obj_ero.benefits.length > 0){
	            	obj_benefits_info = obj_ero.benefits[0];
	                	producthtm += '<div class="col-md-3 service_img">';
		            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
		            			producthtm += '<i class="'+obj_benefits_info.benefits_img_source+'"></i>';
		            		producthtm += '</div>';
		            	producthtm += '</div>';
					
		            	producthtm += '<div class="col-md-7 service_title_2"  style="padding:0px">';
		            	producthtm += '<h4>'+obj_benefits_info.benefits_item+'</h4>';
		            	producthtm += '</div>';
						
		            	producthtm += '<div class="col-md-2 price_2 text-right">';
		            	producthtm += '<h5>$'+obj_benefits_info.benefits_price+'</h5>';
		            	producthtm += '</div>';
		            	producthtm += '<div style="clear: both;"></div>';
						producthtm += '<hr style="margin: 20px 0px;">';
		        
	            }
	            
	            
	            if(obj_ero.insurance.length > 0){
	            	for(var i = 0 ; i < obj_ero.insurance.length ; i++){           		
	            		obj_insurance_products_info = obj_ero.insurance[i];
	            
	            	producthtm += '<div class="col-md-3 service_img"> ';
	            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
	            			producthtm += '<i class="'+obj_insurance_products_info.insurance_img_source+'"></i>';
	            		producthtm += '</div>';
	            	producthtm += '</div>';
				
	            	producthtm += '<div class="col-md-9 service_title_2"  style="padding:0px">';
	            	producthtm += '<h4>'+obj_insurance_products_info.insurance_item+'</h4>';
	            	producthtm += '</div>';
					
	            	producthtm += '<div style="clear: both;"></div>';
					producthtm += '<hr style="margin: 20px 0px;">';
	            	}
	            }
	            
	            $parent.find("#cart_details_view").empty().append(producthtm);
	            
	            
	           if(obj_ero.payment_method == 'Check') {
	        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp; <strong>'+obj_ero.payment_method+'</strong>';
	               pmathodhtm += '</div>';
	               pmathodhtm += '</div>';
	               
	               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
	               pmathodhtm += '</div>';
	               
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
	               pmathodhtm += '</div>';
	               pmathodhtm += '</div>';
	           }
	           else if(obj_ero.payment_method == 'Direct Deposit') {
	        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp;  Bank: <strong>'+obj_ero.app_bank_name+'</strong>';
	               pmathodhtm += '</div>';
	               
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.app_account_no+'</strong>';
	               pmathodhtm += '</div>';
	               
	               
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '    <i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.app_routing_no+'</strong>';
	               pmathodhtm += '</div>';
	               pmathodhtm += '</div>';
	               
	               
	               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
	               pmathodhtm += '</div>';
	               
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
	               pmathodhtm += '</div>';
	               
	               pmathodhtm += '</div>';
	               
	           }
	           else if(obj_ero.payment_method == 'Debit Card') {
	        	   pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Card # <strong>XXX-XXXX-XXXX-'+obj_ero.card_number.substring(16,12);+'</strong>';
	               pmathodhtm += '</div>';
	               pmathodhtm += '</div>';
	               
	               pmathodhtm += '<div class="col-md-6 "  style="margin-top:20px">';
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Account # <strong>'+obj_ero.assign_acc_no+'</strong> <br>';
	               pmathodhtm += '</div>';
	               
	               pmathodhtm += '<div class="alert alert-success">';
	               pmathodhtm += '<i class="icon-ok"></i> &nbsp; &nbsp;  Routing # <strong>'+obj_ero.assign_routing_no+'</strong>';
	               pmathodhtm += '</div>';
	               pmathodhtm += '</div>';
	           }
	           
	           
	           if(obj_ero.status == '0'){
	          	 appststus = 'PENDING';
	          	$parent.find("#ap_status_div").removeClass('alert-success').addClass('alert-warning');
	          	$parent.find("#app_status_btn").css('display','none');
	          	$parent.find("#print_check_icon").removeClass('icon-print').addClass('icon-busy');
	          	
	           }
	           else if(obj_ero.status == '1'){
	          	 appststus = 'READY TO PRINT';
	          	$parent.find("#ap_status_div").removeClass('alert-warning').addClass('alert-success');
	          	$parent.find("#app_status_btn").css('display','block');
	          	$parent.find("#print_check_icon").removeClass('icon-busy').addClass('icon-print');
	           //ap_status_div
	           //$("#app_status").closest("#ap_status_div").modal('show');
	           }else if(obj_ero.status == '2'){
	          	 appststus = 'PRINTED CHECK';
	          	$parent.find("#ap_status_div").removeClass('alert-warning').addClass('alert-success');
	          	$parent.find("#app_status_btn").css('display','block');
	          	$parent.find("#print_check_icon").removeClass('icon-busy').addClass('icon-print');
	           }else if(obj_ero.status == '3'){
	            	 appststus = 'VOIDED CHECK';
	               	$parent.find("#ap_status_div").removeClass('alert-success').addClass('alert-warning');
	               	$parent.find("#app_status_btn").css('display','none');
	               	$parent.find("#print_check_icon").removeClass('icon-print').addClass('icon-busy');
	               	
	                }
	           
	           $parent.find("#app_status").html(appststus);
	           $parent.find("#app_status_btn").val(obj_ero.app_id);
	           
	            
	           $parent.find("#disbursement_method_details").empty().append(pmathodhtm);
	        
	         // for additional info
	           
	           $parent.find('#bank_insurance_additional_info').css('display','none');
	           //console.log(obj_ero.i_additional.length);
	           
			if(obj_ero.insurance.length > 0){
				for(var n = 0 ; n < obj_ero.insurance.length ; n++){           		
	        		obj_insurance_info = obj_ero.insurance[n];
	        		
				if(obj_insurance_info.i_additional.length > 0){
					
					var c = 0;
					obj_addition_info = obj_insurance_info.i_additional[0];
	        	   
					html+= '<section class="panel">';
	        		  html+= '<header class="panel-heading">';
	        			html+= '<span class="title" style="">'+obj_addition_info.insurance_title+' Order Information</span>';
	        			html+= '<span class="tools pull-right icons" style=""><i class="'+obj_insurance_info.insurance_img_source+'"></i></span>';
	        		html+= '</header>';
	        		html+= '<div class="panel-body" style="padding:10px 17px;">';
	               			
		            if(obj_insurance_info.insurance_item == 'Family Individual'){
		        			html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.first_name+'';
									html+= '</div>';
								html+= '</div>';
					                
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.last_name+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
					            
					           
							html+= '<div class="form-group">';
								html+= '<div class="col-md-5 form-group1">';
									html+= '<label for="street_address" class="control-label col-md-12">Coverage Start Date: </label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.family_coverage_date+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.family_gender+'';
								html+= '</div>';
							html+= '</div>';
					                
								html+= '<div class="col-md-4 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.family_tobacco_use+'';	
										html+= '</div>';
					             html+= '</div>';
					      html+= '</div>';
					      html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
		          }
		            if(obj_insurance_info.insurance_item == 'Group Health'){
		         			html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Company name:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.company_name_grouphealth+'';
										html+= '</div>';
								html+= '</div>';
			                
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Industry (Type of Business):</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.industry_grouphealth+'';
										html+= '</div>';
								html+= '</div>';
							html+= '</div>';
			            
							html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Company Address:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.company_address_grouphealth+'';
										html+= '</div>';
								html+= '</div>';
			                
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">State:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.state_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Zip:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.zip_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
			            
							html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Requested Lines of Coverage:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.requested_line_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Current Carrier:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.current_carrier_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
			            
			            
							html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="street_address" class="control-label col-md-12">Current Renewal Date:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.renewal_date_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Requested Effective Date:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.effective_date_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
				    }
		            if(obj_insurance_info.insurance_item == 'Life Insurance & Annuities'){
		       			html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.last_name+'';
									html+= '</div>';
								html+= '</div>';
			                
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.first_name+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
			            
			           
							html+= '<div class="form-group">';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.gender_life+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="street_address" class="control-label col-md-12">Height:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.height_life+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="street_address" class="control-label col-md-12">Width:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.width_life+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.tobacco_use_life+'';
									html+= '</div>';
								html+= '</div>';
						html+= '</div>';
					}
		             if(obj_insurance_info.insurance_item == 'Auto Insurance'){
								if(c < 1){
									//console.log('c='+c);
									c=1;
									
							html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_insurance_info.i_additional[i].first_name+'';
								html += '</div>';
							html += '</div>';
			                
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_insurance_info.i_additional[i].last_name+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
			            
						html += '<div class="form-group">';
							html += '<div class="col-md-4 form-group1">';
								html += '<label for="street_address" class="control-label col-md-12">Marital Status:  </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_insurance_info.i_additional[i].marital_status_auto+'';
								html += '</div>';
							html += '</div>';
							html += '<div class="col-md-4 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_insurance_info.i_additional[i].gender_auto+'';
								html += '</div>';
							html += '</div>';
						 html += '</div>';
			            
						html += '<div class="form-group">';
						html += '<div class="col-md-3 form-group1">';
							html += '<label for="street_address" class="control-label col-md-12">Year:  </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_insurance_info.i_additional[i].year_auto+'';
								html += '</div>';
						html += '</div>';
						html += '<div class="col-md-3 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Make: </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_insurance_info.i_additional[i].make_auto+'';
								html += '</div>';	
						html += '</div>';
						html += '<div class="col-md-3 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Model: </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_insurance_info.i_additional[i].model_auto+'';
							html += '</div>';	
						html += '</div>';
						html += '<div class="col-md-3 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Coverage: </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_insurance_info.i_additional[i].coverage_auto+'';
								html += '</div>';	
						html += '</div>';
			                
						html += '</div>';
								}
							
								if(i < parseInt(obj_insurance_info.i_additional.length - 1)){
									var l = parseInt(i+1);
									//console.log(l);
													var obj3 = obj_insurance_info.i_additional[l];
													
								html += '<div class="form-group">';
								html += '<div class="col-md-6 form-group1">';
									html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
									html += '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj3.first_name+'';
									html += '</div>';
								html += '</div>';
				                
								html += '<div class="col-md-6 form-group1">';
									html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
									html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.last_name+'';
									html += '</div>';
								html += '</div>';
							html += '</div>';
							
							
							html += '<div class="form-group">';
								html += '<div class="col-md-6 form-group1">';
									html += '<label for="first_name" class="control-label col-md-12">Relationship to Applicant: </label>';
									html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.relation_auto+'';
									html += '</div>';	
								html += '</div>';
								html += '<div class="col-md-3 form-group1">';
									html += '<label for="street_address" class="control-label col-md-12" style="padding-right: 0px;">Marital Status:  </label>';
									html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.marital_status_auto+'';
										html += '</div>';
								html += '</div>';
								html += '<div class="col-md-3 form-group1">';
									html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
									html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.gender_auto+'';
									html += '</div>';
								html += '</div>';
							html += '</div>';
							html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
								}
					}
		            if(obj_insurance_info.insurance_item == 'Home Insurance'){
		           		html += '<div class="form-group">';
								html += '<div class="col-md-6 form-group1">';
									html += '<label for="first_name" class="control-label col-md-12">Coverage Start Date:</label>';
									html += '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.coverage_date_home+'';
									html += '</div>';
								html += '</div>';
							html += '</div>';
				    }
		            if(obj_insurance_info.insurance_item == 'Property & Casualty'){
		            		html+= '<div class="form-group">';
							html+= '<div class="col-md-12 form-group1">';
							html+= '<label for="first_name" class="control-label col-md-12">Provide a realistic estimate of your gross revenue for the current financial year:</label>';
							html+= '<div class="input-group1 nopadding col-md-7">';
			                    html+= ''+obj_addition_info.revenue_property+'';
			                    html+= '</div>';
			               html+= '</div>';
			              html+= '</div>';
			            	  html+= '<div class="" style="clear:both"></div>';
			            		  html+= '<div class="form-group">  ';
			            			  html+= '<div class="col-md-12 form-group1">';
			            				  html+= '<label for="last_name" class="control-label col-md-12">Have any claims been filed against your business during the past 3 years:</label>';
			            				html+= '<div class="input-group1 nopadding col-md-3">';
			            					html+= ''+obj_addition_info.past_claims_property+'';
			              				html+= '</div>';
			              		html+= '</div>';
			              html+= '</div>';
			            html+= '<div class="" style="clear:both"></div>'; 
			            html+= '<div class="form-group">';
			            html+= '<div class="col-md-12 form-group1">';
			            	html+= '<label for="first_name" class="control-label col-md-12">Please indicate which types of insurance you would like to receive a quote for: <br> <small>Type of insurance needed (check all that apply)</small></label>';
			            		html+= '<div class="input-group1 nopadding col-md-12">';
			            			html+= '<div class="input-group1 nopadding col-md-12">';
				                    html+= ''+obj_addition_info.insurance_type_property+'';
			              				html+= '</div>';
			              			html+= '</div>';
			              			html+= '</div>';
			              html+= '</div>';
			        }
		            
		            html+= '</div>';
		            html+= '</section>';
		            $parent.find('#bank_insurance_additional_info').css('display','block');
		            $parent.find('#bank_insurance_additional_info').empty().html(html);    
		            
		            $('#summary_div').show();
	      			$('#files_div').show();
	      			$('#bank_insurance_additional_info').show();
	      			

	      			//$('#summary_div').show();
	      			//$('#files_div').show();
	           } // end additional condition 
			}// end insurance loop
			}
			
	         // end for editional info

	        }
	        
	    } // end of bank product 
	    
	    
	    
	    // insurance part
	    
	    for (var k = 0; k < result.allinsurance.length; k++) {
	    	
	        var obj_ero = result.allinsurance[k];
	        console.log(obj_ero);
	        var obj_app_product = '';
	        var producthtm = '';
	        var pmathodhtm = '';
	        var appststus = '';
	        var applicenthtm = '';
	        var notehtm = '';
	        var html = '';
	        var obj_addition_info = '';
	       // console.log(obj_ero.applicent_id);
	       // console.log(uid);
	        
	        if (obj_ero.applicent_id == uid) {
	        	//alert(uid);
	        	//alert(obj_ero.app_id);
	            $parent.find("#first_name_lab").html(obj_ero.first_name);
	            $parent.find("#first_name").val(obj_ero.first_name);
	            $parent.find("#last_name_lab").html(obj_ero.last_name);
	            $parent.find("#last_name").val(obj_ero.last_name);
	            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
	            $parent.find("#ss_number").val(obj_ero.ss_number);
	            $parent.find("#dob_lab").html(obj_ero.dob);
	            $parent.find("#dob").val(obj_ero.dob);            
	            
	            if(obj_ero.sp_first_name == ''){
	            //	$("#dependent-box").css({'display','none'});
	            	$parent.find("#dependent-box").css('display','none');
	        	}
	            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
	            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
	           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
	           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
	    	
	            
	            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
	            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
	            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
	            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
	            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
	            $parent.find("#sp_dob").val(obj_ero.sp_dob);
	            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
	            $parent.find("#street_address").val(obj_ero.street_address_1);
	            $parent.find("#city_lab").html(obj_ero.city);
	            $parent.find("#city").val(obj_ero.city);
	           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
	            $parent.find("#state_lab").html(obj_ero.state);
	           
	            $parent.find("#state").val(obj_ero.state);
	            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
	            $parent.find("#zip_code").val(obj_ero.zip_code);
	            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
	            $parent.find("#cell_phone").val(obj_ero.cell_phone);
	            $parent.find("#email_add_lab").html(obj_ero.email_add);
	            $parent.find("#email_add").val(obj_ero.email_add);
	            //$parent.find("#application_id").val(uid);
	            $parent.find("#insurance_app_id").val(uid);
	            $parent.find("#insurance_applicent_id").val(obj_ero.applicent_id);
	            
	            
	            
	            var appststus='';
	            // selected Insurance Prodcuct
	           
	            
	            
	            if(obj_ero.insurance_item != '' && obj_ero.insurance_item !== null){
	            	
	            if(obj_ero.insurance_status == '0')
	           	 	appststus = '<span style="font-style: italic; color:#c09853;">Pending</span>';
	             else if(obj_ero.insurance_status == '1')
	            	 appststus = '<span style="font-style: italic; color:#468847;">Active</span>';
	             else if(obj_ero.insurance_status == '2')
	            	 appststus = '<span style="font-style: italic; color:#c09853;">Canceled</span>';
	            
	             
	            	producthtm += '<div class="col-md-3 service_img">';
	            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
	            			producthtm += '<i class="'+obj_ero.insurance_img_source+'"></i>';
	            		producthtm += '</div>';
	            	producthtm += '</div>';
				
	            	producthtm += '<div class="col-md-7 service_title_2">';
	            	producthtm += '<h4>'+obj_ero.insurance_item+'</h4>';
	            	producthtm += ''+appststus+'';
	            	producthtm += '</div>';
	            	
	            	producthtm += '<div class="col-md-2 price_2 text-left" style="padding: 0px; margin-top: 25px;">';
	            	producthtm += '	<button class=" btn btn-white-red" onclick="editInsuranceStatus('+uid+','+obj_ero.insurance_status+');" type="button" id="" style="" value="Edit"><i class="icon-pencil6"></i> Edit</button>';
	            	producthtm += '</div>';
					
					
	            	producthtm += '<div style="clear: both;"></div>';
					
	            }
	         
	            $parent.find('#selected_insurance').html(producthtm);
	            
	            
	            // applicent 
			            applicenthtm += ' <div>';
			            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_ero.first_name+' '+obj_ero.last_name+'</strong><span style="color:#83d19f;"> &nbsp; &nbsp;(Primary)</span> 	<br>XXX-XX-'+obj_ero.ss_number.substring(7,11);+' </div>';
			            applicenthtm += '	<div style="clear:both;"></div><hr>';
			            applicenthtm += '</div>';
	            if(obj_ero.applicents.length > 0){
		            for (var l = 0; l < obj_ero.applicents.length; l++) {
		           	 obj_app_app = obj_ero.applicents[l];
		            
		           	 	applicenthtm += ' <div>';
			            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_app.first_name+' '+obj_app_app.last_name+'</strong><br>XXX-XX-'+obj_app_app.ss_number.substring(7,11);+'</div>';
			            applicenthtm += '	<div style="clear:both;"></div><hr>';
			            applicenthtm += '</div>';
		            }
	            }
	            $parent.find('#selected_appl_sum').empty().html(applicenthtm);
	            
	            
	            // additional info
	            
	           // if(obj_ero.products.length > 0){
	            	var c = 0;
	            	//for(var k = 0 ; k < obj_ero.products.length ; k++){           		
	            		//obj_products_info = obj_ero.products[k];
	            		//console.log(obj_products_info.prodcut_name);
	            	//console.log(obj_ero.i_additional.length);
	            		for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
	            			obj_addition_info = obj_ero.i_additional[i];
	            		//	console.log(obj_addition_info);
	            			if(obj_ero.insurance_item  == 'Family Individual'){
		            	
							
							
							//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
							html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									//	html+= '<input type="text"  class="form-control ui-wizard-content" disabled="disabled" value="'+obj_addition_info.first_name+'" name="first_name" id="first_name">';
									html+= ''+obj_addition_info.first_name+'';
									html+= '</div>';
								html+= '</div>';
					                
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										//html+= '<input type="text" class="form-control ui-wizard-content" disabled="disabled" value="'+obj_addition_info.last_name+'" name="last_name" id="last_name">';
									html+= ''+obj_addition_info.last_name+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
					            
					           
							html+= '<div class="form-group">';
								html+= '<div class="col-md-4 form-group1">';
									html+= '<label for="street_address" class="control-label col-md-12">Coverage Start Date: </label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										//html+= '<input type="text" class="form-control ui-wizard-content input-datepicker family_coverage_date" data-date-format="yyyy-mm-dd" name="family_coverage_date[]" id="family_coverage_date" value="">';
										html+= ''+obj_addition_info.family_coverage_date+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-4 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj_addition_info.family_gender+'';
									//html+= '<select name="family_gender[]" id="family_gender" class="form-control family_gender ui-wizard-content">';
										//html+= '<option></option>';
										//html+= '<option value="Male">Male</option>';
										//html+= '<option value="Female">Female</option>';
									//html+= '</select>';
								html+= '</div>';
							html+= '</div>';
					                
								html+= '<div class="col-md-4 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.family_tobacco_use+'';	
										//html+= '<select name="family_tobacco_use[]" id="family_tobacco_use" class="form-control ui-wizard-content family_tobacco_use">';
					                    	//	html+= '<option></option>';
					                    	//	html+= '<option value="Yes">Yes</option>';
					                    	//	html+= '<option value= "No">No</option>';
					                    	//html+= '</select>';
					                    html+= '</div>';
					             html+= '</div>';
					      html+= '</div>';
					      html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
						//	}
						
		            }
		            if(obj_ero.insurance_item == 'Group Health'){
		            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
							//obj_addition_info = obj_ero.i_additional[i];
							
		            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
							html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Company name:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.company_name_grouphealth+'';
										html+= '</div>';
								html+= '</div>';
			                
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Industry (Type of Business):</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.industry_grouphealth+'';
										html+= '</div>';
								html+= '</div>';
							html+= '</div>';
			            
							html+= '<div class="form-group">';
								html+= '<div class="col-md-7 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Company Address:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.company_address_grouphealth+'';
										html+= '</div>';
								html+= '</div>';
			                
								html+= '<div class="col-md-2 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">State:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.state_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Zip:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.zip_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
			            
							html+= '<div class="form-group">';
								html+= '<div class="col-md-8 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Requested Lines of Coverage:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.requested_line_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-4 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Current Carrier:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.current_carrier_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
			            
			            
							html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="street_address" class="control-label col-md-12">Current Renewal Date:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.renewal_date_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Requested Effective Date:</label>';
										html+= '<div class="input-group1 nopadding col-md-12">';
											html+= ''+obj_addition_info.effective_date_grouphealth+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
							html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
		            	//}
		            	//}
		            }
		            
		            if(obj_ero.insurance_item == 'Life Insurance & Annuities'){
		            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
							//obj_addition_info = obj_ero.i_additional[i];
							
		            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
							html+= '<div class="form-group">';
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">First Name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.last_name+'';
									html+= '</div>';
								html+= '</div>';
			                
								html+= '<div class="col-md-6 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.first_name+'';
									html+= '</div>';
								html+= '</div>';
							html+= '</div>';
			            
			           
							html+= '<div class="form-group">';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="first_name" class="control-label col-md-12">Gender:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.gender_life+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="street_address" class="control-label col-md-12">Height:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.height_life+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="street_address" class="control-label col-md-12">Width:</label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.width_life+'';
									html+= '</div>';
								html+= '</div>';
								html+= '<div class="col-md-3 form-group1">';
									html+= '<label for="last_name" class="control-label col-md-12">Tobacco Use: </label>';
									html+= '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.tobacco_use_life+'';
									html+= '</div>';
								html+= '</div>';
						html+= '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
		            	//}
		            	//}
		            }
		            if(obj_ero.insurance_item == 'Auto Insurance'){
		            	//console.log(obj_ero.i_additional.length);
		            	
		            	//for(var w = 0 ; w < obj_ero.i_additional.length ; w++){
							//obj_addition_info = obj_ero.i_additional[w];
							
							//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
								if(c < 1){
									//console.log('c='+c);
									c=1;
							/*html += '<div class="form-group">';
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_ero.i_additional[i].first_name+'';
								html += '</div>';
							html += '</div>';
			                
							html += '<div class="col-md-6 form-group1">';
								html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_ero.i_additional[i].last_name+'';
								html += '</div>';
							html += '</div>';
						html += '</div>';
			            */
						html += '<div class="form-group">';
							html += '<div class="col-md-4 form-group1">';
								html += '<label for="street_address" class="control-label col-md-12">Marital Status:  </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_ero.i_additional[i].marital_status_auto+'';
								html += '</div>';
							html += '</div>';
							html += '<div class="col-md-4 form-group1">';
								html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_ero.i_additional[i].gender_auto+'';
								html += '</div>';
							html += '</div>';
						 html += '</div>';
			            
						html += '<div class="form-group">';
						html += '<div class="col-md-3 form-group1">';
							html += '<label for="street_address" class="control-label col-md-12">Year:  </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_ero.i_additional[i].year_auto+'';
								html += '</div>';
						html += '</div>';
						html += '<div class="col-md-3 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Make: </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_ero.i_additional[i].make_auto+'';
								html += '</div>';	
						html += '</div>';
						html += '<div class="col-md-3 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Model: </label>';
							html += '<div class="input-group1 nopadding col-md-12">';
							html+= ''+obj_ero.i_additional[i].model_auto+'';
							html += '</div>';	
						html += '</div>';
						html += '<div class="col-md-3 form-group1">';
							html += '<label for="first_name" class="control-label col-md-12">Coverage: </label>';
								html += '<div class="input-group1 nopadding col-md-12">';
								html+= ''+obj_ero.i_additional[i].coverage_auto+'';
								html += '</div>';	
						html += '</div>';
			                
						html += '</div>';
						html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
						}
								
							//	if(obj_products_info.app_pro_id == obj_addition_info.product_id){
									
									//for(var j = 1 ; j < obj_ero.i_additional.length ; j++){
										
						if(i < parseInt(obj_ero.i_additional.length - 1)){
						var l = parseInt(i+1);
						//console.log(l);
										var obj3 = obj_ero.i_additional[l];
										
								
								html += '<div class="form-group">';
								html += '<div class="col-md-6 form-group1">';
									html += '<label for="first_name" class="control-label col-md-12">First Name:</label>';
									html += '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj3.first_name+'';
									html += '</div>';
								html += '</div>';
				                
								html += '<div class="col-md-6 form-group1">';
									html += '<label for="last_name" class="control-label col-md-12">Last Name:</label>';
									html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.last_name+'';
									html += '</div>';
								html += '</div>';
							html += '</div>';
							
							
							html += '<div class="form-group">';
								html += '<div class="col-md-6 form-group1">';
									html += '<label for="first_name" class="control-label col-md-12">Relationship to Applicant: </label>';
									html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.relation_auto+'';
									html += '</div>';	
								html += '</div>';
								html += '<div class="col-md-3 form-group1">';
									html += '<label for="street_address" class="control-label col-md-12" style="padding-right: 0px;">Marital Status:  </label>';
									html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.marital_status_auto+'';
										html += '</div>';
								html += '</div>';
								html += '<div class="col-md-3 form-group1">';
									html += '<label for="first_name" class="control-label col-md-12">Gender:</label>';
									html += '<div class="input-group1 nopadding col-md-12">';
									html+= ''+obj3.gender_auto+'';
									html += '</div>';
								html += '</div>';
							html += '</div>';
							html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
						}
							//}
						//	}
							
							//}
		            	//}
		            }
		            if(obj_ero.insurance_item == 'Home Insurance'){
		            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
							//obj_addition_info = obj_ero.i_additional[i];
		            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
							html += '<div class="form-group">';
								html += '<div class="col-md-6 form-group1">';
									html += '<label for="first_name" class="control-label col-md-12">Coverage Start Date:</label>';
									html += '<div class="input-group1 nopadding col-md-12">';
										html+= ''+obj_addition_info.coverage_date_home+'';
									html += '</div>';
								html += '</div>';
							html += '</div>';
							html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
		            	//}
		            	//}
		            }
		            if(obj_ero.insurance_item == 'Property & Casualty'){
		            	//for(var i = 0 ; i < obj_ero.i_additional.length ; i++){
							//obj_addition_info = obj_ero.i_additional[i];
		            	//if(obj_products_info.app_pro_id == obj_addition_info.product_id){
							html+= '<div class="form-group">';
							html+= '<div class="col-md-12 form-group1">';
							html+= '<label for="first_name" class="control-label col-md-12">Provide a realistic estimate of your gross revenue for the current financial year:</label>';
							html+= '<div class="input-group1 nopadding col-md-7">';
			                    html+= ''+obj_addition_info.revenue_property+'';
			                    html+= '</div>';
			               html+= '</div>';
			              html+= '</div>';
			            	  html+= '<div class="" style="clear:both"></div>';
			            		  html+= '<div class="form-group">  ';
			            			  html+= '<div class="col-md-12 form-group1">';
			            				  html+= '<label for="last_name" class="control-label col-md-12">Have any claims been filed against your business during the past 3 years:</label>';
			            				html+= '<div class="input-group1 nopadding col-md-3">';
			            					html+= ''+obj_addition_info.past_claims_property+'';
			              				html+= '</div>';
			              		html+= '</div>';
			              html+= '</div>';
			            html+= '<div class="" style="clear:both"></div>'; 
			            html+= '<div class="form-group">';
			            html+= '<div class="col-md-12 form-group1">';
			            	html+= '<label for="first_name" class="control-label col-md-12">Please indicate which types of insurance you would like to receive a quote for: <br> <small>Type of insurance needed (check all that apply)</small></label>';
			            		html+= '<div class="input-group1 nopadding col-md-12">';
			            			html+= '<div class="input-group1 nopadding col-md-12">';
				                    html+= ''+obj_addition_info.insurance_type_property+'';
			              				html+= '</div>';
			              			html+= '</div>';
			              			html+= '</div>';
			              html+= '</div>';
			              html+= '<hr style="margin: 8px 5px; display: block;" class="dottline info_hr">';
		            	//}
		            	//}
		            }
		              
	            		} 
	            	//} prodcut loap
	            	$parent.find('#insurance_additional_info').empty().html(html);
	           // } // cehck prodcut exist or not
	            
	             
	            
	            
	            
	            if(obj_ero.notes.length > 0){
		            for (var m = 0; m < obj_ero.notes.length; m++) {
		           	 obj_app_notes = obj_ero.notes[m];
		            
		           	 	notehtm += ' <div>';
		           	 	notehtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_notes.firstname+' '+obj_app_notes.lastname+'</strong> - '+obj_app_notes.note_create_date+'<br>'+obj_app_notes.note_text+'</div>';
		           	 	notehtm += '	<div style="clear:both;"></div><hr>';
		           	 	notehtm += '</div>';
		            }
	            }
	            
	            $parent.find('#in_note_div').css('display','block');
	            $parent.find('#selected_applicent_div').show();
	            $parent.find('#additional_info_div').show();
	            $parent.find('#insurance_item_div').show();
	            
      	        
	            $('#insurance_note_div').empty().html(notehtm);
	            
	        }
	    }
	   
      			
	    // end of insurance part
      			
      			
      			// benefits part
	    for (var k = 0; k < result.allbenefits.length; k++) {
	        var obj_ero = result.allbenefits[k];
	        var obj_app_product = '';
	        var producthtm = '';
	        var pmathodhtm = '';
	        var appststus = '';
	        var applicenthtm = '';
	        var notehtm = '';	
	        
      			if (obj_ero.applicent_id == uid) {
      	        	//alert(uid);
      	        	//alert(obj_ero.app_id);
      	            $parent.find("#first_name_lab").html(obj_ero.first_name);
      	            $parent.find("#first_name").val(obj_ero.first_name);
      	            $parent.find("#last_name_lab").html(obj_ero.last_name);
      	            $parent.find("#last_name").val(obj_ero.last_name);
      	            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
      	            $parent.find("#ss_number").val(obj_ero.ss_number);
      	            $parent.find("#dob_lab").html(obj_ero.dob);
      	            $parent.find("#dob").val(obj_ero.dob);            
      	            
      	            if(obj_ero.sp_first_name == ''){
      	            //	$("#dependent-box").css({'display','none'});
      	            	$parent.find("#dependent-box").css('display','none');
      	        	}
      	            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
      	            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
      	           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
      	           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
      	    	
      	            
      	            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
      	            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
      	            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
      	            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
      	            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
      	            $parent.find("#sp_dob").val(obj_ero.sp_dob);
      	            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
      	            $parent.find("#street_address").val(obj_ero.street_address_1);
      	            $parent.find("#city_lab").html(obj_ero.city);
      	            $parent.find("#city").val(obj_ero.city);
      	           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
      	            $parent.find("#state_lab").html(obj_ero.state);
      	           
      	            $parent.find("#state").val(obj_ero.state);
      	            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
      	            $parent.find("#zip_code").val(obj_ero.zip_code);
      	            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
      	            $parent.find("#cell_phone").val(obj_ero.cell_phone);
      	            $parent.find("#email_add_lab").html(obj_ero.email_add);
      	            $parent.find("#email_add").val(obj_ero.email_add);
      	            $parent.find("#benefits_app_id").val(uid);
      	            $parent.find("#benefits_applicent_id").val(obj_ero.applicent_id);
      	            
      	           
      	            
      	            var appststus = '';
      	            // selected Insurance Prodcuct
      	            if(obj_ero.benefits_item != '' && obj_ero.benefits_item !== null){	
      	            	
      	            	
      	                if(obj_ero.benefits_status == '0')
      	               	 appststus = '<span style="font-style: italic; color:#c09853;">Pending</span>';
      	                else if(obj_ero.benefits_status == '1')
      	               	 appststus = '<span style="font-style: italic; color:#468847;">Active</span>';
      	                else if(obj_ero.benefits_status == '2')
      	               	 appststus = '<span style="font-style: italic; color:#c09853;">Canceled</span>';
      	                
      	            	producthtm += '<div class="col-md-3 service_img">';
      	            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
      	            			producthtm += '<i class="'+obj_ero.benefits_img_source+'"></i>';
      	            		producthtm += '</div>';
      	            	producthtm += '</div>';
      				
      	            	producthtm += '<div class="col-md-7 service_title_2">';
      	            	producthtm += '<h4>'+obj_ero.benefits_item+'</h4>';
      	            	producthtm += ''+appststus+'';
      	            	
      	            	producthtm += '</div>';
      	            	
      	            	producthtm += '<div class="col-md-2 price_2 text-right">';
      	            	producthtm += '	<h5>$'+obj_ero.benefits_price+'</h5> <button class=" btn btn-white-red" onclick="editBenefitsStatus('+uid+','+obj_ero.benefits_status+');" type="button" id="edit_cust_info_btn" style="margin-left: -16px" value="Edit"><i class="icon-pencil6"></i> Edit</button>';
      	            	producthtm += '</div>';
      					
      	            	producthtm += '<div style="clear: both;"></div>';
      					
      	            }
      	         
      	            $('#selected_benefits').html(producthtm);
      	            
      			            applicenthtm += ' <div>';
      			            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_ero.first_name+' '+obj_ero.last_name+'</strong> <span style="color:#83d19f;"> &nbsp; &nbsp;(Primary)</span><br>XXX-XX-'+obj_ero.ss_number.substring(7,11);+'</div>';
      			            applicenthtm += '	<div style="clear:both;"></div><hr>';
      			            applicenthtm += '</div>';
      	            if(obj_ero.applicents.length > 0){
      		            for (var l = 0; l < obj_ero.applicents.length; l++) {
      		           	 obj_app_app = obj_ero.applicents[l];
      		            
      		           	 	applicenthtm += ' <div>';
      			            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_app.first_name+' '+obj_app_app.last_name+'</strong><br>XXX-XX-'+obj_app_app.ss_number.substring(7,11);+'</div>';
      			            applicenthtm += '	<div style="clear:both;"></div><hr>';
      			            applicenthtm += '</div>';
      		            }
      	            }
      	            
      	            $('#selected_appl_sum_benefits').html(applicenthtm);
      	           // console.log(applicenthtm);
      	        
      	            if(obj_ero.notes.length > 0){
      		            for (var m = 0; m < obj_ero.notes.length; m++) {
      		           	 obj_app_notes = obj_ero.notes[m];
      		            
      		           	 	notehtm += ' <div>';
      		           	 	notehtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_notes.firstname+' '+obj_app_notes.lastname+'</strong> - '+obj_app_notes.note_create_date+'<br>'+obj_app_notes.note_text+'</div>';
      		           	 	notehtm += '	<div style="clear:both;"></div><hr>';
      		           	 	notehtm += '</div>';
      		            }
      	            }
      	            
	      	        $('#benefits_note').show();
	      	        $('#benefits_seleted_applicent').show(); 
	      	        $('#benefits_item_div').show();
	      	          
      	            $('#benefits_note_div').empty().html(notehtm);
      	            	
      	            
      	        }
	    }	
      			// end of benefits part
	    
	    
	    // summary_div bank_insurance_additional_info files_div
		// in_note_div selected_applicent_div additional_info_div insurance_item_div 
        // benefits_note   benefits_seleted_applicent benefits_item_div
	    
	     $parent.find("#first_name_lab").css('display','block');
		 $parent.find("#first_name_div").css('display','none');
		 $parent.find("#last_name_lab").css('display','block');
		 $parent.find("#last_name_div").css('display','none');
		 $parent.find("#ss_number_lab").css('display','block');
		 $parent.find("#ss_number_div").css('display','none');
		 $parent.find("#dob_lab").css('display','block');
		 $parent.find("#dob_div").css('display','none');
		 $parent.find("#sp_first_name_lab").css('display','block');
		 $parent.find("#sp_first_name").css('display','none');
		 $parent.find("#sp_last_name_lab").css('display','block');
		 $parent.find("#sp_last_name").css('display','none');
		 $parent.find("#sp_ss_number_lab").css('display','block');
		 $parent.find("#sp_ss_number").css('display','none');
		 $parent.find("#sp_dob_lab").css('display','block');
		 $parent.find("#sp_dob").css('display','none');
		 $parent.find("#street_address_lab").css('display','block');
		 $parent.find("#street_address_div").css('display','none');
		 $parent.find("#city_lab").css('display','block');
		 $parent.find("#city_div").css('display','none');
		 $parent.find("#state_lab").css('display','block');
		 $parent.find("#state_div").css('display','none');
		 $parent.find("#zip_code_lab").css('display','block');
		 $parent.find("#zip_code_div").css('display','none');
		 $parent.find("#cell_phone_lab").css('display','block');
		 $parent.find("#cell_phone_div").css('display','none');
		 $parent.find("#email_add_lab").css('display','block');
		 $parent.find("#email_add_div").css('display','none');
		 $(".info_hr").css('display','block');
		 
		 $parent.find("#saveData").css('display','none');
		 
		 $parent.closest("#modal_editApplication").modal('show');
   
         } // end of check return data
      	
      }, "json");

      		//return false;
	    
	    	
}


function editEnableInsurance(parent) {
	var $parent = $("#" + parent);
	//alert(parent);
	// var $parent = parent; //$("#body_edit_application_pending");
		//$parent.closest("#modal_editApplication_pending_fund").modal('hide');
		
	 $parent.find("#first_name_lab").css('display','none');
	 $parent.find("#first_name_div").css('display','block');
	 $parent.find("#last_name_lab").css('display','none');
	 $parent.find("#last_name_div").css('display','block');
	 $parent.find("#ss_number_lab").css('display','none');
	 $parent.find("#ss_number_div").css('display','block');
	 $parent.find("#dob_lab").css('display','none');
	 $parent.find("#dob_div").css('display','block');
	 $parent.find("#sp_first_name_lab").css('display','none');
	 $parent.find("#sp_first_name").css('display','block');
	 $parent.find("#sp_last_name_lab").css('display','none');
	 $parent.find("#sp_last_name").css('display','block');
	 $parent.find("#sp_ss_number_lab").css('display','none');
	 $parent.find("#sp_ss_number").css('display','block');
	 $parent.find("#sp_dob_lab").css('display','none');
	 $parent.find("#sp_dob").css('display','block');
	 $parent.find("#street_address_lab").css('display','none');
	 $parent.find("#street_address_div").css('display','block');
	 $parent.find("#city_lab").css('display','none');
	 $parent.find("#city_div").css('display','block');
	 $parent.find("#state_lab").css('display','none');
	 $parent.find("#state_div").css('display','block');
	 $parent.find("#zip_code_lab").css('display','none');
	 $parent.find("#zip_code_div").css('display','block');
	 $parent.find("#cell_phone_lab").css('display','none');
	 $parent.find("#cell_phone_div").css('display','block');
	 $parent.find("#email_add_lab").css('display','none');
	 $parent.find("#email_add_div").css('display','block');
	 $parent.find(".info_hr").css('display','none');
	 
	 $parent.find("#saveData").css('display','block');
	 
	
	 
}

$("#cancel_edit_insurance").click(function() {
	$("#modal_edit_insuracne_product").modal('hide');	
});


$("#cancel_edit_customer").click(function() {
	$("#modal_edit_customer").modal('hide');	
});

function editEnable(parent) {
	var $parent = $("#" + parent);
	//alert(parent);
	// var $parent = parent; //$("#body_edit_application_pending");
		//$parent.closest("#modal_editApplication_pending_fund").modal('hide');
		
	 $parent.find("#first_name_lab").css('display','none');
	 $parent.find("#first_name_div").css('display','block');
	 $parent.find("#last_name_lab").css('display','none');
	 $parent.find("#last_name_div").css('display','block');
	 $parent.find("#ss_number_lab").css('display','none');
	 $parent.find("#ss_number_div").css('display','block');
	 $parent.find("#dob_lab").css('display','none');
	 $parent.find("#dob_div").css('display','block');
	 $parent.find("#sp_first_name_lab").css('display','none');
	 $parent.find("#sp_first_name").css('display','block');
	 $parent.find("#sp_last_name_lab").css('display','none');
	 $parent.find("#sp_last_name").css('display','block');
	 $parent.find("#sp_ss_number_lab").css('display','none');
	 $parent.find("#sp_ss_number").css('display','block');
	 $parent.find("#sp_dob_lab").css('display','none');
	 $parent.find("#sp_dob").css('display','block');
	 $parent.find("#street_address_lab").css('display','none');
	 $parent.find("#street_address_div").css('display','block');
	 $parent.find("#city_lab").css('display','none');
	 $parent.find("#city_div").css('display','block');
	 $parent.find("#state_lab").css('display','none');
	 $parent.find("#state_div").css('display','block');
	 $parent.find("#zip_code_lab").css('display','none');
	 $parent.find("#zip_code_div").css('display','block');
	 $parent.find("#cell_phone_lab").css('display','none');
	 $parent.find("#cell_phone_div").css('display','block');
	 $parent.find("#email_add_lab").css('display','none');
	 $parent.find("#email_add_div").css('display','block');
	 $parent.find(".info_hr").css('display','none');
	 
	 $parent.find("#saveData").css('display','block');
	 
	
	 
}


function editBenefits(uid, parent) {
    var $parent = $("#" + parent);
    // alert(dataClient.length);
    for (var k = 0; k < dataClient.length; k++) {
        var obj_ero = dataClient[k];
        var obj_app_product = '';
        var producthtm = '';
        var pmathodhtm = '';
        var appststus = '';
        var applicenthtm = '';
        var notehtm = '';
        
        if (obj_ero.benefits_id == uid) {
        	//alert(uid);
        	//alert(obj_ero.app_id);
            $parent.find("#first_name_lab").html(obj_ero.first_name);
            $parent.find("#first_name").val(obj_ero.first_name);
            $parent.find("#last_name_lab").html(obj_ero.last_name);
            $parent.find("#last_name").val(obj_ero.last_name);
            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
            $parent.find("#ss_number").val(obj_ero.ss_number);
            $parent.find("#dob_lab").html(obj_ero.dob);
            $parent.find("#dob").val(obj_ero.dob);            
            
            if(obj_ero.sp_first_name == ''){
            //	$("#dependent-box").css({'display','none'});
            	$parent.find("#dependent-box").css('display','none');
        	}
            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
    	
            
            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
            $parent.find("#sp_dob").val(obj_ero.sp_dob);
            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
            $parent.find("#street_address").val(obj_ero.street_address_1);
            $parent.find("#city_lab").html(obj_ero.city);
            $parent.find("#city").val(obj_ero.city);
           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
            $parent.find("#state_lab").html(obj_ero.state);
           
            $parent.find("#state").val(obj_ero.state);
            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
            $parent.find("#zip_code").val(obj_ero.zip_code);
            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
            $parent.find("#cell_phone").val(obj_ero.cell_phone);
            $parent.find("#email_add_lab").html(obj_ero.email_add);
            $parent.find("#email_add").val(obj_ero.email_add);
            $parent.find("#benefits_app_id").val(uid);
            $parent.find("#benefits_applicent_id").val(obj_ero.applicent_id);
            
           
            
            var appststus = '';
            // selected Insurance Prodcuct
            if(obj_ero.benefits_item != '' && obj_ero.benefits_item !== null){	
            	
            	
                if(obj_ero.benefits_status == '0')
               	 appststus = '<span style="font-style: italic; color:#c09853;">Pending</span>';
                else if(obj_ero.benefits_status == '1')
               	 appststus = '<span style="font-style: italic; color:#468847;">Active</span>';
                else if(obj_ero.benefits_status == '2')
               	 appststus = '<span style="font-style: italic; color:#c09853;">Canceled</span>';
                
            	producthtm += '<div class="col-md-3 service_img">';
            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
            			producthtm += '<i class="'+obj_ero.benefits_img_source+'"></i>';
            		producthtm += '</div>';
            	producthtm += '</div>';
			
            	producthtm += '<div class="col-md-7 service_title_2">';
            	producthtm += '<h4>'+obj_ero.benefits_item+'</h4>';
            	producthtm += ''+appststus+'';
            	
            	producthtm += '</div>';
            	
            	producthtm += '<div class="col-md-2 price_2 text-right">';
            	producthtm += '	<h5>$'+obj_ero.benefits_price+'</h5> <button class=" btn btn-white-red" onclick="editBenefitsStatus('+uid+','+obj_ero.benefits_status+');" type="button" id="edit_cust_info_btn" style="margin-left: -16px" value="Edit"><i class="icon-pencil6"></i> Edit</button>';
            	producthtm += '</div>';
				
            	producthtm += '<div style="clear: both;"></div>';
				
            }
         
            $('#selected_benefits').html(producthtm);
            
		            applicenthtm += ' <div>';
		            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_ero.first_name+' '+obj_ero.last_name+'</strong> <span style="color:#83d19f;"> &nbsp; &nbsp;(Primary)</span><br>XXX-XX-'+obj_ero.ss_number.substring(7,11);+'</div>';
		            applicenthtm += '	<div style="clear:both;"></div><hr>';
		            applicenthtm += '</div>';
            if(obj_ero.applicents.length > 0){
	            for (var l = 0; l < obj_ero.applicents.length; l++) {
	           	 obj_app_app = obj_ero.applicents[l];
	            
	           	 	applicenthtm += ' <div>';
		            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_app.first_name+' '+obj_app_app.last_name+'</strong><br>XXX-XX-'+obj_app_app.ss_number.substring(7,11);+'</div>';
		            applicenthtm += '	<div style="clear:both;"></div><hr>';
		            applicenthtm += '</div>';
	            }
            }
            
            $('#selected_appl_sum_benefits').html(applicenthtm);
           // console.log(applicenthtm);
        
            if(obj_ero.notes.length > 0){
	            for (var m = 0; m < obj_ero.notes.length; m++) {
	           	 obj_app_notes = obj_ero.notes[m];
	            
	           	 	notehtm += ' <div>';
	           	 	notehtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_notes.firstname+' '+obj_app_notes.lastname+'</strong> - '+obj_app_notes.note_create_date+'<br>'+obj_app_notes.note_text+'</div>';
	           	 	notehtm += '	<div style="clear:both;"></div><hr>';
	           	 	notehtm += '</div>';
	            }
            }
            
            $('#benefits_note_div').empty().html(notehtm);

        }
    }
    
    	 $parent.find("#first_name_lab").css('display','block');
		 $parent.find("#first_name_div").css('display','none');
		 $parent.find("#last_name_lab").css('display','block');
		 $parent.find("#last_name_div").css('display','none');
		 $parent.find("#ss_number_lab").css('display','block');
		 $parent.find("#ss_number_div").css('display','none');
		 $parent.find("#dob_lab").css('display','block');
		 $parent.find("#dob_div").css('display','none');
		 $parent.find("#sp_first_name_lab").css('display','block');
		 $parent.find("#sp_first_name").css('display','none');
		 $parent.find("#sp_last_name_lab").css('display','block');
		 $parent.find("#sp_last_name").css('display','none');
		 $parent.find("#sp_ss_number_lab").css('display','block');
		 $parent.find("#sp_ss_number").css('display','none');
		 $parent.find("#sp_dob_lab").css('display','block');
		 $parent.find("#sp_dob").css('display','none');
		 $parent.find("#street_address_lab").css('display','block');
		 $parent.find("#street_address_div").css('display','none');
		 $parent.find("#city_lab").css('display','block');
		 $parent.find("#city_div").css('display','none');
		 $parent.find("#state_lab").css('display','block');
		 $parent.find("#state_div").css('display','none');
		 $parent.find("#zip_code_lab").css('display','block');
		 $parent.find("#zip_code_div").css('display','none');
		 $parent.find("#cell_phone_lab").css('display','block');
		 $parent.find("#cell_phone_div").css('display','none');
		 $parent.find("#email_add_lab").css('display','block');
		 $parent.find("#email_add_div").css('display','none');
		 $(".info_hr").css('display','block');
		 
		 
		 $parent.find("#saveData").css('display','none');
		
    $parent.closest("#modal_edit_benefits_product").modal('show');
}

function editBenefitsFromReport(uid, parent ) {
    var $parent = $("#" + parent);
   // alert(dataClientDR.length);
    for (var k = 0; k < dataClientDR.length; k++) {
        var obj_ero = dataClientDR[k];
        var obj_app_product = '';
        var producthtm = '';
        var pmathodhtm = '';
        var appststus = '';
        var applicenthtm = '';
        var notehtm = '';
        
        if (obj_ero.applicent_id == uid) {
        	//alert(uid);
        	//alert(obj_ero.app_id);
            $parent.find("#first_name_lab").html(obj_ero.first_name);
            $parent.find("#first_name").val(obj_ero.first_name);
            $parent.find("#last_name_lab").html(obj_ero.last_name);
            $parent.find("#last_name").val(obj_ero.last_name);
            $parent.find("#ss_number_lab").html(obj_ero.ss_number);
            $parent.find("#ss_number").val(obj_ero.ss_number);
            $parent.find("#dob_lab").html(obj_ero.dob);
            $parent.find("#dob").val(obj_ero.dob);            
            
            if(obj_ero.sp_first_name == ''){
            //	$("#dependent-box").css({'display','none'});
            	$parent.find("#dependent-box").css('display','none');
        	}
            $parent.find("#sp_first_name_lab").html(obj_ero.sp_first_name);
            $parent.find("#sp_first_name").val(obj_ero.sp_first_name);
           // (obj_ero.is_parent_efin === '1') ?  $parent.find("#pefin_check_box").attr("checked",true) : $parent.find("#pefin_check_box").attr("checked",false)  ;
           // (obj_ero.is_service_bureau === '1') ?  $parent.find("#pservice_bureau_check_box").attr("checked",true) : $parent.find("#pservice_bureau_check_box").attr("checked",false)  ;
    	
            
            $parent.find("#sp_last_name_lab").html(obj_ero.sp_last_name);
            $parent.find("#sp_last_name").val(obj_ero.sp_last_name);
            $parent.find("#sp_ss_number_lab").html(obj_ero.sp_ssn_no);
            $parent.find("#sp_ss_number").val(obj_ero.sp_ssn_no);
            $parent.find("#sp_dob_lab").html(obj_ero.sp_dob);
            $parent.find("#sp_dob").val(obj_ero.sp_dob);
            $parent.find("#street_address_lab").html(obj_ero.street_address_1);
            $parent.find("#street_address").val(obj_ero.street_address_1);
            $parent.find("#city_lab").html(obj_ero.city);
            $parent.find("#city").val(obj_ero.city);
           // (obj_ero.same_as === '1') ? $parent.find("#example-checkbox1").attr("checked", true) : $parent.find("#example-checkbox1").attr("checked", false);
            $parent.find("#state_lab").html(obj_ero.state);
           
            $parent.find("#state").val(obj_ero.state);
            $parent.find("#zip_code_lab").html(obj_ero.zip_code);
            $parent.find("#zip_code").val(obj_ero.zip_code);
            $parent.find("#cell_phone_lab").html(obj_ero.cell_phone);
            $parent.find("#cell_phone").val(obj_ero.cell_phone);
            $parent.find("#email_add_lab").html(obj_ero.email_add);
            $parent.find("#email_add").val(obj_ero.email_add);
            $parent.find("#benefits_app_id").val(uid);
            $parent.find("#benefits_applicent_id").val(obj_ero.applicent_id);
            

            // selected Insurance Prodcuct
            if(obj_ero.benefits_item != '' && obj_ero.benefits_item !== null){	
            	producthtm += '<div class="col-md-3 service_img">';
            		producthtm += '<div style="color:#525a5e;  padding-top:10px; margin-bottom: 5px" class="symbol gray">';
            			producthtm += '<i class="'+obj_ero.benefits_img_source+'"></i>';
            		producthtm += '</div>';
            	producthtm += '</div>';
			
            	producthtm += '<div class="col-md-7 service_title_2">';
            	producthtm += '<h4>'+obj_ero.benefits_item+'</h4>';
            	producthtm += '</div>';
            	
            	producthtm += '<div class="col-md-2 price_2 text-right">';
            	producthtm += '	<h5>$'+obj_ero.benefits_price+'</h5>';
            	producthtm += '</div>';
				
            	producthtm += '<div style="clear: both;"></div>';
				
            }
         
            $parent.find('#selected_benefits').html(producthtm);
            
            applicenthtm += ' <div>';
            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_ero.first_name+' '+obj_ero.last_name+'</strong> <span style="color:#83d19f;"> &nbsp; &nbsp;(Primary)</span><br>XXX-XX-'+obj_ero.ss_number.substring(7,11);+'</div>';
            applicenthtm += '	<div style="clear:both;"></div><hr>';
            applicenthtm += '</div>';
            if(obj_ero.applicents.length > 0){
	            for (var l = 0; l < obj_ero.applicents.length; l++) {
	           	 obj_app_app = obj_ero.applicents[l];
	            
	           	 	applicenthtm += ' <div>';
		            applicenthtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_app.first_name+' '+obj_app_app.last_name+'</strong><br>XXX-XX-'+obj_app_app.ss_number.substring(7,11);+'</div>';
		            applicenthtm += '	<div style="clear:both;"></div><hr>';
		            applicenthtm += '</div>';
	            }
            }
            
            $parent.find('#selected_appl_sum_benefits').html(applicenthtm);
        
            if(obj_ero.notes.length > 0){
	            for (var m = 0; m < obj_ero.notes.length; m++) {
	           	 obj_app_notes = obj_ero.notes[m];
	            
	           	 	notehtm += ' <div>';
	           	 	notehtm += '	<div style="padding-top: 5px" class="col-md-12">  <strong>'+obj_app_notes.firstname+' '+obj_app_notes.lastname+'</strong> - '+obj_app_notes.note_create_date+'<br>'+obj_app_notes.note_text+'</div>';
	           	 	notehtm += '	<div style="clear:both;"></div><hr>';
	           	 	notehtm += '</div>';
	            }
            }
            
            $('#benefits_note_div').empty().html(notehtm);

        }
    }
    
    	 $parent.find("#first_name_lab").css('display','block');
		 $parent.find("#first_name_div").css('display','none');
		 $parent.find("#last_name_lab").css('display','block');
		 $parent.find("#last_name_div").css('display','none');
		 $parent.find("#ss_number_lab").css('display','block');
		 $parent.find("#ss_number_div").css('display','none');
		 $parent.find("#dob_lab").css('display','block');
		 $parent.find("#dob_div").css('display','none');
		 $parent.find("#sp_first_name_lab").css('display','block');
		 $parent.find("#sp_first_name").css('display','none');
		 $parent.find("#sp_last_name_lab").css('display','block');
		 $parent.find("#sp_last_name").css('display','none');
		 $parent.find("#sp_ss_number_lab").css('display','block');
		 $parent.find("#sp_ss_number").css('display','none');
		 $parent.find("#sp_dob_lab").css('display','block');
		 $parent.find("#sp_dob").css('display','none');
		 $parent.find("#street_address_lab").css('display','block');
		 $parent.find("#street_address_div").css('display','none');
		 $parent.find("#city_lab").css('display','block');
		 $parent.find("#city_div").css('display','none');
		 $parent.find("#state_lab").css('display','block');
		 $parent.find("#state_div").css('display','none');
		 $parent.find("#zip_code_lab").css('display','block');
		 $parent.find("#zip_code_div").css('display','none');
		 $parent.find("#cell_phone_lab").css('display','block');
		 $parent.find("#cell_phone_div").css('display','none');
		 $parent.find("#email_add_lab").css('display','block');
		 $parent.find("#email_add_div").css('display','none');
		 $(".info_hr").css('display','block');
		 
		 
		 $parent.find("#saveData").css('display','none');
		
    $parent.closest("#modal_edit_benefits_product").modal('show');
}

function editEnableBenefits(parent) {
	var $parent = $("#" + parent);
	//alert(parent);
	// var $parent = parent; //$("#body_edit_application_pending");
		//$parent.closest("#modal_editApplication_pending_fund").modal('hide');
		
	 $parent.find("#first_name_lab").css('display','none');
	 $parent.find("#first_name_div").css('display','block');
	 $parent.find("#last_name_lab").css('display','none');
	 $parent.find("#last_name_div").css('display','block');
	 $parent.find("#ss_number_lab").css('display','none');
	 $parent.find("#ss_number_div").css('display','block');
	 $parent.find("#dob_lab").css('display','none');
	 $parent.find("#dob_div").css('display','block');
	 $parent.find("#sp_first_name_lab").css('display','none');
	 $parent.find("#sp_first_name").css('display','block');
	 $parent.find("#sp_last_name_lab").css('display','none');
	 $parent.find("#sp_last_name").css('display','block');
	 $parent.find("#sp_ss_number_lab").css('display','none');
	 $parent.find("#sp_ss_number").css('display','block');
	 $parent.find("#sp_dob_lab").css('display','none');
	 $parent.find("#sp_dob").css('display','block');
	 $parent.find("#street_address_lab").css('display','none');
	 $parent.find("#street_address_div").css('display','block');
	 $parent.find("#city_lab").css('display','none');
	 $parent.find("#city_div").css('display','block');
	 $parent.find("#state_lab").css('display','none');
	 $parent.find("#state_div").css('display','block');
	 $parent.find("#zip_code_lab").css('display','none');
	 $parent.find("#zip_code_div").css('display','block');
	 $parent.find("#cell_phone_lab").css('display','none');
	 $parent.find("#cell_phone_div").css('display','block');
	 $parent.find("#email_add_lab").css('display','none');
	 $parent.find("#email_add_div").css('display','block');
	 $parent.find(".info_hr").css('display','none');
	 
	 $parent.find("#saveData").css('display','block');
	 
	
	 
}

$("#cancel_edit_benefits").click(function() {
	$("#modal_edit_benefits_product").modal('hide');	
});


function cancelEditApplicationModal(parent) {
    var $parent = $("#" + parent);
     $parent.find("#cancel_app_edit").click(function(){
        $parent.closest("#modal_editApplication").modal('hide');
        return false;
   });
}

function showEditEroModal(modal_tab) {
    $('.' + modal_tab + ' a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

}

function cancelEditModal(parent) {
     var $parent = $("#" + parent);
      $parent.find("#cancel_allero").click(function(){
         $parent.closest("#modal_editEro").modal('hide');
         return false;
    });
}


function ClickToEditEro(option, parent) {
    var $parent = $("#" + parent);
    $parent.find("#save").click(function() {
        $.post(url_base_path__ + 'admin/ero/ClickToEditEro', {
            option: option,
            senddata: 'yes',
            p_efin :  $parent.find("#parent_efin").val(),
            obj_image : objGallery,
            company_name: $parent.find("#company_name").val(),
            efin: $parent.find("#efin").val(),
            uid: $parent.find("#uid_master").val(),
            address_1: $parent.find("#address_1").val(),
            address_2: $parent.find("#address_2").val(),
            com_phoneno : $parent.find("#com_phoneno").val(),
            zipcode: $parent.find("#zipcode").val(),
            city: $parent.find("#city").val(),
            state: $parent.find("#state").val(),
            same_as: $parent.find("#example-checkbox1").is(':checked') ? 1 : 0,
            address_1_m: $parent.find("#address_1_m").val(),
            address_2_m: $parent.find("#address_2_m").val(),
            zipcode_m: $parent.find("#zipcode_m").val(),
            city_m: $parent.find("#city_m").val(),
            state_m: $parent.find("#state_m").val(),
            //tax: $parent.find("#tax").val()
            tax: $parent.find("input:radio[name=tax]:checked").val(),
            //bank_name: $parent.find("#bank_name").val(),
            //bank_routing: $parent.find("#bank_routing").val(),
            //bank_account: $parent.find("#bank_account").val(),
            //addon: $parent.find("#addon").val(),
           // file: $parent.find("#file").val(),
            //ag: $parent.find("#ag").val(),
            //agprep: $parent.find("#agprep").val()
        }, function(data) {
            if (typeof (data) == 'object') {
                dataClient = data;
                $parent.closest("#modal_editEro").modal('hide');
            }
            loadClients();
        }, "json");

        return false;
    });
}


function clickSaveProfile(option, parent) {
    var $parent = $("#" + parent);
    //alert($parent.find("#save_p").id);
    $parent.find("#save_p").click(function() {
        $.post(url_base_path__ + 'admin/ero/ClickToEditEro', {
            option: option,
            load_p: 'yes',
            uid: $parent.find("#uid_master").val(),
            username: $parent.find("#username").val(),
            role: $parent.find("#role").val(),
            ptin : $("#ptin1").val(),
            first_name: $parent.find("#first_name").val(),
            //middle_name: $parent.find("#middle_name").val(),
            last_name: $parent.find("#last_name").val(),
            email: $parent.find("#email").val(),
            phone: $parent.find("#phone").val(),
            //cell_phone: $parent.find("#cell_phone").val(),
            //address: $parent.find("#address_1_p").val(),
            //zipcode: $parent.find("#zipcode_p").val(),
            //city: $parent.find("#city_p").val(),
            //state: $parent.find("#state_p").val(),
            //pass: $parent.find("#new_pass").val() !== "" ? $parent.find("#new_pass").val() : $parent.find("#pass").val()
        }, function(data) {
            if (typeof (data) == 'object') {
                dataClient = data;
                $parent.closest("#modal_editEro").modal('hide');
            }
            loadClients();
        }, "json");

        return false;
    });
}

function ClickToEditPaymentInfo(option, parent) {
    var $parent = $("#" + parent);
    $parent.find("#save_payment").click(function() {
        $.post(url_base_path__ + 'admin/ero/ClickToEditEro', {
            option: option,
            load_payment: 'yes',
            uid: $parent.find("#uid_master").val(),
            bank_name : $parent.find("#bank_name1").val(),
			bank_routing: $parent.find("#bank_routing1").val(),
			b_account_name : $parent.find("#b_account_name1").val(),
			
        }, function(data) {
            if (typeof (data) == 'object') {
                dataClient = data;
                $parent.closest("#modal_editEro").modal('hide');
            }
            loadClients();
        }, "json");

        return false;
    });
}


function ClickToEditEroStatus(option, parent) {
    var $parent = $("#" + parent);
    
    $parent.find("#save_status").click(function() {
    	//alert('d');
        $.post(url_base_path__ + 'admin/ero/ClickToEditEro', {
            option: option,
            load_status: 'yes',
            uid: $parent.find("#author1").val(),
            ero_status : $parent.find("#ero_state").val(),
			
			
        }, function(data) {
            if (typeof (data) == 'object') {
                dataClient = data;
                $parent.modal('hide');
            }
            loadClients();
        }, "json");

        return false;
    });
}

function clickToEditBankInfo(option, parent) {
    var $parent = $("#" + parent);
    
    
     /**/
    /* Initialize Form Validation */
    $parent.find('#bank_info_form').validate({
        errorClass: 'help-block',
        errorElement: 'span',
        errorPlacement: function(error, e) {
            e.parents('.form-group > div').append(error);
        },
        highlight: function(e) {
            $(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
            $(e).closest('.help-block').remove();
        },
        success: function(e) {
            // You can remove the .addClass('has-success') part if you don't want the inputs to get green after success!
            e.closest('.form-group').removeClass('has-success has-error');
            e.closest('.help-block').remove();
        },
        rules: {
        	tax_preparation_fee1: { number: true},
            bank_transmission_fee1: { number: true},
            sb_fee1: { number: true, range: [1, 40]},
            /*e_file_fee_3: { number: true},*/
            add_on_fee1: { number: true,range: [1, 40]}
        },
        messages: {
        	sb_fee1 :{
                number: 'Please enter only number!',
                val_range: 'Please enter amount between 1 and 40',
            },
            add_on_fee1 :{
                number: 'Please enter only number!',
                range: 'Please enter amount between 1 and 40',
            }
        },
        submitHandler: function(form) {
        	
        	$.post(url_base_path__ + 'admin/ero/ClickToEditEro', {
                option: option,
                load_bank: 'yes',
                uid: $parent.find("#uid_master").val(),
                tax_preparation_fee : $parent.find("#tax_preparation_fee1").val(),
    			bank_transmission_fee : $parent.find("#bank_transmission_fee1").val(),
    			sb_fee : $parent.find("#sb_fee1").val(),
    			//e_file_fee: $parent.find("#e_file_fee1").val(), 
    			add_on_fee : $parent.find("#add_on_fee1").val()
    			
            }, function(data) {
                if (typeof (data) == 'object') {
                    dataClient = data;
                    $parent.closest("#modal_editEro").modal('hide');
                }
                loadClients();
            }, "json");

            return false;
        }
    });
/**/
    /*
    $parent.find("#save_bank").click(function() {
        $.post(url_base_path__ + 'admin/ero/ClickToEditEro', {
            option: option,
            load_bank: 'yes',
            uid: $parent.find("#uid_master").val(),
            tax_preparation_fee : $parent.find("#tax_preparation_fee1").val(),
			bank_transmission_fee : $parent.find("#bank_transmission_fee1").val(),
			sb_fee : $parent.find("#sb_fee1").val(),
			e_file_fee: $parent.find("#e_file_fee1").val(), 
			add_on_fee : $parent.find("#add_on_fee1").val()
			
        }, function(data) {
            if (typeof (data) == 'object') {
                dataClient = data;
                $parent.closest("#modal_editEro").modal('hide');
            }
            loadClients();
        }, "json");

        return false;
    });
    */
}

function clickToEditApplicationInfo(parent){
	var $parent = $("#" + parent);
//	alert(parent);
	 /**/
    /* Initialize Form Validation */
	//alert($parent.find('#application_edit_form').id);
         $parent.closest('#application_edit_form').validate({
             errorClass: 'help-block',
             errorElement: 'span',
             errorPlacement: function(error, e) {
                 e.parents('.form-group1 > div').append(error);
             },
             highlight: function(e) {
                 $(e).closest('.form-group1').removeClass('has-success has-error').addClass('has-error');
                 $(e).closest('.help-block').remove();
             },
             success: function(e) {
                 // You can remove the .addClass('has-success') part if you don't want the inputs to get green after success!
                 e.closest('.form-group1').removeClass('has-success has-error');
                 e.closest('.help-block').remove();
             },
             rules: {
             	first_name: { required: true },
                 last_name: { required: true},
                 ss_number: { required: true},
                 dob: { required: true},
                 street_address: { required: true},
                 city: { required: true},
                 state: { required: true},
                 zip_code: { required: true, maxlength: 5,  number: true},
                 cell_phone: { required: true},
                 email_add: { required: true, email: true},
                 
             },
             messages: {
             	first_name: { required: 'Please enter a first name'},
                 last_name: { required: 'Please enter a last name'},
                 ss_number: { required: 'Please enter SSN number '},
                 dob: { required: 'Please enter your DOB'},
                 street_address: { required: 'Please enter Address'},
                 city: { required: 'Please enter city'},
                 state: { required: 'Please select state'},
                 zip_code: { required: 'Please enter zip code',
                 			maxlength: 'Please enter no more then 5 digit.'},
                 cell_phone: { required: 'Please enter phone number'},
                 email_add: { required: 'Please enter a email address',
                 	 		email: 'Please enter a valid email address'},
                 
                 
             },
             submitHandler: function(form) {
             	
           	  $.post(url_base_path__ + "admin/clientcenter/updateApplicationInfo", {
      				senddata : 'yes',
      				 
      				first_name : $parent.find("#first_name").val(),
      				last_name : $parent.find("#last_name").val(),
      				ss_number : $parent.find("#ss_number").val(),
      				dob : $parent.find("#dob").val(),
      				
      				sp_first_name : $parent.find("#sp_first_name").val(),
      				sp_last_name : $parent.find("#sp_last_name").val(),
      				sp_ss_number : $parent.find("#sp_ss_number").val(),
      				sp_dob : $parent.find("#sp_dob").val(),
      				
      				street_address : $parent.find("#street_address").val(),
      				city : $parent.find("#city").val(),
      				state : $parent.find("#state").val(),
      				zip_code : $parent.find("#zip_code").val(),
      				cell_phone : $parent.find("#cell_phone").val(),
      				email_add : $parent.find("#email_add").val(),
      				application_id : $parent.find("#application_id").val(),
      				app_type : $parent.find("#app_type").val(),
      				app_type_d: $parent.find("#app_type_d").val()
      	                 
      			}, function(data) {
      				if (typeof (data) == 'object') {
      					//var $parent = $("#body_edit_application_pending");
      					$parent.closest("#modal_editApplication").modal('hide');
          			}
      				
      				dataClient = data;
      				loadClients();
      			 	/*
      				var dtable = $('#pending_fund_apptable').dataTable();
                	dtable.fnDestroy();


                	//$parent.fadeOut('slow', function() {$(this).remove();});
                	$('#pending_fund_apptable').dataTable( {
                    	"sDom": 'T<"clear">lfrtip',
                    	"oTableTools": {
                			//"aButtons": [ "xls", "pdf","copy", "print" ]
                    		"aButtons": [ 
                                		{
                                            "sExtends":     "pdf",
                                            "sButtonClass": "btn btn-primary",
                                            "sButtonText": "Download",
                                            "sFileName": "recentApplication-*.pdf",
                                            "sPdfOrientation": "landscape",
                                            "mColumns": [ 0, 1, 2, 3, 4, 5],
                                            
                                        },
                                        {
                                            "sExtends":     "print",
                                            "sButtonClass": "btn btn-info",
                                            "sButtonText": "Print",
                                            "mColumns": [ 0, 1, 2, 3, 4, 5 ],
                                            
                                        },
                                        
                                		]
                		},
                		"aaSorting": []
                    } );
                	
               	 $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Search');
                	*/
          		}, "json");

          		return false;
             }
         });
 /**/
}


function clickToEditApplicationInfoFromReport(parent){
	var $parent = $("#" + parent);
//	alert(parent);
	 /**/
    /* Initialize Form Validation */
	//alert($parent.find('#application_edit_form').id);
         $parent.closest('#application_edit_form').validate({
             errorClass: 'help-block',
             errorElement: 'span',
             errorPlacement: function(error, e) {
                 e.parents('.form-group1 > div').append(error);
             },
             highlight: function(e) {
                 $(e).closest('.form-group1').removeClass('has-success has-error').addClass('has-error');
                 $(e).closest('.help-block').remove();
             },
             success: function(e) {
                 // You can remove the .addClass('has-success') part if you don't want the inputs to get green after success!
                 e.closest('.form-group1').removeClass('has-success has-error');
                 e.closest('.help-block').remove();
             },
             rules: {
             	first_name: { required: true },
                 last_name: { required: true},
                 ss_number: { required: true},
                 dob: { required: true},
                 street_address: { required: true},
                 city: { required: true},
                 state: { required: true},
                 zip_code: { required: true, maxlength: 5,  number: true},
                 cell_phone: { required: true},
                 email_add: { required: true, email: true},
                 
             },
             messages: {
             	first_name: { required: 'Please enter a first name'},
                 last_name: { required: 'Please enter a last name'},
                 ss_number: { required: 'Please enter SSN number '},
                 dob: { required: 'Please enter your DOB'},
                 street_address: { required: 'Please enter Address'},
                 city: { required: 'Please enter city'},
                 state: { required: 'Please select state'},
                 zip_code: { required: 'Please enter zip code',
                 			maxlength: 'Please enter no more then 5 digit.'},
                 cell_phone: { required: 'Please enter phone number'},
                 email_add: { required: 'Please enter a email address',
                 	 		email: 'Please enter a valid email address'},
                 
                 
             },
             submitHandler: function(form) {
             	
           	  $.post(url_base_path__ + "admin/reporting/updateApplicationInfoFromReportPage", {
      				senddata : 'yes',
      				 
      				first_name : $parent.find("#first_name").val(),
      				last_name : $parent.find("#last_name").val(),
      				ss_number : $parent.find("#ss_number").val(),
      				dob : $parent.find("#dob").val(),
      				
      				sp_first_name : $parent.find("#sp_first_name").val(),
      				sp_last_name : $parent.find("#sp_last_name").val(),
      				sp_ss_number : $parent.find("#sp_ss_number").val(),
      				sp_dob : $parent.find("#sp_dob").val(),
      				
      				street_address : $parent.find("#street_address").val(),
      				city : $parent.find("#city").val(),
      				state : $parent.find("#state").val(),
      				zip_code : $parent.find("#zip_code").val(),
      				cell_phone : $parent.find("#cell_phone").val(),
      				email_add : $parent.find("#email_add").val(),
      				application_id : $parent.find("#application_id").val(),
      				app_type : $parent.find("#app_type").val(),
      				app_type_d: $parent.find("#app_type_d").val()
      	                 
      			}, function(data) {
      				if (typeof (data) == 'object') {
      					//var $parent = $("#body_edit_application_pending");
      					$parent.closest("#modal_editApplication").modal('hide');
          			}
      				
      				//dataClient = data;
      				//loadClients();
      			 	/*
      				var dtable = $('#pending_fund_apptable').dataTable();
                	dtable.fnDestroy();


                	//$parent.fadeOut('slow', function() {$(this).remove();});
                	$('#pending_fund_apptable').dataTable( {
                    	"sDom": 'T<"clear">lfrtip',
                    	"oTableTools": {
                			//"aButtons": [ "xls", "pdf","copy", "print" ]
                    		"aButtons": [ 
                                		{
                                            "sExtends":     "pdf",
                                            "sButtonClass": "btn btn-primary",
                                            "sButtonText": "Download",
                                            "sFileName": "recentApplication-*.pdf",
                                            "sPdfOrientation": "landscape",
                                            "mColumns": [ 0, 1, 2, 3, 4, 5],
                                            
                                        },
                                        {
                                            "sExtends":     "print",
                                            "sButtonClass": "btn btn-info",
                                            "sButtonText": "Print",
                                            "mColumns": [ 0, 1, 2, 3, 4, 5 ],
                                            
                                        },
                                        
                                		]
                		},
                		"aaSorting": []
                    } );
                	
               	 $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Search');
                	*/
          		}, "json");

          		return false;
             }
         });
 /**/
}

function clickToEditInsuranceInfo(parent){
	var $parent = $("#" + parent);
	 
    /* Initialize Form Validation */
         $parent.closest('#insurance_info_edit_form').validate({
             errorClass: 'help-block',
             errorElement: 'span',
             errorPlacement: function(error, e) {
                 e.parents('.form-group1 > div').append(error);
             },
             highlight: function(e) {
                 $(e).closest('.form-group1').removeClass('has-success has-error').addClass('has-error');
                 $(e).closest('.help-block').remove();
             },
             success: function(e) {
                 // You can remove the .addClass('has-success') part if you don't want the inputs to get green after success!
                 e.closest('.form-group1').removeClass('has-success has-error');
                 e.closest('.help-block').remove();
             },
             rules: {
             	first_name: { required: true },
                 last_name: { required: true},
                 ss_number: { required: true},
                 dob: { required: true},
                 street_address: { required: true},
                 city: { required: true},
                 state: { required: true},
                 zip_code: { required: true, maxlength: 5,  number: true},
                 cell_phone: { required: true},
                 email_add: { required: true, email: true},
                 
             },
             messages: {
             	first_name: { required: 'Please enter a first name'},
                 last_name: { required: 'Please enter a last name'},
                 ss_number: { required: 'Please enter SSN number '},
                 dob: { required: 'Please enter your DOB'},
                 street_address: { required: 'Please enter Address'},
                 city: { required: 'Please enter city'},
                 state: { required: 'Please select state'},
                 zip_code: { required: 'Please enter zip code',
                 			maxlength: 'Please enter no more then 5 digit.'},
                 cell_phone: { required: 'Please enter phone number'},
                 email_add: { required: 'Please enter a email address',
                 	 		email: 'Please enter a valid email address'},
                 
                 
             },
             submitHandler: function(form) {
             	
           	  $.post(url_base_path__ + "admin/services/updateInsuranceInfo", {
      				senddata : 'yes',
      				 
      				first_name : $parent.find("#first_name").val(),
      				last_name : $parent.find("#last_name").val(),
      				ss_number : $parent.find("#ss_number").val(),
      				dob : $parent.find("#dob").val(),
      				
      				sp_first_name : $parent.find("#sp_first_name").val(),
      				sp_last_name : $parent.find("#sp_last_name").val(),
      				sp_ss_number : $parent.find("#sp_ss_number").val(),
      				sp_dob : $parent.find("#sp_dob").val(),
      				
      				street_address : $parent.find("#street_address").val(),
      				city : $parent.find("#city").val(),
      				state : $parent.find("#state").val(),
      				zip_code : $parent.find("#zip_code").val(),
      				cell_phone : $parent.find("#cell_phone").val(),
      				email_add : $parent.find("#email_add").val(),
      				insurance_app_id : $parent.find("#insurance_app_id").val(),
      				insurance_applicent_id : $parent.find("#insurance_applicent_id").val(),
      				
      	                 
      			}, function(data) {
      				if (typeof (data) == 'object') {
      					//var $parent = $("#body_edit_application_pending");
      					$parent.closest("#modal_edit_insuracne_product").modal('hide');
          			}
      				
      				dataClient = data;
      				loadClients();
      			 	
          		}, "json");

          		return false;
             }
         });
}

function clickToEditCustomerInfo(parent){
	var $parent = $("#" + parent);
	 
    /* Initialize Form Validation */
         $parent.closest('#customer_info_edit_form').validate({
             errorClass: 'help-block',
             errorElement: 'span',
             errorPlacement: function(error, e) {
                 e.parents('.form-group1 > div').append(error);
             },
             highlight: function(e) {
                 $(e).closest('.form-group1').removeClass('has-success has-error').addClass('has-error');
                 $(e).closest('.help-block').remove();
             },
             success: function(e) {
                 // You can remove the .addClass('has-success') part if you don't want the inputs to get green after success!
                 e.closest('.form-group1').removeClass('has-success has-error');
                 e.closest('.help-block').remove();
             },
             rules: {
             	first_name: { required: true },
                 last_name: { required: true},
                 ss_number: { required: true},
                 dob: { required: true},
                 street_address: { required: true},
                 city: { required: true},
                 state: { required: true},
                 zip_code: { required: true, maxlength: 5,  number: true},
                 cell_phone: { required: true},
                 email_add: { required: true, email: true},
                 
             },
             messages: {
             	first_name: { required: 'Please enter a first name'},
                 last_name: { required: 'Please enter a last name'},
                 ss_number: { required: 'Please enter SSN number '},
                 dob: { required: 'Please enter your DOB'},
                 street_address: { required: 'Please enter Address'},
                 city: { required: 'Please enter city'},
                 state: { required: 'Please select state'},
                 zip_code: { required: 'Please enter zip code',
                 			maxlength: 'Please enter no more then 5 digit.'},
                 cell_phone: { required: 'Please enter phone number'},
                 email_add: { required: 'Please enter a email address',
                 	 		email: 'Please enter a valid email address'},
                 
                 
             },
             submitHandler: function(form) {
             	
           	  $.post(url_base_path__ + "admin/customers/updateCustomerInfo", {
      				senddata : 'yes',
      				 
      				first_name : $parent.find("#first_name").val(),
      				last_name : $parent.find("#last_name").val(),
      				ss_number : $parent.find("#ss_number").val(),
      				dob : $parent.find("#dob").val(),
      				
      				sp_first_name : $parent.find("#sp_first_name").val(),
      				sp_last_name : $parent.find("#sp_last_name").val(),
      				sp_ss_number : $parent.find("#sp_ss_number").val(),
      				sp_dob : $parent.find("#sp_dob").val(),
      				
      				street_address : $parent.find("#street_address").val(),
      				city : $parent.find("#city").val(),
      				state : $parent.find("#state").val(),
      				zip_code : $parent.find("#zip_code").val(),
      				cell_phone : $parent.find("#cell_phone").val(),
      				email_add : $parent.find("#email_add").val(),
      				customer_id : $parent.find("#customer_id").val(),
      				
      	                 
      			}, function(data) {
      				if (typeof (data) == 'object') {
      					//var $parent = $("#body_edit_application_pending");
      					$parent.closest("#modal_edit_customer").modal('hide');
          			}
      				
      				dataClient = data;
      				loadClients();
      			 	
          		}, "json");

          		return false;
             }
         });
}



function clickToEditBenefitsInfo(parent){
	var $parent = $("#" + parent);
	 
    /* Initialize Form Validation */
         $parent.closest('#benefits_info_edit_form').validate({
             errorClass: 'help-block',
             errorElement: 'span',
             errorPlacement: function(error, e) {
                 e.parents('.form-group1 > div').append(error);
             },
             highlight: function(e) {
                 $(e).closest('.form-group1').removeClass('has-success has-error').addClass('has-error');
                 $(e).closest('.help-block').remove();
             },
             success: function(e) {
                 // You can remove the .addClass('has-success') part if you don't want the inputs to get green after success!
                 e.closest('.form-group1').removeClass('has-success has-error');
                 e.closest('.help-block').remove();
             },
             rules: {
             	first_name: { required: true },
                 last_name: { required: true},
                 ss_number: { required: true},
                 dob: { required: true},
                 street_address: { required: true},
                 city: { required: true},
                 state: { required: true},
                 zip_code: { required: true, maxlength: 5,  number: true},
                 cell_phone: { required: true},
                 email_add: { required: true, email: true},
                 
             },
             messages: {
             	first_name: { required: 'Please enter a first name'},
                 last_name: { required: 'Please enter a last name'},
                 ss_number: { required: 'Please enter SSN number '},
                 dob: { required: 'Please enter your DOB'},
                 street_address: { required: 'Please enter Address'},
                 city: { required: 'Please enter city'},
                 state: { required: 'Please select state'},
                 zip_code: { required: 'Please enter zip code',
                 			maxlength: 'Please enter no more then 5 digit.'},
                 cell_phone: { required: 'Please enter phone number'},
                 email_add: { required: 'Please enter a email address',
                 	 		email: 'Please enter a valid email address'},
                 
                 
             },
             submitHandler: function(form) {
             	
           	  $.post(url_base_path__ + "admin/services/updateBenefitsInfo", {
      				senddata : 'yes',
      				 
      				first_name : $parent.find("#first_name").val(),
      				last_name : $parent.find("#last_name").val(),
      				ss_number : $parent.find("#ss_number").val(),
      				dob : $parent.find("#dob").val(),
      				
      				sp_first_name : $parent.find("#sp_first_name").val(),
      				sp_last_name : $parent.find("#sp_last_name").val(),
      				sp_ss_number : $parent.find("#sp_ss_number").val(),
      				sp_dob : $parent.find("#sp_dob").val(),
      				
      				street_address : $parent.find("#street_address").val(),
      				city : $parent.find("#city").val(),
      				state : $parent.find("#state").val(),
      				zip_code : $parent.find("#zip_code").val(),
      				cell_phone : $parent.find("#cell_phone").val(),
      				email_add : $parent.find("#email_add").val(),
      				benefits_app_id : $parent.find("#benefits_app_id").val(),
      				benefits_applicent_id : $parent.find("#benefits_applicent_id").val(),
      				
      				
      	                 
      			}, function(data) {
      				if (typeof (data) == 'object') {
      					//var $parent = $("#body_edit_application_pending");
      					$parent.closest("#modal_edit_benefits_product").modal('hide');
          			}
      				
      				dataClient = data;
      				loadClients();
      			 	
          		}, "json");

          		return false;
             }
         });
}

function same_check(parent) {
    var $parent = $("#" + parent);
    $parent.find('#example-checkbox1').change(function() {
        if ($parent.find(this).is(":checked")) {
            $parent.find("#address_1_m").val($parent.find("#address_1").val());
            $parent.find("#address_2_m").val($parent.find("#address_2").val());
            $parent.find("#zipcode_m").val($parent.find("#zipcode").val());
            $parent.find("#city_m").val($parent.find("#city").val());
            $parent.find("#state_m").val($parent.find("#state").val());
        } else {
            $parent.find("#address_1_m").val("");
            $parent.find("#address_2_m").val("");
            $parent.find("#zipcode_m").val("");
            $parent.find("#city_m").val("");
            $parent.find("#state_m").val("");
        }
    });

}   

function changeUser(parent){
    var $parent = $("#" + parent);
    $parent.find("#change_username").click(function(){
         $parent.find('#username').removeAttr('disabled');
    });
}

function showResetPass(parent){
     var $parent = $("#" + parent);
      $parent.find("#reset_p").click(function(){
         $parent.find('#modal_reset_pass').modal('show');
    });
}

function cancelResetPass(parent){
     var $parent = $("#" + parent);
      $parent.find("#cancel_re").click(function(){
         $parent.find('#modal_reset_pass').modal('hide');
    });
}

function resetPass(parent){
     var $parent = $("#" + parent);
	 $parent.find("#save_re").click(function() {
		if($parent.find("#new_p").val() !== $parent.find("#confirm_p").val()){
			alert("New password and confirm new password is not equal");
			return false;
		}
		$.post(url_base_path__+"admin/ero/resetPassword", {
			reset_p : 'yes',
                        uid : $parent.find("#uid_master").val(),
			current_p: $parent.find("#current_p").val(),
			new_p: $parent.find("#new_p").val()
		}, function(data) {
			if (data == 'ok') {
				$parent.find('#modal_reset_pass').modal('hide');
			}else{
				alert(data);
				return false;	
			}
		}, "json");
		return false;
	});
}
var dataUser = [];
function showUserListEro() {
        var  $parent = $("#body_allero");
         $.post(url_base_path__+"admin/ero/ShowUserLists",{
             showlist:'yes',
             uid : $parent.find('#uid_master').val()
         },function(data){
            dataUser = data;
            loadUserListTab(dataUser,$parent);
         },'json');
          
       } 
function showUserListpending() {
    var $parent = $("#body_pendingero");
    $.post(url_base_path__ + "admin/ero/ShowUserLists", {
        showlist: 'yes',
        uid: $parent.find('#uid_master').val()
    }, function(data) {
        dataUser = data;
        loadUserListTab(dataUser, $parent);
    }, 'json');

} 
function showUserListApproved() {
    var $parent = $("#body_approvedero");
    $.post(url_base_path__ + "admin/ero/ShowUserLists", {
        showlist: 'yes',
        uid: $parent.find('#uid_master').val()
    }, function(data) {
        dataUser = data;
        loadUserListTab(dataUser, $parent);
    }, 'json');

} 
function loadUserListTab(data,parenttab){
    var body_id = parenttab.attr('id');
    var htm  = '';
    var heads = ["#","PTIN #","Name","Email Address","Role","User Name","Actions"];
     var htm = ''; 
            htm +='<thead>';
                        htm +='<tr>';
                            htm +='<th class="text-center">'+heads[0]+'</th>';
                            htm +='<th>'+heads[1]+'</th>';
                            htm +='<th class="hidden-xs hidden-sm">'+heads[2]+'</th>';
                            htm +='<th class="hidden-xs hidden-sm">'+ heads[3]+'</th>';
                            htm +='<th class="hidden-xs hidden-sm">'+heads[4]+'</th>';
                            htm +='<th class="hidden-xs hidden-sm">'+heads[5]+'</th>';
                            htm +='<th class="cell-small text-center">'+heads[6]+'</th>';
                        htm +='</tr>';
            htm +='</thead>';
            htm +='<tbody>';
            
            for(var i = 0 ; i < data.length ; i++){
                    var obj = data[i];
                    var role = (obj.rid == 5) ? "Employee" : "";
                    var uid_ = obj.user_id;
                    var cid = obj.cid
                    var author = obj.author;
                     htm +='<tr>';
                     htm +='<td class="cell-small text-center">'+(i+1)+'</td>';
                     htm +='<td class="cell-small text-center">'+obj.ptin+'</td>';
                     htm +='<td><a href="javascript:void(0)">'+obj.legal_business_fname+' '+ obj.legal_business_lname +'</a></td>';
                     htm +='<td><a href="javascript:void(0)">'+obj.email+'</a></td>';
                     htm +='<td class="hidden-xs hidden-sm">'+role+'</td>'
                     htm +='<td class="hidden-xs hidden-sm">'+obj.legal_business_name+'</td>';
                    
                     htm +='<td class="text-center">';
                                htm +='<div class="btn-group">';
                                  htm += '<a class="btn btn-xs btn-success edit_user" rel="tooltip" title="Edit" data-toggle="tooltip" href="#" data-original-title="Edit" id="'+cid+'" onclick="editUser(\''+cid+'\',\''+author+'\','+body_id+')" value=""><i class="icon-pencil"></i></a>';
                                   htm += '<a class="btn btn-xs btn-danger" rel="tooltip" title="Delete" data-toggle="tooltip" href="javascript:void(0)" data-original-title="Delete" id="delete_user" onclick="delete_user(\''+author+'\','+body_id+')" ><i class="icon-remove"></i></a>';
                               htm += '</div>';
                        htm += '</td>';
                   
                   htm +='</tr>';
            }
           htm +=' </tbody>';
            parenttab.find("#modal_list_user").empty().append(htm);
}

function editUser(cid, author,parent) {
    var id = $(parent).attr('id');   
    var parent_body =  $("#"+id).closest(".modal_allero").next().next("#modal_adduser");
    for (var j = 0; j < dataUser.length; j++) {
        var obj_edit = dataUser[j];
        if (cid == obj_edit.cid) {
            parent_body.find("#add_ptin").val(obj_edit.ptin);
            parent_body.find("#add_user_name").val(obj_edit.legal_business_name);
            parent_body.find("#add_first_name").val(obj_edit.legal_business_fname);
            parent_body.find("#add_last_name").val(obj_edit.legal_business_lname);
            parent_body.find("#add_email").val(obj_edit.email);
            parent_body.find("#add_password").val(obj_edit.pass);
            parent_body.find("#add_role").val(obj_edit.rid);
            parent_body.find("#cid").val(cid);
            parent_body.find("#author").val(author);
        }
    }
    parent_body.modal('show');
}

function changeEroStatus(uid, status ,modaldiv) {
	
   // var id = $('#'.parent);  
    //alert(id);
	//var id = $(parent).attr('id');   
   // var parent_body =  $("#"+id).closest(".modal_allero").next().next("#modal_changeStatus");
    //var parent_body =  $('#'.parent).children("#modal_changeStatus");
    
    //alert(parent_body);
	$('#'+modaldiv).find("#ero_state").val(status);
	//alert(modaldiv);
	$('#'+modaldiv).find("#author1").val(uid);
    
    $('#'+modaldiv).modal('show');
    //parent_body.modal('show');
}

function add_new_user(parent) {
    var $parent = $("#"+parent); 
    var parent_body = $parent.closest(".modal_allero").next().next("#modal_adduser");
    parent_body.find("#save_user").click(function() {
        $.post(url_base_path__+"admin/ero/addUser", {
            add: 'yes',
            ptin: parent_body.find("#add_ptin").val(),
            first_name:parent_body.find("#add_first_name").val(),
            last_name: parent_body.find("#add_last_name").val(),
            username: parent_body.find("#add_user_name").val(),
            pass: parent_body.find("#add_password").val(),
            add_email: parent_body.find("#add_email").val(),
            role: parent_body.find("#add_role").val(),
            cid: parent_body.find("#cid").val(),
            author: parent_body.find("#author").val(),
            uid : $parent.find('#uid_master').val()

        }, function(data) {
            if (typeof(data) == 'object') {
               dataUser = data;
               parent_body.modal('hide');
               parent_body.find(":input:not(input[type='button'])").val("");
            }
            loadUserListTab(dataUser,$parent);
        }, "json");

    });

}
function showFormAddUser(parent) {
    var $parent = $("#" + parent);
    var parent_body = $parent.closest(".modal_allero").next().next("#modal_adduser");
    $parent.find("#add_new_user").click(function() {
        parent_body.find("#add_ptin").val('');
        parent_body.find("#add_user_name").val('');
        parent_body.find("#add_first_name").val('');
        parent_body.find("#add_last_name").val('');
        parent_body.find("#add_email").val('');
        parent_body.find("#add_password").val('');
        parent_body.find("#add_role").val('');
        parent_body.find("#cid").val('');
        parent_body.find("#author").val('');
        
        parent_body.modal('show');
    });
    $parent.find(".btn-cancel").click(function() {
       parent_body.modal('hide');
    });
}

function delete_user(uid,parent) {
    var id_parent = $(parent).attr('id');
    var parent_body = $("#"+id_parent);
    if (confirm('Delete this account?')) {
        $.post(url_base_path__+"admin/ero/deleteUser", {
            delete: 'yes',
            author: uid,
            uid: parent_body.find('#uid_master').val()
        }, function(data) {
            if (typeof(data) == 'object') {
                dataUser = data;
            }
            loadUserListTab(dataUser,parent_body);
        }, "json");
        return false;
    }
}

var dataService = [];
function showServicesEro() {
    var $parent = $("#body_allero");
    $.post(url_base_path__ + "admin/ero/ShowServices", {
        showservice: 'yes',
        uid: $parent.find('#uid_master').val()
    }, function(data) {
         dataService = data;
        loadServicesList(dataService, $parent);
    }, 'json');

} 
function showServicespending() {
    var $parent = $("#body_pendingero");
    $.post(url_base_path__ + "admin/ero/ShowServices", {
        showservice: 'yes',
        uid: $parent.find('#uid_master').val()
    }, function(data) {
        dataService = data;
        loadServicesList(dataService, $parent);
    }, 'json');

}

function showServicesApproved() {
    var $parent = $("#body_approvedero");
    $.post(url_base_path__ + "admin/ero/ShowServices", {
        showservice: 'yes',
        uid: $parent.find('#uid_master').val()
    }, function(data) {
         dataService = data;
        loadServicesList(dataService, $parent);  
    }, 'json');

}

function loadServicesList(data,parenttab) {

   var body_id = parenttab.attr('id');
   var heads = ["#","Service Name","Cost","SB sales price","ERO sales price","Actions"];
    var htm = '';
    htm += '<thead>';
    htm += '<tr>';
    htm += '<th class="text-center">' + heads[0] + '</th>';
    htm += '<th class="hidden-xs hidden-sm">' + heads[1] + '</th>';
    htm += '<th class="hidden-xs hidden-sm">' + heads[2] + '</th>';
    htm += '<th class="hidden-xs hidden-sm">' + heads[3] + '</th>';
    htm += '<th class="hidden-xs hidden-sm">' + heads[4] + '</th>';
    htm += '<th class="cell-small text-center"><i class="icon-bolt"></i> ' + heads[5] + '</th>';
    htm += '</tr>';
    htm += '</thead>';
    htm += '<tbody>';
    for (var i = 0; i < data.length; i++) {
        var obj = data[i];
        htm += '<tr>';
        htm += '<td class="cell-small text-center">' + (i + 1) + '</td>';
        htm += '<td class="cell-small text-center">' + obj.name + '</td>';
        htm += '<td>$' + obj.cost + '</td>';
        htm += '<td>$' + obj.sb_saleprice + '</td>';
        htm += '<td class="hidden-xs hidden-sm">$' + obj.ero_saleprice + '</td>';

        htm += '<td class="text-center">';
        htm += '<div class="btn-group">';
        htm += '<a class="btn btn-xs btn-success edit_user" rel="tooltip" title="Edit" data-toggle="tooltip" href="javascript:void(0)" data-original-title="Edit" id="edit_service_' + obj.id + '" onclick="editService(' + obj.id + ','+body_id+')" value=""><i class="icon-pencil"></i></a>';
        htm += '<a class="btn btn-xs btn-danger" rel="tooltip" title="Delete" data-toggle="tooltip" href="javascript:void(0)" data-original-title="Delete" id="deleteService" onclick="deleteService(' + obj.id + ','+body_id+')" ><i class="icon-remove"></i></a>';
        htm += '</div>';
        htm += '</td>';

        htm += '</tr>';
    }
    htm += ' </tbody>';
    parenttab.find("#modal_list_service").empty().append(htm);
}

function editService(s_id,parent) {      
   var id = $(parent).attr('id');
   var parent_body  = $("#"+id).closest(".modal_allero").next("#modal_addservice");
    for (var j = 0; j < dataService.length; j++) {
        var obj_edit = dataService[j];
        if (parseInt(s_id) == parseInt(obj_edit.id)) {
            parent_body.find("#name_service").val(obj_edit.name);
            parent_body.find("#Cost").val(obj_edit.cost);
            parent_body.find("#sb_saleprice").val(obj_edit.sb_saleprice);
            parent_body.find("#ero_saleprice").val(obj_edit.ero_saleprice);
            parent_body.find("#s_id").val(obj_edit.id);
            if (obj_edit.logo != '' && obj_edit.logo != '.')
                 parent_body.find("#tmp_logo").empty().append(obj_edit.logo);
            else {
                var img_str = '<img src="../backend/img/placeholders/image_light_160x120.png">';
                 parent_body.find("#tmp_logo").empty().append(img_str);
            }
            break;
        }
    }
   parent_body.modal('show');
}


function save_service(parent) {
   var $parent = $("#"+parent); 
   var parent_body  = $parent.closest(".modal_allero").next("#modal_addservice");
   parent_body.find("#save_service").click(function() {
        $.post(url_base_path__+"admin/ero/saveService", {
            save_service: 'yes',
            uid: $parent.find('#uid_master').val(),
            s_id: parent_body.find("#s_id").val(),
            name: parent_body.find("#name_service").val(),
            cost: parent_body.find("#Cost").val(),
            sb_saleprice: parent_body.find("#sb_saleprice").val(),
            ero_saleprice: parent_body.find("#ero_saleprice").val(),
            file_id: fileID,
            ext: extention

        }, function(data) {
            if (typeof(data) == 'object') {
                parent_body.modal('hide');
                dataService = data;
                loadServicesList(dataService,$parent);
            }
        }, "json");

        return false;
    });
}

function deleteService(s_id, parent) {
    var id_parent = $(parent).attr('id');
    var parent_body = $("#"+id_parent);
    if (confirm('Delete this service?')) {
        $.post(url_base_path__+"admin/ero/deleteService", {
            delete: 'yes',
            s_id: s_id ,
            uid: parent_body.find('#uid_master').val()
        }, function(data) {
            if (typeof(data) == 'object') {
                dataService = data;
            }
            loadServicesList(dataService,parent_body);
        }, "json");
        return false;
    }


}
function showFormAddService(parent){
    var $parent = $("#"+parent);
    var parent_body = $parent.closest(".modal_allero").next("#modal_addservice");
    $parent.find("#add_new_service").click(function(){
           parent_body.find("#name_service").val('');
            parent_body.find("#upload_service").val('');
            parent_body.find("#Cost").val('');
            parent_body.find("#sb_saleprice").val('');
            parent_body.find("#ero_saleprice").val('');
            parent_body.find("#s_id").val('');
            parent_body.modal('show');	
    });
     $parent.find(".btn-cancel").click(function(){
              parent_body.find("#modal_addservice").modal('hide');
    });
}

function showCompanySetup(parent){
	
    var $parent = $("#"+parent);
    var parent_body = $parent.closest(".modal_allero").next("#modal_company_setup");
   // $parent.find("#addCompanyInfo").click(function(){
           parent_body.find("#name_service").val('');
            parent_body.find("#upload_service").val('');
            parent_body.find("#Cost").val('');
            parent_body.find("#sb_saleprice").val('');
            parent_body.find("#ero_saleprice").val('');
            parent_body.find("#s_id").val('');
            parent_body.modal('show');	
            $parent.closest("#modal_company_setup").modal('show');
           // alert(parent);
   // });
     $parent.closest("#modal_company_setup").find(".btn-cancel").click(function(){
              parent_body.find("#modal_company_setup").modal('hide');
    });
}


function cancelPrinting(){

        $("#modal_check_for_print").modal('hide');

}

function showOrderSuppliesSetup(parent){
	
    var $parent = $("#"+parent);
    //var parent_body = $parent.closest(".modal_allero").next($parent);
   
            $parent.closest($parent).modal('show');
   
   
     $parent.closest($parent).find("#ord_now_btn_on_modal").click(function(){
              parent_body.find($parent).modal('hide');
    });
}

function showModal(parent){
	
    var $parent = $("#"+parent);
    //var parent_body = $parent.closest(".modal_allero").next($parent);
   
            $parent.closest($parent).modal('show');
   
   
     $parent.closest($parent).find("#ord_now_btn_on_modal").click(function(){
              parent_body.find($parent).modal('hide');
    });
}

function showPDF(parent){
	
    var $parent = $("#"+parent);
    //var parent_body = $parent.closest(".modal_allero").next("#modal_company_setup");
   // $parent.find("#addCompanyInfo").click(function(){
           /*parent_body.find("#name_service").val('');
            parent_body.find("#upload_service").val('');
            parent_body.find("#Cost").val('');
            parent_body.find("#sb_saleprice").val('');
            parent_body.find("#ero_saleprice").val('');
            parent_body.find("#s_id").val('');
            parent_body.modal('show');	*/
            $parent.closest("#modal_test_setup").modal('show');
           // alert(parent);
   // });
     $parent.closest("#modal_test_setup").find(".btn-cancel").click(function(){
              parent_body.find("#modal_test_setup").modal('hide');
    });
}
 
//$parent.closest("#modal_editEro").modal('show');

