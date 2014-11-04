$(function(){
    
    function isValidEmail(email){
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	if(reg.test(email) === false) {
		return "Email format invalid.";
	}
	var AtPos = email.indexOf("@");
	var StopPos = email.lastIndexOf(".");
	if (AtPos === -1 || StopPos === -1)
		return "Email format invalid.";
	return "";
    }
    function keypressInput(){
        $("input[type=text]").each(function(i){
            var target = $(this);
            target.keypress(function(){
                if(target !== ''){
                    target.css("border", '1px solid #D8D8D8');
                }
            });
        });  
    }
    
    $("#signup_submit").click(function(){
        var checkError = false;
        keypressInput();
        if($('#efin_signup').val() === ''){
		$('#efin_signup').css("border", '#66B9E3 1px solid');
		if(checkError === false){
			checkError = true;	
			$("#efin_signup").focus();	
		}
	}
        if($('#email_signup').val() === ''){
		$('#email_signup').css("border", '#66B9E3 1px solid');
		if(checkError === false){
			checkError = true;	
			$("#email_signup").focus();	
		}
	}/*else{
            var msg = isValidEmail($("#email_signup").val());
            if(msg !== ''){
                $('#email_signup').css("border", '#66B9E3 1px solid');
		if(checkError === false){
			checkError = true;	
			$("#email_signup").focus();	
		}
            }
        }
        */
        if($('#phone_signup').val() === ''){
    		$('#phone_signup').css("border", '#66B9E3 1px solid');
    		if(checkError === false){
    			checkError = true;	
    			$("#phone_signup").focus();	
    		}
    	}
        if($('#username_signup').val() === ''){
		$('#username_signup').css("border", '#66B9E3 1px solid');
		if(checkError === false){
			checkError = true;	
			$("#username_signup").focus();	
		}
	}
        if($('#password_signup').val() === ''){
		$('#password_signup').css("border", '#66B9E3 1px solid');
		if(checkError === false){
			checkError = true;	
			$("#password_signup").focus();	
		}
	}
        
        if($('#conf_password_signup').val() === ''){
    		$('#conf_password_signup').css("border", '#66B9E3 1px solid');
    		if(checkError === false){
    			checkError = true;	
    			$("#conf_password_signup").focus();	
    		}
    	}else{
    		/*if($('#conf_password_signup').val() != $('#password_signup').val()){
    			$('#conf_password_signup').css("border", '#ce2030 1px solid');
        		if(checkError === false){
        			checkError = true;	
        			$("#conf_password_signup").focus();	
        		}
    		}*/
    	}
        
        if(checkError === true) return false;
         $.ajax({
            type: 'post',
            url: url_base_path__+'signup/register',
            data:
            {
                save_data:'yes',
                efin:$("#efin_signup").val(),
                email:$("#email_signup").val(),
                primary_phone:$("#phone_signup").val(),
                username:$("#username_signup").val(),
                password:$("#password_signup").val(),
                confpassword:$("#conf_password_signup").val()
            },
            success: function(results) { 
                if(results == 'OK'){
                    goHome();            
                }else{
                   $("#warning").empty().append($('<div class="alert alert-error" style="padding-top:10px;margin-top:10px;border-radius:0px;border-right:0px;border-left:0px;" >'+results+'</div>')); 
                }
            }
        }); 
        
        return false;      
    }); 
    
    $("#btnSubmitLogin").click(function(){
        var checkField  = false;
    	var efin =  $("#efin_signin").val();
    	var username =  $("#username_signin").val();
    	var password =  $("#password_signin").val();
    	/*if(efin  === ""){
    		if(checkField === false){
    			checkField = true;
    		}
    	}*/
    	if(username  ===  ""){
    		if(checkField === false){
    			checkField  = true;
    		}
    	}
    	if(password  === ""){
    		if(checkField ===  false){
    			checkField  = true;
    		}
    	}
    	
    	if(checkField === true) return false;
    	
    	$.post(url_base_path__ + "login/checklogin",
    	{
    		e:efin,u:username,p:password
    	},
    	function(data){
    		if(data == 'OK'){
    			 goHome();
    		}else if(data == 'Reject'){
                $("#warninglogin").empty().append($('<div class="alert alert-error" style="padding-top:10px;margin-top:10px;border-radius:0px;border-right:0px;border-left:0px;" >Account Temporarily Deactivated.</div>'));
            }
            else{
                    $("#warninglogin").empty().append($('<div class="alert alert-error" style="padding-top:10px;margin-top:10px;border-radius:0px;border-right:0px;border-left:0px;" >The EFIN or name or password incorrect.</div>'));  
                }
    	});
       return false;
    });

    
});
  /*  
$("#contact_submit").click(function(){
    var checkError = false;
    keypressInput();
    if($('#efin_signup').val() === ''){
	$('#efin_signup').css("border", '#66B9E3 1px solid');
	if(checkError === false){
		checkError = true;	
		$("#efin_signup").focus();	
	}
}
    if($('#email_signup').val() === ''){
	$('#email_signup').css("border", '#66B9E3 1px solid');
	if(checkError === false){
		checkError = true;	
		$("#email_signup").focus();	
	}
}
    if($('#phone_signup').val() === ''){
		$('#phone_signup').css("border", '#66B9E3 1px solid');
		if(checkError === false){
			checkError = true;	
			$("#phone_signup").focus();	
		}
	}
    if($('#username_signup').val() === ''){
	$('#username_signup').css("border", '#66B9E3 1px solid');
	if(checkError === false){
		checkError = true;	
		$("#username_signup").focus();	
	}
}
    if($('#password_signup').val() === ''){
	$('#password_signup').css("border", '#66B9E3 1px solid');
	if(checkError === false){
		checkError = true;	
		$("#password_signup").focus();	
	}
}
    
    if($('#conf_password_signup').val() === ''){
		$('#conf_password_signup').css("border", '#66B9E3 1px solid');
		if(checkError === false){
			checkError = true;	
			$("#conf_password_signup").focus();	
		}
	}else{
		
	}
    
    if(checkError === true) return false;
     $.ajax({
        type: 'post',
        url: url_base_path__+'signup/register',
        data:
        {
            save_data:'yes',
            efin:$("#efin_signup").val(),
            email:$("#email_signup").val(),
            primary_phone:$("#phone_signup").val(),
            username:$("#username_signup").val(),
            password:$("#password_signup").val(),
            confpassword:$("#conf_password_signup").val()
        },
        success: function(results) { 
            if(results == 'OK'){
                goHome();            
            }else{
               $("#warning").empty().append($('<div class="alert alert-error" style="padding-top:10px;margin-top:10px;border-radius:0px;border-right:0px;border-left:0px;" >'+results+'</div>')); 
            }
        }
    }); 
    
    return false;      
}); 

*/
