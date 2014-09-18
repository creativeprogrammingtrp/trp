var add2cart = false;
var __keycode__ = '<ho^^slDbtSS695hG>';
var __keyat__ = '(d^^gk67ty@45M)';
function showcartAjax(){
    $.ajax({
        url: url_base_path__+'shop/cart/shopcart', 
        cache: false,
        success: function(message){
            $("#itemsNumber").empty().append(message);
			if (document.getElementById('get_cart_url'))
			{
				$('#shop_cart_url').attr('href',$('#get_cart_url').attr('href'));
			}
        }
    });
}
function removaItem(attributes){
    if(confirm('Are you sure want to remove this item on cart?')){
        var post_card = new Object();
        post_card.attributes 	= attributes;
        post_card.type 			= 'd';
        $.post(url_base_path__+"shop/cart/shopcart", {
            addcard:post_card
        },function(data){
            $("#itemsNumber").empty().append(data);
            if(document.getElementById("cartlistings")){
                loadObjectCart();
            }
        });
    }
}
function removaitemschedule(attributes, ckey){
	 if(confirm('Are you sure want to remove this item on cart?')){
			var post_card = new Object();
			post_card.attributes 	= attributes;
			post_card.type 			= 'd';
			$.post(url_base_path__+"shop/cart/shopcart", {
				addcard:post_card,
				ckey:ckey
			},function(data){
				$("#itemsNumber").empty().append(data);
			});
			 if(document.getElementById("cartlistings")){
                loadObjectCart();
            }
	 }
}
function donate(pkey,check){
    var qlt = 0;
    /* if(document.getElementById("p_qlty")){
        qlt = document.getElementById("p_qlty").value;	
    }
    if(qlt=='' || qlt <=0){
        alert('Please select quantity.');
        if(document.getElementById("p_qlty")){
            document.getElementById("p_qlty").focus();	
        }
        return false;
    }*/
    
    var flag = false;
    var stock = 0;
    if(document.getElementById("p_qlty")){
        if(check != 1 ){
            var qlt1 = document.getElementById("p_qlty").value;	
            if(trim(qlt1) == ''){
                alert('Please select quantity.');
                document.getElementById("p_qlty").focus();
            }
            flag = false;
            stock = instock;
        } else{
            var qlt2 = 1;
            stock = 2;
            flag = true;
        }
    }
        
    if(flag == false){
        qlt = qlt1;
    }else{
        qlt = qlt2;
    }
    if(qlt > stock){
        alert("This quantity out of stock, select smaller quantity or contact to us to buy more.");
        if(document.getElementById("p_qlty")){
            document.getElementById("p_qlty").focus();	
        }
        return false;
    }
    if(pkey != '' && parseInt(qlt, 10) > 0){
        goto('shop/cart/checkout_donate/'+pkey+'/'+qlt);
    }
    return false;
}
function a2c_service(type, pkey){
    var str_attributes = pkey;
    var qlt = 0;
    if(arr_list_location && arr_list_location.length > 0){
        for(var i = 0; i < arr_list_location.length; i++){
            arr_list_location[i].quantity = $("#location_choosed_"+arr_list_location[i].id).val();
            if(parseInt(arr_list_location[i].quantity,10) <= 0 || parseInt(arr_list_location[i].quantity,10) == ''){
                alert("Please select quantity");
                return false;	
            }
            qlt += parseInt(arr_list_location[i].quantity,10);
        }
    }else{
        alert("Please choose location");
        return false;	
    }
    if(qlt > instock){
        alert("This quantity out of stock, select smaller quantity or contact to us to buy more.");
        return false;
    }
    if(pkey != '' && qlt > 0 && type != ''){
        if(document.getElementById("FloGradient16x16x")) document.getElementById("FloGradient16x16x").style.display = 'block';

        var post_card = new Object();
        post_card.type 		= type;
        post_card.qlt 		= qlt;
        post_card.attributes = str_attributes;
                
        $.post(url_base_path__+'shop/cart/shopcart',{
            addcard:post_card,
            location:arr_list_location
        },function(data){
            if(document.getElementById("FloGradient16x16x")) document.getElementById("FloGradient16x16x").style.display = 'none';
            $("#itemsNumber").empty().append(data);
            add2cart = true;
            goto('shop/cart');
        });	
    }	
    return false;
}
function a2c(type, pkey,check){
    var qlt = 0;
    var str_attributes = pkey;
    if(typeof(dataAttributes) == 'object'){
        for(var i = 0; i < dataAttributes.length; i++){
            var obj = dataAttributes[i];
            if(parseInt(obj.display_type, 10) > 0){
                if(typeof(obj.options) == 'undefined' || obj.options.length == 0){
                    continue;	
                }	
            }
            var attribute_id = 'attribute_'+obj.akey;
            if(parseInt(obj.required, 10) == 1){
                var msg = "Please complete '"+obj.label+"' field.";
                if(document.getElementById(attribute_id)){
                    if(document.getElementById(attribute_id).value == ""){
                        alert(msg);
                        document.getElementById(attribute_id).focus();
                        return false;	
                    }	
                }else if(document.getElementsByName(attribute_id+'[]')){
                    var attributes_field = document.getElementsByName(attribute_id+'[]');
                    var check_true = false;
                    for(var j = 0; j < attributes_field.length; j++){
                        if(attributes_field[j].checked == true){
                            check_true = true;
                            break;	
                        }	
                    }
                    if(check_true == false){
                        alert(msg);
                        return false;	
                    }	
                }	
            }
            if(document.getElementById(attribute_id)){
                if(parseInt(obj.display_type, 10) == 0){
                    str_attributes += __keycode__ + obj.akey + __keyat__ + document.getElementById(attribute_id).value;
                }else{
                    str_attributes += __keycode__ + document.getElementById(attribute_id).value;
                }
            }else if(document.getElementsByName(attribute_id+'[]')){
                var attributes_field = document.getElementsByName(attribute_id+'[]');
                for(var j = 0; j < attributes_field.length; j++){
                    if(attributes_field[j].checked == true){
                        str_attributes += __keycode__ + attributes_field[j].value;
                    }	
                }
            }		
        }
    }
    var flag = false;
    var stock = 0;
    if(document.getElementById("p_qlty")){
        if(check != 1 ){
            var qlt1 = document.getElementById("p_qlty").value;	
            if(trim(qlt1) == ''){
                alert('Please select quantity.');
                document.getElementById("p_qlty").focus();
            }
            stock = instock;
            flag = false;
        } else{
            var qlt2 = 1;
            stock = 2;
            flag = true;
        }
    }
        
    if(flag == false){
        qlt = qlt1;
    }else{
        qlt = qlt2;
    }

    /* if(document.getElementById("p_qlty")){
	if(qlt==''  || qlt <=0 ){
		alert('Please select quantity.');
		if(document.getElementById("p_qlty")){
			document.getElementById("p_qlty").focus();	
		}
		return false;
	}
        }*/
    if(qlt > stock){//instock
        alert("This quantity out of stock, select smaller quantity or contact to us to buy more.");
        if(document.getElementById("p_qlty")){
            document.getElementById("p_qlty").focus();	
        }
        return false;
    }
    if(pkey != '' && parseInt(qlt, 10) > 0 && type != ''){
        if(document.getElementById("FloGradient16x16x")) document.getElementById("FloGradient16x16x").style.display = 'block';
        var post_card = new Object();
        post_card.type 		= type;
        post_card.qlt 		= qlt;
        post_card.attributes = str_attributes;
        $.post(url_base_path__+'shop/cart/shopcart', {
            addcard:post_card
        },function(data){
            if(document.getElementById("FloGradient16x16x")) document.getElementById("FloGradient16x16x").style.display = 'none';
            $("#itemsNumber").empty().append(data);
            add2cart = true;
            goto('shop/cart');
        });
        
    }
    return false;
}
function submit_order(type, pkey, count_order){
    var qlt = 0;
    var str_attributes = pkey;
    var check_qlt = false;
    if(typeof(dataAttributes) == 'object'){
        for(var i = 0; i < dataAttributes.length; i++){
            var obj = dataAttributes[i];
            if(parseInt(obj.display_type, 10) > 0){
                if(typeof(obj.options) == 'undefined' || obj.options.length == 0){
                    continue;	
                }	
            }
            var attribute_id = 'attribute_'+obj.akey;
            if(parseInt(obj.required, 10) == 1){
                var msg = "Please complete '"+obj.label+"' field.";
                if(document.getElementById(attribute_id)){
                    if(document.getElementById(attribute_id).value == ""){
                        alert(msg);
                        document.getElementById(attribute_id).focus();
                        return false;	
                    }	
                }else if(document.getElementsByName(attribute_id+'[]')){
                    var attributes_field = document.getElementsByName(attribute_id+'[]');
                    var check_true = false;
                    for(var j = 0; j < attributes_field.length; j++){
                        if(attributes_field[j].checked == true){
                            check_true = true;
                            break;	
                        }	
                    }
                    if(check_true == false){
                        alert(msg);
                        return false;	
                    }	
                }	
            }
            if(document.getElementById(attribute_id)){
                if(parseInt(obj.display_type, 10) == 0){
                    str_attributes += __keycode__ + obj.akey + __keyat__ + document.getElementById(attribute_id).value;
                }else{
                    str_attributes += __keycode__ + document.getElementById(attribute_id).value;
                }
            }else if(document.getElementsByName(attribute_id+'[]')){
                var attributes_field = document.getElementsByName(attribute_id+'[]');
                for(var j = 0; j < attributes_field.length; j++){
                    if(attributes_field[j].checked == true){
                        str_attributes += __keycode__ + attributes_field[j].value;
                    }	
                }
            }		
        }
    }
	
    var arr_card_obj = [];
    var add_card = new Object();
    add_card.type = type;
	
    var total_qty = 0;
	
    for(var c = 0; c < count_order; c++){
        if(document.getElementById("p_qlty_"+c)){
            qlt = document.getElementById("p_qlty_"+c).value;
            total_qty += parseInt(qlt);
        }
        if(total_qty > instock){
            alert("This quantity out of stock, select smaller quantity or contact to us to buy more.");
            return false;
        }
        if(IsNumeric(qlt) && qlt > 0){
            if(!check_qlt) check_qlt = true;
            var ckey = document.getElementById("p_qlty_"+c).name;
            var post_card = new Object();
            post_card.qlt 		= qlt;
            post_card.attributes = str_attributes;	
            post_card.ckey = ckey;
			
            arr_card_obj[arr_card_obj.length] = post_card;
        }
    }
    if(!check_qlt){
        alert('Please select quantity.');
        return false;
    }
    if(pkey != '' && type != ''){
        if(document.getElementById("FloGradient16x16x")) document.getElementById("FloGradient16x16x").style.display = 'block';
		
        $.post(url_base_path__+"shop/cart/shopcart", {
            addcard:add_card,
            arr_addcard:arr_card_obj
        },function(data){
            if(document.getElementById("FloGradient16x16x")) document.getElementById("FloGradient16x16x").style.display = 'none';
            $("#itemsNumber").empty().append(data);
            add2cart = true;
            goto('shop/cart/autodelivery');
        });	
    }
}
function checkShoppingCart(pkey){
    var str_attributes = pkey;
    if(typeof(dataAttributes) == 'object'){
        for(var i = 0; i < dataAttributes.length; i++){
            var obj = dataAttributes[i];
            if(parseInt(obj.display_type, 10) > 0){
                if(typeof(obj.options) == 'undefined' || obj.options.length == 0){
                    continue;	
                }	
            }
            var attribute_id = 'attribute_'+obj.akey;
            if(document.getElementById(attribute_id)){
                str_attributes += __keycode__ + document.getElementById(attribute_id).value;
            }else if(document.getElementsByName(attribute_id+'[]')){
                var attributes_field = document.getElementsByName(attribute_id+'[]');
                for(var j = 0; j < attributes_field.length; j++){
                    if(attributes_field[j].checked == true){
                        str_attributes += __keycode__ + attributes_field[j].value;
                    }	
                }
            }
			
        }
    }
    if(document.getElementById("FloGradient16x16x")) document.getElementById("FloGradient16x16x").style.display = 'block';
    $.post(url_base_path__+"ajax/check_shopping_cart.php", {
        check_shopping_card:'yes',
        attributes:str_attributes
    },function(message){
        if(document.getElementById("FloGradient16x16x")) document.getElementById("FloGradient16x16x").style.display = 'none';
        if(message==''||message=='0')
            $("#add2cart").empty().append('<input type="button" class="button" value="Add to cart" onclick="a2c(\'a\', \''+pkey+'\');" />');
        else
            $("#add2cart").empty().append('<input type="button" class="button" value="Proceed to checkout" onclick="window.location.replace(\'index.php?q=items/cart\');" />');
    });
    add2cart = false;
}
function loadCatName(catName){
    if(catName!="")
        $('#cat_name').css("background", "url("+dir_theme+"images/"+catName+".jpg) no-repeat top right transparent"); 
    else
        $("#cat_name").html('');
}
function login_checkout(form,u,p,msgbox){
    //	$("#"+form).submit(function()
    //	{
    //remove all the class add the messagebox classes and start fading
    $("#"+msgbox).removeClass().addClass('text-info').text('Validating....').fadeIn(1000);
    //check the username exists or not from ajax
    if($('#'+u).val()==''){
        $("#"+msgbox).removeClass('text-info').addClass('text-error').html('Please enter member-ID to login!');
        $('#'+u).focus();
        return false;
    }
    if($('#'+p).val()==''){
        $("#"+msgbox).removeClass('text-info').addClass('text-error').html('Please enter password to login!');
        $('#'+p).focus();
        return false;
    }
    $.post(url_base_path__+"login/checklogin",{
        u:$('#'+u).val(),
        p:$('#'+p).val(),
        rand:Math.random()
    } ,function(data){
        if(data != ''){
            if(data != 'no'){ //if correct login detail
                $("#"+msgbox).fadeTo(200,0.1,function(){  //start fading the messagebox 
                    //add message and change the class of the box and start fading
                    $(this).removeClass('text-error').addClass('text-info').html('Logging in.....').fadeTo(900,1,function(){ 
                        window.location.reload();
                    });
                });
            }else{
                $("#"+msgbox).fadeTo(200,1,function(){ //start fading the messagebox
                    //add message and change the class of the box and start fading
                    $(this).removeClass('text-info').addClass('text-error').html('The member-ID or password incorrect.');
                });		
            }
        }
    });
    return false; //not to post the  form physically
    //	});
    //now call the ajax also focus move from 
    $("#"+password).blur(function(){
        $("#"+form).trigger('submit');
    });
}
function gotoCheckout(){
	
    var schedules = [];
	
    if(typeof(dataOrdersCarts) == 'object' && dataOrdersCarts.length > 0){
        for(var i = 0; i < dataOrdersCarts.length; i++){
            if(dataOrdersCarts[i].items_content != null && dataOrdersCarts[i].items_content != ''){
                if(document.getElementById("delivery_date_"+i) == null) return false;
                if(trim(document.getElementById("delivery_date_"+i).value) == ''){
                    alert('Please select schedule of delivery');
                    document.getElementById("delivery_date_"+i).focus();
                    return false;
                }
            }
            if(document.getElementById("delivery_date_"+i)){
                schedule = new Object();

                schedule.id = document.getElementById("delivery_date_"+i).name;
                schedule.start = document.getElementById("delivery_date_"+i).value;
                schedules[schedules.length] = schedule;		
            }
        }
    }
    $.post(url_base_path__+"shop/cart/autodelivery",{
        gotoCheckout:'yes',
        schedule:schedules
    },function(data){
        if(typeof(data) == 'object'){
            if(data.err == ''){
                window.location = url_base_path__+'shop/cart/checkout';	
            }
        }
    },'json');	
    return false;
}

function valid(f) {
    f.value = f.value.replace(/[^\u00D1\u00F10-9]*/ig,'');
}


 function schedule(key){
        window.location = url_base_path__+'shop/item_details?itemid='+key;
        return false;
 }
 
 
 function find_location(itm_key){
      window.location = url_base_path__+'shop/item_details/findLocation?itmid='+itm_key;
      return false;
  }