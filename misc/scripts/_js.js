/*
typeof [JavaScript operator]
typeof returns one of the following strings:
    number
    string
    boolean
    object
    function
    undefined 

*/
var dir_theme = 'themes/purple/';
function trim(s){ 
    var l=0; var r=s.length -1; 
    while(l < s.length && s[l] == ' ') 
         l++;  
    while(r > l && s[r] == ' ') 
         r-=1;
    return s.substring(l, r+1); 
}

function form_input_is_numeric(input){
	return !isNaN(trim(input));
}
function function_exists( function_name ) {    
    if (typeof function_name == 'string'){   
        return (typeof window[function_name] == 'function');   
    } else{   
        return (function_name instanceof Function);   
    }   
} 
function MM_findObj(n, d) { //v3.0
	var p,i,x; if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
	d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}
function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function isValidZip (zip,country){
	if(!zip) return "Please enter ZIP code.";
	var zipRegExp = "";
	if(!country) format = 'US';
	switch(country){
		case'US': zipRegExp = /^\d{5}$|^\d{5}-\d{4}$/; break;
		case'CA': zipRegExp = /^[A-Z]\d[A-Z] \d[A-Z]\d$/; break;
		case'FR': zipRegExp = /^\d{5}$/; break;
		case'Monaco':zipRegExp = /^(MC-)\d{5}$/; break;
	}
	if(zipRegExp!="")
		if(!zipRegExp.test(zip)){
			return "ZIP code is not valid.";
		}
	return "";
}
function isValidEmail(email){
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	if(reg.test(email) == false) {
		return "Email format invalid.";
	}
	var AtPos = email.indexOf("@");
	var StopPos = email.lastIndexOf(".");
	if (AtPos == -1 || StopPos == -1)
		return "Email format invalid.";
	return "";
}
function validatePhone(phoneField) {
    var num = phoneField.value.replace(/[^\d]/g,'');
    if(num.length != 10) {
		return false;                 
    } else {
		phoneField.value = num.substring(0,3) + "-" + num.substring(3, 6) + "-" + num.substring(6);
    }
}
function truebody(){
	return(!window.opera&&document.compatMode&&document.compatMode!="BackCompat")?document.documentElement:document.body
}
function copyObject(oldObj){
	var newObj = new Object();
	for(var propertyName in oldObj){
		newObj[propertyName] = oldObj[propertyName];	
	}
	return newObj;
}
function fadeIn(element, opacity) {
	var reduceOpacityBy = 5;
	var rate = 40;	// 15 fps
	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}
		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}
	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}
function Show_Dashboard(element, opacity, max_opacity) {
	var reduceOpacityBy = 5;
	var rate = 40;	// 15 fps
	if (opacity < max_opacity) {
		opacity += reduceOpacityBy;
		if (opacity > max_opacity) {
			opacity = max_opacity;
		}
		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}
	if (opacity < max_opacity) {
		setTimeout(function () {
			Show_Dashboard(element, opacity, max_opacity);
		}, rate);
	}
}
function setOpacity(element,opacity){
	/*if (element.filters) {
		try {
			element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
		} catch (e) {
			element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
		}
	} else {
		element.style.opacity = opacity / 100;
	}
	element.style.display = "block";*/
}
function Hide_Dashboard(element, opacity, min_opacity) {
	var reduceOpacityBy = 5;
	var rate = 40;	// 15 fps
	if(min_opacity < 0) min_opacity = 0;
	if (opacity > 0) {
		opacity -= reduceOpacityBy;
		if (opacity < min_opacity) {
			opacity = min_opacity;
		}
		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
		if(opacity == 0){
			return false;
		}
	}
	if (opacity > 0) {
		setTimeout(function () {
			Hide_Dashboard(element, opacity, min_opacity);
		}, rate);
	}
}
function checkUsername(strng){
	if ((strng.length < 4) || (strng.length > 20)) {
    	return "The username is the wrong length. Username must be from 4 to 20 characters.";
	}
	var illegalChars = /\W/;
    if (illegalChars.test(strng)) {
       return "The username contains illegal characters.";
    }
	return '';
}
function passwordStrength(objpass) {
//	var strength = document.getElementById('strength');
	var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	var pwd = objpass;
	if (pwd.value.length==0) {
		return false;
	} else if (false == enoughRegex.test(pwd.value)) {
		return false;
	} else if (strongRegex.test(pwd.value)) {
	//	strength.innerHTML = '<span style="color:green">Strong!</span>';
	} else if (mediumRegex.test(pwd.value)) {
	//	strength.innerHTML = '<span style="color:orange">Medium!</span>';
	} else { 
	//	strength.innerHTML = '<span style="color:red">Weak!</span>';
	}
	return true;
}
/*function login_(form, u, p, msgbox) {
    $('#' + u).attr("disabled", "disabled");
    $('#' + p).attr("disabled", "disabled");
    $("#" + msgbox).removeClass().addClass('messagebox').text('Validating....').fadeIn(1000);
    if ($('#' + u).val() == '') {
        $("#" + msgbox).html('Please enter username to login!').addClass('error').fadeTo(900, 1);
        $('#' + u).focus();
        $('#' + u).removeAttr("disabled");
        $('#' + p).removeAttr("disabled");
        //$('#' + efin).removeAttr("disabled");
        return false;
    }
    if ($('#' + p).val() == '') {
        $("#" + msgbox).html('Please enter password to login!').addClass('error').fadeTo(900, 1);
        $('#' + p).focus();
        $('#' + u).removeAttr("disabled");
        //$('#' + efin).removeAttr("disabled");
        return false;
    }*/
    /*if ($('#' + efin).val() == '') {
        $("#" + msgbox).html('Please enter password to login!').addClass('error').fadeTo(900, 1);
        $('#' + efin).focus();
        $('#' + u).removeAttr("disabled");
        $('#' + p).removeAttr("disabled");
        return false;
    }*/
    /*$.post(url_base_path__ + "login/checklogin", {u: $('#' + u).val(), p: $('#' + p).val()}, function(data) {
        if (data != '') {
            if (data != 'no') {
                goHome();
                $("#" + msgbox).fadeTo(200, 0.1, function() {
                    $(this).html('Logging in.....').addClass('messageboxok').fadeTo(900, 1, function() { 
                       
                    });
                });
            } else {     
                $("#" + msgbox).fadeTo(200, 0.1, function() {
                    $(this).html('The name or password incorrect.').addClass('error').fadeTo(900, 1);
                    $('#' + u).removeAttr("disabled");
                    $('#' + p).removeAttr("disabled");
                    //$('#' + efin).removeAttr("disabled");
                });
            }
        }
    });
    $('#' + u).removeAttr("disabled");
    $('#' + p).removeAttr("disabled");
    return false; 
}*/
function number_format(number){
	var str="";
	number=""+number+"";
	for(i=0;i<number.length;i++){
		if(i!=number.length-1){
			if((i+1)%3==0){
				end=number.substr(number.length-(i+1),3);
				if(i==2)str=end;
				else str=end+","+str;
			}
		}else {
			if((i+1)%3==0){
				end=number.substr(0,3);
				if(i==2)str=end;
				else str=end+","+str;
			}else{
				mode=number.length%3;
				end=number.substr(0,mode);
				if(i==mode-1)str=end;
				else str=end+","+str;
			}
		}
	}
	return str;
}
function formatAsMoney (value, decimal){
	if(typeof(decimal) == 'undefined' || decimal == null) decimal = 2;
	var anynum=eval(value);
   	var divider = '1';
	for(var i = 0; i < decimal; i++){
		divider += '0';	
	}
	divider = parseFloat(divider);	
  	var workNum=Math.abs((Math.round(anynum*divider)/divider));
   	var workStr = "" + workNum;
	if(workStr.indexOf(".") == -1){
		workStr += ".";
	}
   	var dStr = workStr.substr(0, workStr.indexOf("."));
	var dNum = dStr - 0;
   	var pStr = workStr.substr(workStr.indexOf("."));
   	while (pStr.length - 1 < decimal){
		pStr += "0";
	}
  	if(pStr == '.') pStr ='';
	if (dNum >= 1000) {
		dLen = dStr.length;
		dStr = parseInt(dNum/1000, 10) + "," + dStr.substring(dLen - 3, dLen);
	} 
	if (dNum >= 1000000) {
	  	dLen = dStr.length;
	  	dStr = parseInt(dNum/1000000, 10) + "," + dStr.substring(dLen - 6, dLen);
	}
   	retval = dStr + pStr;
   	if (anynum<0){
		retval = "(" + retval + ")";
	}
   	return retval;
}
function findPosX(obj) {
    /*var curleft = 0;
    if (obj.offsetParent) {
        while (1) {
            curleft += parseInt(obj.offsetLeft, 10);
            if (!obj.offsetParent) {
                break;
            }
            obj=obj.offsetParent;
        }
    } else if (obj.x) {
        curleft += parseInt(obj.x, 10);
    }
    return curleft;*/
}
function findPosY(obj) {
    /*var curtop = 0;
    if (obj.offsetParent) {
        while (1) {
            curtop += parseInt(obj.offsetTop, 10);
            if (!obj.offsetParent) {
                break;
            }
            obj=obj.offsetParent;
        }
    } else if (obj.y) {
        curtop += parseInt(obj.y, 10);
    }
    return curtop;*/
}
function addEventHandler(element, type, func) { //unfortunate hack to deal with Internet Explorer's horrible DOM event model <iehack>
	if(element.addEventListener) {
		element.addEventListener(type,func,false);
	}else if (element.attachEvent) {
		element.attachEvent('on'+type,func);
	}
}
function removeEventHandler(element, type, func) { //unfortunate hack to deal with Internet Explorer's horrible DOM event model <iehack>
	if(element.removeEventListener) {
		element.removeEventListener(type,func,false);
	}else if (element.attachEvent) {
		element.detachEvent('on'+type,func);
	}
}
function ShowLoadingObj(){
	if(typeof(arguments[0]) == 'object'){
		var obj_arguments = arguments[0];
		if(typeof(obj_arguments.obj) == 'undefined') return;
		var obj = obj_arguments.obj;
		setOpacity(obj, 50);
		var width = $(obj).width();
		var height = $(obj).height();			
		var left = findPosX(obj);
		var top = findPosY(obj);
		var IDObjLoading = 'loadingContentAjax';
		if(typeof(obj_arguments.height) != "undefined" && obj_arguments.height > 0) height = obj_arguments.height;
		if(typeof(obj_arguments.width) != "undefined" && obj_arguments.width > 0) width = obj_arguments.width;
		var image__ = 'loadinfo2.gif';
		if(typeof(obj_arguments.image) != "undefined" && obj_arguments.image != '') image__ = obj_arguments.image;
		var contentObjLoading = '';
		contentObjLoading += '<table cellpadding="0" cellspacing="0" border="0" width="100%">';
		contentObjLoading += '	<tr><td height="'+height+'px" align="center" valign="middle"><table cellpadding="0" cellspacing="0" border="0"><tr><td align="left" valign="middle" style="padding-right:5px"><img src="'+dir_theme+'images/'+image__+'" border="0" /></td><td align="left" valign="middle" style="color:#949fa7">Please wait...</td></tr></table></td></tr>';
		contentObjLoading += '</table>';
		var newObj;
		if(document.getElementById(IDObjLoading)){
			newObj = document.getElementById(IDObjLoading);	
		}else{
			newObj = document.createElement("div");
			window.document.body.appendChild(newObj);
			newObj.id = IDObjLoading;
		}
		newObj.setAttribute("style", 'width:'+width+'px; left:'+left+'px; top:'+top+'px; position:absolute; overflow:hidden; cursor:wait; z-index:999; display:none');
		$(newObj).empty().append(contentObjLoading);
		$(newObj).show();
	}
}
function HideLoadingObj(){
	var IDObjLoading = 'loadingContentAjax';
	if(document.getElementById(IDObjLoading)){
		document.getElementById(IDObjLoading).style.display = 'none';
	}
	if(typeof(arguments[0]) == 'object'){
		setOpacity(arguments[0], 100);	
	}
}
function isNumberFloatKey(evt){	//onkeypress="return isNumberFloatKey(event)"
	if(typeof(evt) == 'undefined'){
		if(document.all) evt = event;	
	}
 	var charCode = (evt.which) ? evt.which : evt.keyCode;
 	if (charCode > 31 && ((charCode < 48 && charCode != 46) || charCode > 57)) return false;
 	return true;
}
function isNumberIntKey(evt){
	if(typeof(evt) == 'undefined'){
		if(document.all) evt = event;	
	}
 	var charCode = (evt.which) ? evt.which : evt.keyCode;
 	if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
 	return true;
}
function onkeydownInputText(obj){
	$(obj).css("background", '#EDEDED');
	if(document.getElementById(obj.id+"_label")){
		document.getElementById(obj.id+"_label").style.display = 'none';	
	}	
}
function onfocusInputText(obj){
	if(obj.value == ''){
		showlabelfocus(obj.id+"_label");
		showTooltipRequi(obj);
	}
}
function onfocusInputText2(obj){
	if(obj.value == ''){
            showlabelfocus(obj.id+"_label");
	}
}
function showlabelfocus(textid){
	if(document.getElementById(textid)){
		document.getElementById(textid).className = 'placeholder focus';
		document.getElementById(textid).style.display = 'block';	
	}		
}
function hidelabelfocus(textid){
	if(document.getElementById(textid)){
		document.getElementById(textid).className = 'placeholder';
		document.getElementById(textid).style.display = 'block';
	}	
}
function clearForms(){
	var input_text = document.getElementsByTagName('input');
	for(var i = 0; i < input_text.length; i++){
		if(input_text[i].type == "text" || input_text[i].type == 'password' || input_text[i].type == 'tel'){
			if(input_text[i].value != ''){
				onkeydownInputText(input_text[i]);	
			}	
		}	
	}			
	setTimeout(function () {
		clearForms();
	}, 100);
}
function deleteAllCookies() {
    var cookies = document.cookie.split(";");
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie =  name+"=;expires=Thu, 01-Jan-1970 00:00:01 GMT";
    }
}
function ConvertToHTML(str){
	if(str != '' && str != null){
		str = str.replace(/\r\n/gi, "<br>");
		str = str.replace(/\n\r/gi, "<br>");
		str = str.replace(/\n/gi, "<br>");
		str = str.replace(/\r/gi, "<br>");
		str = str.replace(/"/gi, '&quot;');
		str = str.replace(/'/gi, '&#039');			
	}
	return str;
}
function ConvertToTest(str){
	if(str != '' && str != null){
		str = str.replace(/<br>/gi, "\n");
		str = html_entity_decode(str);
	}	
	return str;
}
function html_entity_decode (string, quote_style) {
    var hash_map = {}, symbol = '',tmp_str = '',entity = '';    tmp_str = string.toString();
    if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
        return false;
    } 
    delete(hash_map['&']);
    hash_map['&'] = '&amp;'; 
    for (symbol in hash_map) {
        entity = hash_map[symbol];
        tmp_str = tmp_str.split(entity).join(symbol);
    }    tmp_str = tmp_str.split('&#039;').join("'");
    return tmp_str;
}
function get_html_translation_table (table, quote_style) {
    var entities = {},
        hash_map = {},        decimal;
    var constMappingTable = {},
        constMappingQuoteStyle = {};
    var useTable = {},
        useQuoteStyle = {}; 
    // Translate arguments
    constMappingTable[0] = 'HTML_SPECIALCHARS';
    constMappingTable[1] = 'HTML_ENTITIES';
    constMappingQuoteStyle[0] = 'ENT_NOQUOTES';    constMappingQuoteStyle[2] = 'ENT_COMPAT';
    constMappingQuoteStyle[3] = 'ENT_QUOTES';
 
    useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
    useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT'; 
    if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
        throw new Error("Table: " + useTable + ' not supported');
    } 
    entities['38'] = '&amp;';
    if (useTable === 'HTML_ENTITIES') {
        entities['160'] = '&nbsp;';
        entities['161'] = '&iexcl;';        entities['162'] = '&cent;';
        entities['163'] = '&pound;';
        entities['164'] = '&curren;';
        entities['165'] = '&yen;';
        entities['166'] = '&brvbar;';        entities['167'] = '&sect;';
        entities['168'] = '&uml;';
        entities['169'] = '&copy;';
        entities['170'] = '&ordf;';
        entities['171'] = '&laquo;';        entities['172'] = '&not;';
        entities['173'] = '&shy;';
        entities['174'] = '&reg;';
        entities['175'] = '&macr;';
        entities['176'] = '&deg;';        entities['177'] = '&plusmn;';
        entities['178'] = '&sup2;';
        entities['179'] = '&sup3;';
        entities['180'] = '&acute;';
        entities['181'] = '&micro;';        entities['182'] = '&para;';
        entities['183'] = '&middot;';
        entities['184'] = '&cedil;';
        entities['185'] = '&sup1;';
        entities['186'] = '&ordm;';        entities['187'] = '&raquo;';
        entities['188'] = '&frac14;';
        entities['189'] = '&frac12;';
        entities['190'] = '&frac34;';
        entities['191'] = '&iquest;';        entities['192'] = '&Agrave;';
        entities['193'] = '&Aacute;';
        entities['194'] = '&Acirc;';
        entities['195'] = '&Atilde;';
        entities['196'] = '&Auml;';        entities['197'] = '&Aring;';
        entities['198'] = '&AElig;';
        entities['199'] = '&Ccedil;';
        entities['200'] = '&Egrave;';
        entities['201'] = '&Eacute;';        entities['202'] = '&Ecirc;';
        entities['203'] = '&Euml;';
        entities['204'] = '&Igrave;';
        entities['205'] = '&Iacute;';
        entities['206'] = '&Icirc;';        entities['207'] = '&Iuml;';
        entities['208'] = '&ETH;';
        entities['209'] = '&Ntilde;';
        entities['210'] = '&Ograve;';
        entities['211'] = '&Oacute;';        entities['212'] = '&Ocirc;';
        entities['213'] = '&Otilde;';
        entities['214'] = '&Ouml;';
        entities['215'] = '&times;';
        entities['216'] = '&Oslash;';        entities['217'] = '&Ugrave;';
        entities['218'] = '&Uacute;';
        entities['219'] = '&Ucirc;';
        entities['220'] = '&Uuml;';
        entities['221'] = '&Yacute;';        entities['222'] = '&THORN;';
        entities['223'] = '&szlig;';
        entities['224'] = '&agrave;';
        entities['225'] = '&aacute;';
        entities['226'] = '&acirc;';        entities['227'] = '&atilde;';
        entities['228'] = '&auml;';
        entities['229'] = '&aring;';
        entities['230'] = '&aelig;';
        entities['231'] = '&ccedil;';        entities['232'] = '&egrave;';
        entities['233'] = '&eacute;';
        entities['234'] = '&ecirc;';
        entities['235'] = '&euml;';
        entities['236'] = '&igrave;';        entities['237'] = '&iacute;';
        entities['238'] = '&icirc;';
        entities['239'] = '&iuml;';
        entities['240'] = '&eth;';
        entities['241'] = '&ntilde;';        entities['242'] = '&ograve;';
        entities['243'] = '&oacute;';
        entities['244'] = '&ocirc;';
        entities['245'] = '&otilde;';
        entities['246'] = '&ouml;';        entities['247'] = '&divide;';
        entities['248'] = '&oslash;';
        entities['249'] = '&ugrave;';
        entities['250'] = '&uacute;';
        entities['251'] = '&ucirc;';        entities['252'] = '&uuml;';
        entities['253'] = '&yacute;';
        entities['254'] = '&thorn;';
        entities['255'] = '&yuml;';
    } 
    if (useQuoteStyle !== 'ENT_NOQUOTES') {
        entities['34'] = '&quot;';
    }
    if (useQuoteStyle === 'ENT_QUOTES') {        entities['39'] = '&#39;';
    }
    entities['60'] = '&lt;';
    entities['62'] = '&gt;';
  
    // ascii decimals to real symbols
    for (decimal in entities) {
        if (entities.hasOwnProperty(decimal)) {
            hash_map[String.fromCharCode(decimal)] = entities[decimal];        }
    }
    return hash_map;
}
function showTooltipRequi(obj){
	if(document.getElementById("coherent_bubble_node")){
		var top = findPosY(obj);
		var left = findPosX(obj);
		var width = parseInt($(obj).width(), 10);
		document.getElementById("coherent_bubble_node").style.display = 'block';
		document.getElementById("coherent_bubble_node").style.top = (top - 58) + 'px';
		document.getElementById("coherent_bubble_node").style.left = (left + (width/2) - 97) + 'px';	
	}	
}
function hideTooltipRequi(){
	if(document.getElementById("coherent_bubble_node")){
		document.getElementById("coherent_bubble_node").style.display = 'none';	
	}	
}
function sprintfInt(format, number){
	if(format == 1) return number;
	var value = 1;
	for(var i = 1; i < format; i++){
		value = value*10;	
	}
	if(number >= value) return number;
	var test = "0" + number;
	while(test.length < format){
		test = "0" + test;	
	}
	return test;
}
function format_dem(max_format, number){
	var test = max_format + "";
	return sprintfInt(test.length, number);
}
function goHome(){
	if(typeof(url_server__) == 'string'){
		window.location = url_server__;
		
	}else{
		window.location = '?';		
	}
	return false;
}
function goto(page){
	if(page != ''){
		if(page[0] == '/') page = page.substr(1);
	}
	if(typeof(url_base_path__) == 'string')
		window.location = url_base_path__ + page;
	else
		window.location = 'index.php?q=' + page;
	return false;
}
function pager_new(hien_thi, arr_ads, iPage, func_name, pager_name){
	var output = '';
	var num = arr_ads.length;
	var page = iPage*hien_thi;
	
	var arr = [];
	var so = Math.round(num / hien_thi);
	if (so*hien_thi < num){so += 1;}
	if(so <= 7){
		for (var i = 1; i < so+1; i++){ arr[arr.length] = i; }
	}else {
		if(iPage <= 4){
			for (var i = 1; i < 8; i++){ arr[arr.length] = i; }
		}else {
			if(iPage >= so-3){
				for(var i = so-6; i<=so; i++){ arr[arr.length] = i; }
			}else {
				for(var i=iPage-3; i<=iPage+3; i++){ arr[arr.length] = i;}
			}
		}
	}
	if(iPage <= 1){
	//	output += "Previous";
		for(var x = 0; x < arr.length; x++){		
			var pa = arr[x];
			if(iPage != pa){
				output += "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\" >"+pa+"</a>";
			}else {
				output += "&nbsp;&nbsp;"+pa;
			}
		}
		var last = arr[arr.length-1];
		if(so > 7) output += "&nbsp;&nbsp;...&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+so+");\" >"+so+"</a>";		
//			$output .= "&nbsp;&nbsp;<font color='#696969'>of</font>&nbsp;&nbsp;$last";
//		if(num > page){
//			var p = iPage + 1;
//		  		$last = $arr[count($arr)-1];
//			output += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+p+");\" >Next</a>";
//		  		$output .= "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"loadAdsList('$url&page=$last');\" ><img align='absmiddle' src='themes/$_theme/images/arrow_first.gif' border='0'></a>";
//		}else {
//			output += "&nbsp;&nbsp;Next";
//		}
	}else if (iPage > 1) {
		var p = iPage-1;
		var p2 = iPage + 1;
		var last = arr[arr.length-1];
		var bol = false;

		if(num > page){
//		  		$output .= "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"loadAdsList('$url&page=1');\" ><img align='absmiddle' src='themes/$_theme/images/arrow_last.gif' border='0'></a>";
		//	output += "<a href='javascript:void(0);' onclick=\""+func_name+"("+p+");\" >Previous</a>";
			if(so > 7 && iPage > 4) output += "&nbsp;&nbsp;...";
			for(var x = 0; x < arr.length; x++){		
				var pa = arr[x];
				if(iPage != pa){			  		    
					if(bol == false){
						output += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\">"+pa+"</a>";
						bol = true;
					}else {
						output += "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\" >"+pa+"</a>";
					}
				}else {
					if(bol == false){
						bol = true;
						output += "&nbsp;&nbsp;"+pa;
					}else {
						output += "&nbsp;&nbsp;|&nbsp;&nbsp;"+pa;
					}
				}
			}
			if(so > 7) output += "&nbsp;&nbsp;...&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+so+");\" >"+so+"</a>";
//			    $output .= "&nbsp;&nbsp;<font color='#696969'>of</font>&nbsp;&nbsp;$last";
		//	output += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+p2+");\" >Next</a>";
//		  		$output .= "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"loadAdsList('$url&page=$last');\" ><img align='absmiddle' src='themes/$_theme/images/arrow_first.gif' border='0'></a>";
		}else {
//		  		$output .= "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"loadAdsList('$url&page=1');\" ><img align='absmiddle' src='themes/$_theme/images/arrow_last.gif' border='0'></a>";
		//	output += "<a href='javascript:void(0);' onclick=\""+func_name+"("+p+");\" >Previous</a>";
			if(so > 7 && iPage > 4) output += "&nbsp;&nbsp;...";
			for(var x = 0; x < arr.length; x++){		
				var pa = arr[x];
				if(iPage != pa){ 
					if(bol == false){
						bol = true;
						output += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\" >"+pa+"</a>"; 
					}else
						 output += "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\">"+pa+"</a>"; 
				}
				else { 
					if(bol == false){
						bol = true;
						output += "&nbsp;&nbsp;"+pa; 
					}else 
						 output += "&nbsp;&nbsp;|&nbsp;&nbsp;"+pa; 
				}
			}
		//	output += "&nbsp;&nbsp;Next";
		}
	} 
	if(output != ''){ 
		for(var i = 0; i < pager_name.length; i++){
			$("#"+pager_name[i]).empty().append("Pagers: "+output);
		}  
	}
}
function pagerAjax_1(hien_thi, num, iPage, func_name, pager_name, colum, sort_list){
	var output = '';
	var page = iPage*hien_thi;
	
	var arr = [];
	var so = Math.round(num / hien_thi);
	if (so*hien_thi < num){so += 1;}
	if(so <= 7){
		for (var i = 1; i < so+1; i++){ arr[arr.length] = i; }
	}else {
		if(iPage <= 4){
			for (var i = 1; i < 8; i++){ arr[arr.length] = i; }
		}else {
			if(iPage >= so-3){
				for(var i = so-6; i<=so; i++){ arr[arr.length] = i; }
			}else {
				for(var i=iPage-3; i<=iPage+3; i++){ arr[arr.length] = i;}
			}
		}
	}
	if(iPage <= 1){
		for(var x = 0; x < arr.length; x++){		
			var pa = arr[x];
			if(iPage != pa){
				output += "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+','+colum+','+sort_list+");\" >"+pa+"</a>";
			}else {
				output += "&nbsp;&nbsp;"+pa;
			}
		}
		var last = arr[arr.length-1];
		if(so > 7) output += "&nbsp;&nbsp;...&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+so+','+colum+','+sort_list+");\" >"+so+"</a>";
	}else if (iPage > 1) {
		var p = iPage-1;
		var p2 = iPage + 1;
		var last = arr[arr.length-1];
		var bol = false;

		if(num > page){
			if(so > 7 && iPage > 4) output += "&nbsp;&nbsp;...";
			for(var x = 0; x < arr.length; x++){		
				var pa = arr[x];
				if(iPage != pa){			  		    
					if(bol == false){
						output += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+','+colum+','+sort_list+");\">"+pa+"</a>";
						bol = true;
					}else {
						output += "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+','+colum+','+sort_list+");\" >"+pa+"</a>";
					}
				}else {
					if(bol == false){
						bol = true;
						output += "&nbsp;&nbsp;"+pa;
					}else {
						output += "&nbsp;&nbsp;|&nbsp;&nbsp;"+pa;
					}
				}
			}
			if(so > 7) output += "&nbsp;&nbsp;...&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+so+','+colum+','+sort_list+");\" >"+so+"</a>";
		}else {
			if(so > 7 && iPage > 4) output += "&nbsp;&nbsp;...";
			for(var x = 0; x < arr.length; x++){		
				var pa = arr[x];
				if(iPage != pa){ 
					if(bol == false){
						bol = true;
						output += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+','+colum+','+sort_list+");\" >"+pa+"</a>"; 
					}else
						 output += "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+','+colum+','+sort_list+");\">"+pa+"</a>"; 
				}
				else { 
					if(bol == false){
						bol = true;
						output += "&nbsp;&nbsp;"+pa; 
					}else 
						 output += "&nbsp;&nbsp;|&nbsp;&nbsp;"+pa; 
				}
			}
		}
	} 
	if(output != ''){ 
		for(var i = 0; i < pager_name.length; i++){
			$("#"+pager_name[i]).empty().append("Pagers: "+output);
		}  
	}
}
function pagerAjax(hien_thi, num, iPage, func_name, pager_name){
	var output = '';
	var page = iPage*hien_thi;
	
	var arr = [];
	var so = Math.round(num / hien_thi);
	if (so*hien_thi < num){so += 1;}
	if(so <= 7){
		for (var i = 1; i < so+1; i++){ arr[arr.length] = i; }
	}else {
		if(iPage <= 4){
			for (var i = 1; i < 8; i++){ arr[arr.length] = i; }
		}else {
			if(iPage >= so-3){
				for(var i = so-6; i<=so; i++){ arr[arr.length] = i; }
			}else {
				for(var i=iPage-3; i<=iPage+3; i++){ arr[arr.length] = i;}
			}
		}
	}
	if(iPage <= 1){
		for(var x = 0; x < arr.length; x++){		
			var pa = arr[x];
			if(iPage != pa){
				output += "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\" >"+pa+"</a>";
			}else {
				output += "&nbsp;&nbsp;"+pa;
			}
		}
		var last = arr[arr.length-1];
		if(so > 7) output += "&nbsp;&nbsp;...&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+so+");\" >"+so+"</a>";
	}else if (iPage > 1) {
		var p = iPage-1;
		var p2 = iPage + 1;
		var last = arr[arr.length-1];
		var bol = false;

		if(num > page){
			if(so > 7 && iPage > 4) output += "&nbsp;&nbsp;...";
			for(var x = 0; x < arr.length; x++){		
				var pa = arr[x];
				if(iPage != pa){			  		    
					if(bol == false){
						output += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\">"+pa+"</a>";
						bol = true;
					}else {
						output += "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\" >"+pa+"</a>";
					}
				}else {
					if(bol == false){
						bol = true;
						output += "&nbsp;&nbsp;"+pa;
					}else {
						output += "&nbsp;&nbsp;|&nbsp;&nbsp;"+pa;
					}
				}
			}
			if(so > 7) output += "&nbsp;&nbsp;...&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+so+");\" >"+so+"</a>";
		}else {
			if(so > 7 && iPage > 4) output += "&nbsp;&nbsp;...";
			for(var x = 0; x < arr.length; x++){		
				var pa = arr[x];
				if(iPage != pa){ 
					if(bol == false){
						bol = true;
						output += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\" >"+pa+"</a>"; 
					}else
						 output += "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\""+func_name+"("+pa+");\">"+pa+"</a>"; 
				}
				else { 
					if(bol == false){
						bol = true;
						output += "&nbsp;&nbsp;"+pa; 
					}else 
						 output += "&nbsp;&nbsp;|&nbsp;&nbsp;"+pa; 
				}
			}
		}
	} 
	if(output != ''){ 
		for(var i = 0; i < pager_name.length; i++){
			$("#"+pager_name[i]).empty().append("Pagers: "+output);
		}  
	}
}
function showbox(divID){
	var width_ = $("#"+divID).width();
	var height_ = $("#"+divID).height();
	document.getElementById(divID).style.left = Math.round((truebody().clientWidth - width_)/2) + 'px';
	document.getElementById(divID).style.top = Math.round(((truebody().clientHeight - height_)/2<0)?0:(truebody().clientHeight - height_)/2) + 'px';
	$("#"+divID).show();
	$("#fancy_box").show();		
}
function closeBox(divID){
	$("#"+divID).hide();
	hideTooltipRequi();
	$("#fancy_box").hide();	
}
function gotoFrame(){
	var iframe_ = document.getElementsByTagName('iframe');
	if(iframe_.length > 0){
	//	ShowLoadingObj({obj:document.getElementById("content_load"), image:"Loader-FloGradient16x16x.gif"});
		$('iframe').bind('load', function(){
			var doc = this.contentDocument || this.contentWindow.document;
			var jDoc = $(doc);
			//jDoc = $('body', doc);
			var frame = $(this);
			var destWidth = jDoc.width();
			var destHeight = jDoc.height();
			frame.unbind('load');
		//	frame.width(destWidth);
			setTimeout(function () {
				frame.height(destHeight);
			}, 100);	
		//	HideLoadingObj(document.getElementById("content_load"));
		});		
	}
}
function keypress(e,i,obj_text){
	var st = '';
	st = remove_char(obj_text.value);
	if (window.event){
		keypressed = window.event.keyCode; //IE
	}else{ 
		keypressed = e.which; //NON-IE, Standard
	}
	if(keypressed < 48 || keypressed > 57 && keypressed < 96 || keypressed > 105){
		if(keypressed == 8){
			st = st.substring(0,st.length - 1);
			obj_text.value = backspace(st) + 1;
			return;
		}
		return false;
	}
	str = obj_text.value;
	if(IsNumeric(remove_char(str))){
		obj_text.value = format_data(str,i);
	}else{
		obj_text.value = '';
	}
}
function format_data(value,i){
	var str = '';
	var length = 0;
	str = remove_char(value);
	if(i == 0){
		length = str.length;
	}else{
		length = str.length - 1;
	}
	if(str.charAt(0) == 0) return str;
	if(str.charAt(0) != 1) {
		if(length <= 2) return str;
		if(length > 2 && length <= 6){
			str = str.substring(0,3) + "-" + str.substring(3,str.length);	
		}
		if(length > 6 && length <= 9){
			str = "(" + str.substring(0,3) + ") " + str.substring(3,6) + "-" + str.substring(6,str.length);	
		}
		if(length > 9){
			return str;
		}
	}else{
		if(length == 1) str += " ";
		if(length >1 && length <=3){
			str = "1 " + str.substring(1,str.length);	
		}
		if(length >3 && length <=6){
			str = "1 (" + str.substring(1,4) + ") " + str.substring(4,str.length);	
		}
		if(length >6 && length <=10){
			str = "1 (" + str.substring(1,4) + ") " + str.substring(4,7) + "-" + str.substring(7,str.length);	
		}
		if(length > 10){
			return str;
		}
	}
	return str;
}
function backspace(value){
	var str = '';
	var length = 0;
	str = remove_char(value);
	length = str.length;
	if(str.charAt(0) == 0) return str;
	if(str.charAt(0) != 1) {
		if(length <= 1) return str;
		if(length > 3 && length < 8){
			str = str.substring(0,3) + "-" + str.substring(3,str.length);	
		}
		if(length >= 8 && length <= 10){
			str = "(" + str.substring(0,3) + ") " + str.substring(3,6) + "-" + str.substring(6,str.length);	
		}
		if(length > 10){
			return str;
		}
	}else{
		if(length >1 && length <=4){
			str = "1 " + str.substring(1,str.length);	
		}
		if(length >4 && length <=7){
			str = "1 (" + str.substring(1,4) + ") " + str.substring(4,str.length);	
		}
		if(length >7 && length <=11){
			str = "1 (" + str.substring(1,4) + ") " + str.substring(4,7) + "-" + str.substring(7,str.length);	
		}
		if(length > 11){
			return str;
		}
	}
	return str;
}
function remove_char(str){
	str = str.split("(").join("");
	str = str.split(")").join("");
	str = str.split("-").join("");
	str = str.split(" ").join("");
	return str;
}
function IsNumeric(sText){
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;
   for (i = 0; i < sText.length && IsNumber == true; i++){ 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         IsNumber = false;
   }
   return IsNumber;
}
function showUSAMoney(number, decimal){
	if(typeof(decimal) == 'undefined' || decimal == null) decimal = 2;
	var st = '';
	if(number >= 0) st = '$'+formatAsMoney(number, decimal);
	else st = '- $'+formatAsMoney((-1)*number, decimal);
	return st;
}

function addToWishlist(key)
{
	$.post(url_server__+"shop/wishlist",
	{
		addItem: "yes",
		itmkey: key
	},
	function(data){
		var msg = "";
		switch(data)
		{
			case "0"://Anonymous user
				window.location = url_server__+"login";
				return;
			case "-1"://product key is invalid
			case "-2"://this product does not exist
				msg = "This product does not exist!";
				break;
			case "-3"://This product has been in your wishlist
				msg = "This product has been in your wishlist!";
				break;
			case "1":
				msg = "Add product to wishlist successfully";
				break;
		}
		alert(msg);
		return false;
	})	
}//function addToWishlist

function getParameter(paramName) {
	var str = window.location+"";
	var arr_hash = str.split('#');
	if (arr_hash.length<=1) return null; 
	var searchString = arr_hash[1];
	i=0, val='', params = searchString.split("&");
  	for (i=0;i<params.length;i++) {
  		val = params[i].split("=");
    	if (val[0] == paramName) {
      		return unescape(val[1]);
    	}
  	}
  	return null;
}