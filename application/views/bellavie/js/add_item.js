var ids_related = [];
var uploadImgProduct;
var currentFile;
var x_img, y_img, w_img, h_img, boxWidth, boxHeight, fileId, file_name, extend;
var arr_movie = ['wmv','WMV','wma','WMA','avi','AVI','mpeg','MPEG','mpg','MPG','mov','MOV','mp4','MP4','mpe','MPE','m1v','M1V','dvr-ms','DVR-MS','mpe','MPE','mp2','MP2','mpv2','MPV2'];
var arr_flv = ['flv','FLV'];
var arr_swf = ['swf','SWF'];
var arr_img = ['jpg','JPG','jpeg','JPEG','pjpeg','PJPEG','gif','GIF','bmp','BMP','png','PNG'];
function CancelUploadProduct(){	
	uploadImgProduct.stop();
	$("#upload_bt").show();
	$("#Progress_bar").hide();
}
var thumb_width = 54;
var thumb_height = 48;
var imgCrop_width = 360;
var imgCrop_height = 320;
var box_width = 700;
var box_height = 500;
var rate = 1;
function ShowCropImage(newImg, gallery_main){
	if(newImg.complete) {
		HideLoadingObj(document.getElementById("gallery-main"));
		newImg.style.display = 'block';
		
		var left_move_banner = (box_width-imgCrop_width)/2;
		var top_move_banner = (box_height-imgCrop_height)/2;
		var style_move_banner = 'cursor:move; width:'+imgCrop_width+'px; height:'+imgCrop_height+'px; left:'+left_move_banner+'px; top:'+top_move_banner+'px; position:absolute; z-index:1; overflow:hidden;';
		var style_move_banner_m = 'cursor:move; width:'+(imgCrop_width-5)+'px; height:'+(imgCrop_height-5)+'px; overflow:hidden; background-color:#000000';
		
		var move_banner;
		if(document.getElementById('move_banner')){
			move_banner = document.getElementById('move_banner');
			move_banner.setAttribute("style", style_move_banner);
			
			if(!document.getElementById("move_banner_m")){
				var move_banner_m = document.createElement("div");
				move_banner.appendChild(move_banner_m);
				move_banner_m.id = 'move_banner_m';
				move_banner_m.setAttribute("style", style_move_banner_m);
				setOpacity(document.getElementById("move_banner_m"), 0);	
			}
			if(!document.getElementById("_line_top_")){
				var line_top = document.createElement("div");
				move_banner.appendChild(line_top);
				line_top.id = '_line_top_';
				line_top.className = 'jcrop-hline';
				line_top.setAttribute("style", 'position: absolute; top: 0px;');
				setOpacity(document.getElementById("_line_top_"), 50);		
			}
			if(!document.getElementById("_line_bottom_")){
				var line_bottom = document.createElement("div");
				move_banner.appendChild(line_bottom);
				line_bottom.id = '_line_bottom_';
				line_bottom.className = 'jcrop-hline';
				line_bottom.setAttribute("style", 'position: absolute; top: '+(imgCrop_height-1)+'px;');
				setOpacity(document.getElementById("_line_bottom_"), 50);	
			}
			if(!document.getElementById("_line_left_")){
				var line_left = document.createElement("div");
				move_banner.appendChild(line_left);
				line_left.id = '_line_left_';
				line_left.className = 'jcrop-vline';
				line_left.setAttribute("style", 'position: absolute; top:0px;');
				setOpacity(document.getElementById("_line_left_"), 50);	
			}
			if(!document.getElementById("_line_right_")){
				var line_right = document.createElement("div");
				move_banner.appendChild(line_right);
				line_right.id = '_line_right_';
				line_right.className = 'jcrop-vline';
				line_right.setAttribute("style", 'position: absolute; left: '+(imgCrop_width-1)+'px; top:0px;');	
				setOpacity(document.getElementById("_line_right_"), 50);	
			}	
		}else{
			move_banner = document.createElement("div");
			gallery_main.appendChild(move_banner);
			move_banner.id = "move_banner";	
			move_banner.setAttribute("style", style_move_banner);
			
			var move_banner_m = document.createElement("div");
			move_banner.appendChild(move_banner_m);
			move_banner_m.id = 'move_banner_m';
			move_banner_m.setAttribute("style", style_move_banner_m);	
			setOpacity(document.getElementById("move_banner_m"), 0);
			
			var line_top = document.createElement("div");
			move_banner.appendChild(line_top);
			line_top.id = '_line_top_';
			line_top.className = 'jcrop-hline';
			line_top.setAttribute("style", 'position: absolute; top: 0px;');
			setOpacity(document.getElementById("_line_top_"), 80);	
			
			var line_bottom = document.createElement("div");
			move_banner.appendChild(line_bottom);
			line_bottom.id = '_line_bottom_';
			line_bottom.className = 'jcrop-hline';
			line_bottom.setAttribute("style", 'position: absolute; top: '+(imgCrop_height-1)+'px;');
			setOpacity(document.getElementById("_line_bottom_"), 80);
			
			var line_left = document.createElement("div");
			move_banner.appendChild(line_left);
			line_left.id = '_line_left_';
			line_left.className = 'jcrop-vline';
			line_left.setAttribute("style", 'position: absolute; top:0px;');
			setOpacity(document.getElementById("_line_left_"), 80);	
			
			var line_right = document.createElement("div");
			move_banner.appendChild(line_right);
			line_right.id = '_line_right_';
			line_right.className = 'jcrop-vline';
			line_right.setAttribute("style", 'position: absolute; left: '+(imgCrop_width-1)+'px; top:0px;');	
			setOpacity(document.getElementById("_line_right_"), 80);	
		}
		$("#move_banner").draggable({
			containment: '#gallery-main',
			drag: function() {
				var left_ = parseFloat(move_banner.offsetLeft)>0?parseFloat(move_banner.offsetLeft):0;
				var top_ = parseFloat(move_banner.offsetTop)>0?parseFloat(move_banner.offsetTop):0;
				
				var current_w = parseFloat(newImg.offsetWidth);
				if(current_w < box_width) current_w = box_width;
				var current_h = parseFloat(newImg.offsetHeight);
				if(current_h < box_height) current_h = box_height;
				
				var move_banner_top =  document.getElementById('move_banner_top');
				move_banner_top.style.height = top_ + 'px';
				
				var move_banner_right =  document.getElementById('move_banner_right');
				move_banner_right.style.left = (left_+imgCrop_width) + 'px';
				move_banner_right.style.top = top_ + 'px';
				move_banner_right.style.width = ((current_w-left_-imgCrop_width > 0)?current_w-left_-imgCrop_width:0) + 'px';
				
				var move_banner_bottom =  document.getElementById('move_banner_bottom');
				move_banner_bottom.style.height = ((current_h-imgCrop_height-top_>0)?current_h-imgCrop_height-top_:0) + 'px';
				move_banner_bottom.style.top = (imgCrop_height+top_) + 'px';
				
				var move_banner_left =  document.getElementById('move_banner_left');
				move_banner_left.style.top = top_ + 'px';
				move_banner_left.style.width = left_ + 'px';
			},
			stop: function() {
				var left_ = parseFloat(move_banner.offsetLeft)>0?parseFloat(move_banner.offsetLeft):0;
				var top_ = parseFloat(move_banner.offsetTop)>0?parseFloat(move_banner.offsetTop):0;
				
				var current_w = parseFloat(newImg.offsetWidth);
				if(current_w < box_width) current_w = box_width;
				var current_h = parseFloat(newImg.offsetHeight);
				if(current_h < box_height) current_h = box_height;
				
				var move_banner_top =  document.getElementById('move_banner_top');
				move_banner_top.style.height = top_ + 'px';
				
				var move_banner_right =  document.getElementById('move_banner_right');
				move_banner_right.style.left = (left_+imgCrop_width) + 'px';
				move_banner_right.style.top = top_ + 'px';
				move_banner_right.style.width = ((current_w-left_-imgCrop_width > 0)?current_w-left_-imgCrop_width:0) + 'px';
				
				var move_banner_bottom =  document.getElementById('move_banner_bottom');
				move_banner_bottom.style.height = ((current_h-imgCrop_height-top_>0)?current_h-imgCrop_height-top_:0) + 'px';
				move_banner_bottom.style.top = (imgCrop_height+top_) + 'px';
				
				var move_banner_left =  document.getElementById('move_banner_left');
				move_banner_left.style.top = top_ + 'px';
				move_banner_left.style.width = left_ + 'px';
			}  
		});
		var new_width = boxWidth;
		if(boxWidth < box_width) new_width = box_width;
		var new_height = boxHeight;
		if(boxHeight < box_height) new_height = box_height;
		var move_banner_top;
		if(document.getElementById('move_banner_top')){
			move_banner_top = document.getElementById('move_banner_top');
		}else{
			move_banner_top = document.createElement("div");
			gallery_main.appendChild(move_banner_top);
			move_banner_top.id = "move_banner_top";
			
			move_banner_top.setAttribute("style", 'width:'+new_width+'px; height:'+top_move_banner+'px; overflow:hidden; left:0px; top:0px; border:none; position:absolute; background-color:#000000; z-index:1;');
			setOpacity(move_banner_top, 50);
		}
		var move_banner_bottom;
		if(document.getElementById('move_banner_bottom')){
			move_banner_bottom = document.getElementById('move_banner_bottom');
		}else{
			move_banner_bottom = document.createElement("div");
			gallery_main.appendChild(move_banner_bottom);
			move_banner_bottom.id = "move_banner_bottom";
			move_banner_bottom.setAttribute("style", 'width:'+new_width+'px; height:'+((new_height-imgCrop_height-top_move_banner>0)?new_height-imgCrop_height-top_move_banner:0)+'px; overflow:hidden; left:0px; top:'+(imgCrop_height+top_move_banner)+'px; border:none; position:absolute; background-color:#000000; z-index:1');
			setOpacity(move_banner_bottom, 50);
		}
		var move_banner_right;
		if(document.getElementById('move_banner_right')){
			move_banner_right = document.getElementById('move_banner_right');
		}else{
			move_banner_right = document.createElement("div");
			gallery_main.appendChild(move_banner_right);
			move_banner_right.id = "move_banner_right";
			move_banner_right.setAttribute("style", 'width:'+((new_width-left_move_banner-imgCrop_width > 0)?new_width-left_move_banner-imgCrop_width:0)+'px; height:'+imgCrop_height+'px; overflow:hidden; left:'+(imgCrop_width+left_move_banner)+'px; top:'+top_move_banner+'px; border:none; position:absolute; background-color:#000000; z-index:1');
			setOpacity(move_banner_right, 50);
		}
		var move_banner_left;
		if(document.getElementById('move_banner_left')){
			move_banner_left = document.getElementById('move_banner_left');
		}else{
			move_banner_left = document.createElement("div");
			gallery_main.appendChild(move_banner_left);
			move_banner_left.id = "move_banner_left";
			move_banner_left.setAttribute("style", 'width:'+left_move_banner+'px; height:'+imgCrop_height+'px; overflow:hidden; left:0px; top:'+top_move_banner+'px; border:none; position:absolute; background-color:#000000; z-index:1');
			setOpacity(move_banner_left, 50);
		}
		return;
   	}else{
		setTimeout(function () {	 
			ShowCropImage(newImg, gallery_main)
		}, 100);
	}	
}
function zoom(type){	
	var img_customize_gallery = document.getElementById("img_customize_gallery");
	var current_w = parseFloat(img_customize_gallery.offsetWidth);
	var current_h = parseFloat(img_customize_gallery.offsetHeight);
	if(current_w == 0 || current_h == 0) return;	
	var val = 2;
	var new_w = current_w;
	var new_h = current_h;
	if(type == 1){
		new_w = current_w + val;
		new_h = new_w * current_h / current_w;
		if(new_h < 0) new_h = 0;
	}else if(type == 0){
		new_w = current_w - val;
		if(new_w < 0) new_w = 0;
		new_h = new_w * current_h / current_w;	
		if(new_h < 0) new_h = 0;
	}
	img_customize_gallery.width = new_w;
	img_customize_gallery.height = new_h;
	
	img_customize_gallery.style.left = ((box_width-new_w)/2>0?(box_width-new_w)/2:0) + 'px';
	img_customize_gallery.style.top = ((box_height-new_h)/2>0?(box_height-new_h)/2:0) + 'px';	
	
	
	if(new_w < box_width) new_w = box_width;
	if(new_h < box_height) new_h = box_height;
	
	var move_banner = document.getElementById('move_banner');
	var left_ = parseFloat(move_banner.offsetLeft);
	var top_ = parseFloat(move_banner.offsetTop);
	
	var move_banner_top =  document.getElementById('move_banner_top');
	move_banner_top.style.height = top_ + 'px';
	move_banner_top.style.width = new_w + 'px';
	
	var move_banner_right =  document.getElementById('move_banner_right');
	move_banner_right.style.left = (left_+imgCrop_width) + 'px';
	move_banner_right.style.top = top_ + 'px';
	move_banner_right.style.width = (new_w-left_-imgCrop_width>0?new_w-left_-imgCrop_width:0) + 'px';
	
	var move_banner_bottom =  document.getElementById('move_banner_bottom');
	move_banner_bottom.style.height = (new_h-imgCrop_height-top_>0?new_h-imgCrop_height-top_:0) + 'px';
	move_banner_bottom.style.top = (imgCrop_height+top_) + 'px';
	move_banner_bottom.style.width = new_w + 'px';
	
	var move_banner_left =  document.getElementById('move_banner_left');
	move_banner_left.style.top = top_ + 'px';
	move_banner_left.style.width = left_ + 'px';
}
function removeAdFile(){
	$("#gallery-main").empty();	
}
function resizeImgThumb(newImg){
	if(newImg.complete) {	
		newImg.width = thumb_width;
		newImg.height = thumb_height;
		newImg.style.display = 'block';
	}else{
		setTimeout(function () {	 
			resizeImgThumb(newImg)
		}, 100);	
	}
}
function ActiveImgProduct(activeimg){
	if(imgActive == activeimg) return;
	if(document.getElementById(activeimg)){
		document.getElementById(imgActive).className = 'liImgthumb';	
		imgActive = activeimg;	
		document.getElementById(imgActive).className = 'liImgthumbActive';	
		$("#image_sample").empty();
		for(var i = 0; i < dataImgProduct.length; i++){
			if(dataImgProduct[i] == imgActive){
				showActive(i);
				break;	
			}	
		}
	}	
}
function RemoveImgProduct(){
	var dataImgProduct_tam = dataImgProduct;
	dataImgProduct = [];
	for(var i = 0; i < dataImgProduct_tam.length; i++){
		if(dataImgProduct_tam[i] != imgActive){
			dataImgProduct[dataImgProduct.length] = dataImgProduct_tam[i];	
		}	
	}
	loadImagesProduct();	
}
function loadingImgSample(newImg){
	if(newImg.complete) {	
		newImg.style.visibility = 'visible';
		HideLoadingObj(document.getElementById("image_sample"));
	}else{
		setTimeout(function () {	 
			loadingImgSample(newImg)
		}, 100);	
	}
}
function CheckExitsImg(src){
	if(src.indexOf(".", 0) != -1) return true; 
	return false;
}
var checkzoom = false;
var timeZoom;
function startZoom(type){
	checkzoom = true;
	ZoomDone(type);
}
function ZoomDone(type){
	if(checkzoom){
		zoom(type);
		timeZoom = setTimeout(function () {	 
			ZoomDone(type)
		}, 50);	
	}
}
function stopZoom(){
	checkzoom = false;
	clearTimeout(timeZoom);
}
var timerID;
var nhay = 10;
var setTimeOut = 40;
var thumb_section;
var timerRunning = false;
function StartScrolling(image_thumb){
	thumb_section = document.getElementById("image_thumb");	
	addEventHandler(thumb_section, 'mouseover', function(event){moveThumb(event);});
	addEventHandler(thumb_section, 'mousemove', function(event){moveThumb(event);});
	addEventHandler(thumb_section, 'mouseout', function(event){DungLai();});
}
function moveThumb(e){
	if(timerRunning == false){
		timerRunning = true;
		if(document.all) e = event;
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		if(navigator.userAgent.toLowerCase().indexOf('safari')>=0)st=0;
		var leftPos = e.clientX - findPosX(thumb_section);
		if(leftPos < 0) leftPos = 0;
		if(leftPos <= 62){
			move_left();
		}else if(leftPos > 310){
			move_right();
		}else{
			DungLai();	
		}		
	}
}
function move_right() {
	if(timerRunning){
		var scrollLeft = thumb_section.scrollLeft;
		thumb_section.scrollLeft = scrollLeft + nhay;
		timerID = setTimeout(function () {
			move_right();	
		}, setTimeOut);	
	}
}
function move_left() {
	if(timerRunning){
		var scrollLeft = thumb_section.scrollLeft;
		if (scrollLeft > 0){
			thumb_section.scrollLeft = scrollLeft - nhay;
			if(thumb_section.scrollLeft < 0){
				thumb_section.scrollLeft = 0;
				DungLai();
			}else{
				timerID = setTimeout(function () {
					move_left();	
				}, setTimeOut);		
			}
		}else{
			thumb_section.scrollLeft = 0;
			DungLai();	
		}
	}
}
function DungLai(){
	timerRunning = false;
	clearTimeout(timerID);
}
function onblurInputText(o){
	hideTooltipRequi();
	if(o.value == ''){
		hidelabelfocus(o.id+"_label");
		return false;	
	}
}
function showPromotion(obj){
	if(obj.checked == true){
		$("#show_Promotion").show("blind");	
	}else{
		$("#show_Promotion").hide("blind");		
	}	
}
function delete_Attribute(akey){
	for(var i = 0; i < arrAttributes.length; i++){
		if(arrAttributes[i].akey == akey){
			arrAttributes[i].my_attribute = 0;
			arrAttributes[i].my_objAttribute = [];
			loadAttributesListing();
			break;
		}
	}
}
function EnabledAllOptions(obj){
	if(obj.checked == true){
		if(document.getElementsByName("check_Options[]")){
			var check_Options = document.getElementsByName("check_Options[]");
			for(var i = 0; i < check_Options.length; i++){
				check_Options[i].checked = true;	
			}	
		}
	}else{
		if(document.getElementsByName("check_Options[]")){
			var check_Options = document.getElementsByName("check_Options[]");
			for(var i = 0; i < check_Options.length; i++){
				check_Options[i].checked = false;	
			}	
		}	
	}
}
function UnEnabledAllOptions(obj){
	if(obj.checked == false){
		if(document.getElementById("CheckAll_options")){
			document.getElementById("CheckAll_options").checked = false;	
		}
	}
}
function Add_Attributes_toProduct(){
	for(var i = 0; i < arrAttributes.length; i++){
		if(parseInt(arrAttributes[i].my_attribute, 10) == 1) continue;	
		if(document.getElementById("attribute_"+arrAttributes[i].akey)){
			if(document.getElementById("attribute_"+arrAttributes[i].akey).checked == true){
				arrAttributes[i].my_attribute = 1;	
			}	
		}
	}
	loadAttributesListing();
	closeBox("box_popup");
}
function loadListPosition(weight, key){
	var str = '<select id="weight_'+key+'">';
	for(var i = 50; i > -51; i--){
		var select_ = '';
		if(weight == i) select_ = 'selected="selected"';
		str += '<option value="'+i+'" '+select_+'>'+i+'</option>';	
	}
	str += '</select>';
	return str;
}
function loadDisplay_type(display_type, key){
	var arr_display_type = [];
	arr_display_type[arr_display_type.length] = 'Text field';
	arr_display_type[arr_display_type.length] = 'Select box';
	arr_display_type[arr_display_type.length] = 'Radio buttons';
	arr_display_type[arr_display_type.length] = 'Checkboxes';
	var str = '<select id="display_type_'+key+'">';
	for(var i = 0; i < arr_display_type.length; i++){
		var select_ = '';
		if(display_type == i) select_ = 'selected="selected"';
		str += '<option value="'+i+'" '+select_+'>'+arr_display_type[i]+'</option>';	
	}
	str += '</select>';
	return str;
}
function showBoxUploadProduct(type){
	switch(parseInt(type, 10)){
		case 0:
			document.getElementById("image_video_product").className = 'tab_img_pro_active';
			if(document.getElementById("dressing_room_product")) document.getElementById("dressing_room_product").className = 'tab_img_pro';
			$("#image_video_product_box").show();
			$("#dressing_room_product_box").hide();
			break;
		case 1:
			document.getElementById("image_video_product").className = 'tab_img_pro';
			if(document.getElementById("dressing_room_product")) document.getElementById("dressing_room_product").className = 'tab_img_pro_active';
			$("#image_video_product_box").hide();
			$("#dressing_room_product_box").show();
			break;	
	}
}

function ChangeContent(type){
	tab_selected = type;
	switch(parseInt(type, 10)){
		case 1:
			ShowProductInformation();
			break;
		case 2:
			ShowWarrantySettings();
			break;	
		case 3:
			ShowAttributesSettings();
			break;
		case 4:
			ShowPromotionSettings();
			break;
		case 5:
			ShowPriceMarkupSettings();
			break;
		case 6:
			ShowLocationServices();
			break;	
		case 7:
			ShowTaxSetting();
			break;		
	}
}

function ShowTaxSetting(){
	document.getElementById("product_infomations_tab").className = 'tab';
	document.getElementById("attributes_settings_tab").className = 'tab';
	$("#product_infomations").hide();
	$("#attributes_settings").hide();
	if(document.getElementById("warranty_settings_tab")){
		document.getElementById("warranty_settings_tab").className = 'tab';	
		$("#warranty_settings").hide();
	}
	if(document.getElementById("promotion_settings_tab")){
		document.getElementById("promotion_settings_tab").className = 'tab';	
		$("#promotion_settings").hide();
	}
	if(document.getElementById("price_markup_tab")){
		document.getElementById("price_markup_tab").className = 'tab';	
		$("#price_markup_setting").hide();
	}
	if(document.getElementById("location_tab")){
		document.getElementById("location_tab").className = 'tab';	
		$("#location_setting").hide();
	}
	if(document.getElementById("tax_settings_tab")){
		document.getElementById("tax_settings_tab").className = 'tab_active';	
		$("#tax_setting").show();
	}
		
}

function ShowLocationServices(){
	document.getElementById("product_infomations_tab").className = 'tab';
	document.getElementById("attributes_settings_tab").className = 'tab';
	$("#product_infomations").hide();
	$("#attributes_settings").hide();
	if(document.getElementById("warranty_settings_tab")){
		document.getElementById("warranty_settings_tab").className = 'tab';	
		$("#warranty_settings").hide();
	}
	if(document.getElementById("promotion_settings_tab")){
		document.getElementById("promotion_settings_tab").className = 'tab';	
		$("#promotion_settings").hide();
	}
	if(document.getElementById("price_markup_tab")){
		document.getElementById("price_markup_tab").className = 'tab';	
		$("#price_markup_setting").hide();
	}
	if(document.getElementById("location_tab")){
		document.getElementById("location_tab").className = 'tab_active';	
		$("#location_setting").show();
	}
	if(document.getElementById("tax_settings_tab")){
		document.getElementById("tax_settings_tab").className = 'tab';	
		$("#tax_setting").hide();
	}
}

function ShowPriceMarkupSettings(){
	document.getElementById("product_infomations_tab").className = 'tab';
	document.getElementById("attributes_settings_tab").className = 'tab';
	$("#product_infomations").hide();
	$("#attributes_settings").hide();
	if(document.getElementById("warranty_settings_tab")){
		document.getElementById("warranty_settings_tab").className = 'tab';	
		$("#warranty_settings").hide();
	}
	if(document.getElementById("promotion_settings_tab")){
		document.getElementById("promotion_settings_tab").className = 'tab';	
		$("#promotion_settings").hide();
	}
	if(document.getElementById("price_markup_tab")){
		document.getElementById("price_markup_tab").className = 'tab_active';	
		$("#price_markup_setting").show();
	}
	if(document.getElementById("location_tab")){
		document.getElementById("location_tab").className = 'tab';	
		$("#location_setting").hide();
	}
	if(document.getElementById("tax_settings_tab")){
		document.getElementById("tax_settings_tab").className = 'tab';	
		$("#tax_setting").hide();
	}
}
function ShowProductInformation(){
	document.getElementById("product_infomations_tab").className = 'tab_active';
	document.getElementById("attributes_settings_tab").className = 'tab';
	$("#product_infomations").show();
	$("#attributes_settings").hide();
	if(document.getElementById("warranty_settings_tab")){
		document.getElementById("warranty_settings_tab").className = 'tab';	
		$("#warranty_settings").hide();
	}
	if(document.getElementById("promotion_settings_tab")){
		document.getElementById("promotion_settings_tab").className = 'tab';	
		$("#promotion_settings").hide();
	}
	if(document.getElementById("price_markup_tab")){
		document.getElementById("price_markup_tab").className = 'tab';	
		$("#price_markup_setting").hide();
	}
	if(document.getElementById("location_tab")){
		document.getElementById("location_tab").className = 'tab';	
		$("#location_setting").hide();
	}
	if(document.getElementById("tax_settings_tab")){
		document.getElementById("tax_settings_tab").className = 'tab';	
		$("#tax_setting").hide();
	}
}
var check_load_shipping = false;
function ShowWarrantySettings(){
	document.getElementById("product_infomations_tab").className = 'tab';
	document.getElementById("attributes_settings_tab").className = 'tab';
	$("#product_infomations").hide();
	$("#attributes_settings").hide();
	if(document.getElementById("warranty_settings_tab")){
		document.getElementById("warranty_settings_tab").className = 'tab_active';	
		$("#warranty_settings").show();
		if(check_load_shipping == false){
			check_load_shipping = true;
			showShippingListings();
		}
		showWarrantyListings();
	}
	if(document.getElementById("promotion_settings_tab")){
		document.getElementById("promotion_settings_tab").className = 'tab';	
		$("#promotion_settings").hide();
	}
	if(document.getElementById("price_markup_tab")){
		document.getElementById("price_markup_tab").className = 'tab';	
		$("#price_markup_setting").hide();
	}
	if(document.getElementById("location_tab")){
		document.getElementById("location_tab").className = 'tab';	
		$("#location_setting").hide();
	}
	if(document.getElementById("tax_settings_tab")){
		document.getElementById("tax_settings_tab").className = 'tab';	
		$("#tax_setting").hide();
	}
}
function ShowAttributesSettings(){
	document.getElementById("product_infomations_tab").className = 'tab';
	document.getElementById("attributes_settings_tab").className = 'tab_active';
	$("#product_infomations").hide();
	$("#attributes_settings").show();
	if(document.getElementById("warranty_settings_tab")){
		document.getElementById("warranty_settings_tab").className = 'tab';	
		$("#warranty_settings").hide();
	}
	if(document.getElementById("promotion_settings_tab")){
		document.getElementById("promotion_settings_tab").className = 'tab';	
		$("#promotion_settings").hide();
	}
	if(check_loadAttribute == false){
		check_loadAttribute = true;
		loadObjAttributes();	
	}
	if(document.getElementById("price_markup_tab")){
		document.getElementById("price_markup_tab").className = 'tab';	
		$("#price_markup_setting").hide();
	}
	if(document.getElementById("location_tab")){
		document.getElementById("location_tab").className = 'tab';	
		$("#location_setting").hide();
	}
	if(document.getElementById("tax_settings_tab")){
		document.getElementById("tax_settings_tab").className = 'tab';	
		$("#tax_setting").hide();
	}
}
var check_loadPromotions = false;
function ShowPromotionSettings(){
	document.getElementById("product_infomations_tab").className = 'tab';
	document.getElementById("attributes_settings_tab").className = 'tab';
	$("#product_infomations").hide();
	$("#attributes_settings").hide();
	if(document.getElementById("warranty_settings_tab")){
		document.getElementById("warranty_settings_tab").className = 'tab';	
		$("#warranty_settings").hide();
	}
	if(document.getElementById("promotion_settings_tab")){
		document.getElementById("promotion_settings_tab").className = 'tab_active';	
		$("#promotion_settings").show();
		if(check_loadPromotions == false){
			check_loadPromotions = true;
			showPromotionListings();
		}
	}
	if(document.getElementById("price_markup_tab")){
		document.getElementById("price_markup_tab").className = 'tab';	
		$("#price_markup_setting").hide();
	}
	if(document.getElementById("location_tab")){
		document.getElementById("location_tab").className = 'tab';	
		$("#location_setting").hide();
	}
	if(document.getElementById("tax_settings_tab")){
		document.getElementById("tax_settings_tab").className = 'tab';	
		$("#tax_setting").hide();
	}
}
function updatePromotion(obj){
	if(document.getElementsByName('promotions[]')){
		var promotions = document.getElementsByName('promotions[]');
		if(obj.checked == true){
			for(var i = 0; i < promotions.length; i++){
				if(promotions[i].value != obj.value){
					promotions[i].checked = false;		
				}
			}		
		}	
	}	
}
function updateWarranty(obj){
	warranty_selected = '';
	if(document.getElementsByName('warranty_select[]')){
		var warranty_select = document.getElementsByName('warranty_select[]');
		if(obj.checked == true){
			warranty_selected = obj.value;
			for(var i = 0; i < warranty_select.length; i++){
				if(warranty_select[i].value != obj.value){
					warranty_select[i].checked = false;		
				}
			}		
		}	
	}	
}
function cal_Selling_price(){
	var current_cost = 0;
	if(document.getElementById("current_cost")){
		current_cost = trim(document.getElementById("current_cost").value);
		if(current_cost != ''){
			if(form_input_is_numeric(current_cost)){
				current_cost = parseFloat(current_cost);	
			}else{
				alert("Current cost must numberic.");
				current_cost = 0;	
			}			
		}
	}
	var markup_per = 0;
	if(document.getElementById("markup_percentage") && document.getElementById("markup_percentage").value != ''){
		markup_per = parseFloat(document.getElementById("markup_percentage").value);	
	}
	var selling_price = current_cost + current_cost * markup_per / 100;
	return selling_price;
}
function Selling_price(){
	$("#selling_price").empty().append("$"+formatAsMoney(cal_Selling_price()));
	if(function_exists('updateTotalPayOut')){
		updateTotalPayOut();	
	}
}
function SaveOptions(akey){
	for(var i = 0; i < arrAttributes.length; i++){
		if(arrAttributes[i].akey == akey){
			var check_default__ = false;
			if(typeof(arrAttributes[i].options) != 'undefined'){
				if(arrAttributes[i].options.length > 0){
					for(var j = 0; j < arrAttributes[i].options.length; j++){
						if(document.getElementById("option_"+arrAttributes[i].options[j].okey)){
							if(document.getElementById("option_"+arrAttributes[i].options[j].okey).checked == true){//1
								if(document.getElementById("odefault_"+arrAttributes[i].options[j].okey)){
									if(document.getElementById("odefault_"+arrAttributes[i].options[j].okey).checked == true){
										check_default__ = true;	
										break;
									}	
								}
							}
						}
					}
					if(check_default__ == false){
						alert("Please choice option default for attribute.");
						return;	
					}
					for(var j = 0; j < arrAttributes[i].options.length; j++){
						if(document.getElementById("option_"+arrAttributes[i].options[j].okey)){
							if(document.getElementById("option_"+arrAttributes[i].options[j].okey).checked == true){//1
								arrAttributes[i].options[j].my_option = 1;
								
								var odefault = 0;
								if(document.getElementById("odefault_"+arrAttributes[i].options[j].okey)){
									if(document.getElementById("odefault_"+arrAttributes[i].options[j].okey).checked == true){
										odefault = 1;
										check_default__ = true;	
									}	
								}
								var cost = arrAttributes[i].options[j].cost;
								if(document.getElementById("option_cost_"+arrAttributes[i].options[j].okey)){
									cost = document.getElementById("option_cost_"+arrAttributes[i].options[j].okey).value;
									if(cost == '') cost = 0;	
								}
								var price = arrAttributes[i].options[j].price;
								if(document.getElementById("option_price_"+arrAttributes[i].options[j].okey)){
									price = document.getElementById("option_price_"+arrAttributes[i].options[j].okey).value;
									if(price == '') price = 0;	
								}
								var weight = arrAttributes[i].options[j].weight;
								if(document.getElementById("option_weight_"+arrAttributes[i].options[j].okey)){
									weight = document.getElementById("option_weight_"+arrAttributes[i].options[j].okey).value;
									if(weight == '') weight = 0;	
								}
								
								var my_objOption = new Object();
								my_objOption.okey = arrAttributes[i].options[j].okey;
								my_objOption.odefault = odefault;
								my_objOption.cost = cost;
								my_objOption.price = price;
								my_objOption.weight = weight;
								
								arrAttributes[i].options[j].my_objOption = my_objOption;	
							}//1	
						}
					}
				}
			}
			if(check_default__ == false){
				alert("Please create option for attribute.");
				return;	
			}
			loadAttributesListing();
			alert("Updating completed. Please Submit form to finish.");
			closeBox("box_popup");
			break;		
		}
	}
}

function changeProductType(){
	if(document.getElementById("product_type").value == 0){
		$("#warranty_settings_tab").show();
		$("#Package_section").show();
		$("#expiration_date_show").hide();
		$("#location_tab").hide();
		$("#voucher").hide();	
	}else{
		if(document.getElementById("product_type").value == 2){
			$("#voucher").show();	
		}else{
			$("#voucher").hide();	
		}
		$("#warranty_settings_tab").hide();	
		$("#Package_section").hide();
		$("#expiration_date_show").show();
		$("#location_tab").show();
	}
}

function showUploadDressingRoom(){
	var check_dressing_room = document.getElementById("check_dressing_room");
	if(check_dressing_room.checked == true){
		$("#show_upload_dressing_room").show();
		uploadImgDressingRoom();
	}else{
		$("#show_upload_dressing_room").hide();	
	}
}

function filterProducts(){
	var Key_work = document.getElementById("search_product").value;
	var arr_Key_work = [];
	if(Key_work != ''){
		Key_work = trim(Key_work);
		Key_work = Key_work.replace(/  /gi, ' ');
		arr_Key_work = Key_work.toUpperCase().split(" ");
	}
	var ids_related_post = [];
	var products_rela = $("#related > ul").children();
	for(var i = 0; i < products_rela.length; i++){
		var spli = products_rela[i].id.split("_");
		if(typeof(spli[1]) != 'undefined'){
			ids_related_post[ids_related_post.length] = parseInt(spli[1], 10);	
		}
	}
	arrProductsShow = [];
	for(var i = 0; i < arrProducts.length; i++){
		var check = false;				
		for(var j = 0; j < arr_Key_work.length; j++){				
			if(arrProducts[i].itm_name.toUpperCase().indexOf(arr_Key_work[j]) != -1){
				check = true;
				break;
			}	
			if(arrProducts[i].itm_model.toUpperCase().indexOf(arr_Key_work[j]) != -1){
				check = true;
				break;
			}
		}
		if(check == false){
			continue;	
		}
		var check_exists = false;
		for(var j = 0; j < ids_related_post.length; j++){
			if(ids_related_post[j] == parseInt(arrProducts[i].itm_id, 10)){
				check_exists = true;
				break;	
			}	
		}
		if(check_exists == false) arrProductsShow[arrProductsShow.length] = arrProducts[i];	
	}
	loadProductsList();
}

function loadAttributesListing(){
	var str_content = '';
	str_content += '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table-per">';
	str_content += '	<thead>';
	str_content += '		<tr>';
	str_content += '			<th align="left" valign="middle" class="th-per">Name</th>';
	str_content += '			<th width="10" class="th-per" align="center" valign="middle">|</th>';
	str_content += '			<th align="left" valign="middle" class="th-per">Label</th>';
	str_content += '			<th width="10" class="th-per" align="center" valign="middle">|</th>';
	str_content += '			<th align="left" valign="middle" class="th-per">Default</th>';
	str_content += '			<th width="10" class="th-per" align="center" valign="middle">|</th>';
	str_content += '			<th align="left" valign="middle" class="th-per">Required</th>';
	str_content += '			<th width="10" class="th-per" align="center" valign="middle">|</th>';
	str_content += '			<th align="left" valign="middle" class="th-per">List position</th>';
	str_content += '			<th width="10" class="th-per" align="center" valign="middle">|</th>';
	str_content += '			<th align="left" valign="middle" class="th-per">Display</th>';
	str_content += '			<th align="left" valign="middle" class="th-per">&nbsp;</th>';
	str_content += '		</tr>';
	str_content += '	</thead>';
	var length_data = arrAttributes.length;
	if(length_data > 0){
		str_content += '	<tbody>';
		for(var i = 0; i < length_data; i++){
			if(parseInt(arrAttributes[i].my_attribute, 10) == 0) continue;
			var obj = arrAttributes[i];
			var button = '<a href="javascript:void(0)" onclick="showOptions(\''+obj.akey+'\')">Options</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="javascript:void(0)" style="color:#ff0000" onclick="delete_Attribute(\''+obj.akey+'\')">x</a>';	
			var required = '';
			if(parseInt(obj.required, 10) == 1) required = 'checked="checked"';
			var weight = parseInt(obj.weight, 10);
			var display_type = parseInt(obj.display_type, 10);
			var label = obj.label;
			if(typeof(obj.my_objAttribute) == 'object'){
				if(typeof(obj.my_objAttribute.label) == 'string'){
					label = obj.my_objAttribute.label;	
				}
				if(typeof(obj.my_objAttribute.required) != 'undefined'){
					if(parseInt(obj.my_objAttribute.required, 10) == 1) required = 'checked="checked"';
					else required = '';			
				}
				if(typeof(obj.my_objAttribute.weight) != 'undefined'){
					weight = parseInt(obj.my_objAttribute.weight, 10);	
				}
				if(typeof(obj.my_objAttribute.display) != 'undefined'){
					display_type = parseInt(obj.my_objAttribute.display, 10);	
				}
			}
			var Default_text = 'n/a';
			if(typeof(obj.options) != 'undefined'){
				for(var j = 0; j < obj.options.length; j++){
					if(parseInt(obj.options[j].my_option, 10) == 1){
						if(parseInt(obj.options[j].my_objOption.odefault, 10) == 1){
							Default_text = obj.options[j].name + '&nbsp;&nbsp;$' + formatAsMoney(obj.options[j].my_objOption.price);	
						}	
					}	
				}
			}
			str_content += '		<tr class="tr-row">';
			str_content += '			<td align="left" valign="middle" class="td-row">'+ConvertToHTML(obj.name)+'</td>';
			str_content += '			<td class="td-row"></td>';
			str_content += '			<td align="left" valign="middle" class="td-row"><input type="text" id="label_'+obj.akey+'" value="'+ConvertToTest(label)+'" class="input-text" style="width:150px" /></td>';
			str_content += '			<td class="td-row"></td>';
			str_content += '			<td align="left" valign="middle" class="td-row">'+Default_text+'</td>';
			str_content += '			<td class="td-row"></td>';
			str_content += '			<td align="left" valign="middle" class="td-row"><input type="checkbox" class="input-checkbox" id="required_'+obj.akey+'" value="1" '+required+' /></td>';
			str_content += '			<td class="td-row"></td>';
			str_content += '			<td align="left" valign="middle" class="td-row">'+loadListPosition(weight, obj.akey)+'</td>';
			str_content += '			<td class="td-row"></td>';
			str_content += '			<td align="left" valign="middle" class="td-row">'+loadDisplay_type(display_type, obj.akey)+'</td>';
			str_content += '			<td align="right" valign="middle" class="td-row">'+button+'</td>';
			str_content += '		</tr>';
		}
		str_content += '	</tbody>';
	}
	str_content += '</table>';
	$("#attributes_listing").empty().append(str_content);
	$('.table-per').fixedtableheader();
}

function showOptions(akey){
	for(var i = 0; i < arrAttributes.length; i++){
		if(arrAttributes[i].akey == akey){
			$("#title_popup").empty().append("Options");
			$("#content_popup").css("width", '800px');
			var str_content = '';
			str_content += '<div style="clear:both">Use the checkboxes to enable options for attributes and the radio buttons to specify defaults for the enabled options. Use the other fields to override the default settings for each option. Attributes with no enabled options will be displayed as text fields.</div>';
			str_content += '<div class="holder osX" style="overflow: auto; overflow-x: hidden; clear:both; padding-top:10px;"><div class="scroll-pane" style="overflow-x: hidden; width:800px; height:270px;" id="options_list_add">';
			str_content += '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table-per">';
			str_content += '	<thead>';
			str_content += '		<tr>';
			str_content += '			<th align="left" valign="middle" class="th-per"><input type="checkbox" class="input-checkbox" id="CheckAll_options" onclick="EnabledAllOptions(this)"/>&nbsp;&nbsp;Enabled</th>';
			str_content += '			<th width="10" class="th-per" align="center" valign="middle">|</th>';
			str_content += '			<th align="left" valign="middle" class="th-per">Default</th>';
			str_content += '			<th width="10" class="th-per" align="center" valign="middle">|</th>';
			str_content += '			<th align="left" valign="middle" class="th-per">Cost ($)</th>';
			str_content += '			<th width="10" class="th-per" align="center" valign="middle">|</th>';
			str_content += '			<th align="left" valign="middle" class="th-per">Price ($)</th>';
			str_content += '			<th width="10" class="th-per" align="center" valign="middle">|</th>';
			str_content += '			<th align="left" valign="middle" class="th-per">List position</th>';
			str_content += '		</tr>';
			str_content += '	</thead>';
			if(typeof(arrAttributes[i].options) != 'undefined'){
				str_content += '<tbody>';
				if(arrAttributes[i].options.length > 0){
					for(var j = 0; j < arrAttributes[i].options.length; j++){
						var check_option = '';
						if(parseInt(arrAttributes[i].options[j].my_option, 10) == 1) check_option = 'checked="checked"';
						var cost = arrAttributes[i].options[j].cost;
						var price = arrAttributes[i].options[j].price;
						var weight = arrAttributes[i].options[j].weight;
						var odefault = '';
						if(typeof(arrAttributes[i].options[j].my_objOption) == 'object'){
							if(typeof(arrAttributes[i].options[j].my_objOption.cost) != 'undefined'){
								cost = arrAttributes[i].options[j].my_objOption.cost;	
							}
							if(typeof(arrAttributes[i].options[j].my_objOption.price) != 'undefined'){
								price = arrAttributes[i].options[j].my_objOption.price;	
							}
							if(typeof(arrAttributes[i].options[j].my_objOption.weight) != 'undefined'){
								weight = arrAttributes[i].options[j].my_objOption.weight;	
							}
							if(typeof(arrAttributes[i].options[j].my_objOption.odefault) != 'undefined'){
								if(parseInt(arrAttributes[i].options[j].my_objOption.odefault, 10) == 1){
									odefault = 'checked="checked"';	
								}	
							}	
						}
						str_content += '		<tr class="tr-row">';
						str_content += '			<td align="left" valign="middle" class="td-row"><input type="checkbox" class="input-checkbox" name="check_Options[]" id="option_'+arrAttributes[i].options[j].okey+'" '+check_option+' onclick="UnEnabledAllOptions(this)"/>&nbsp;&nbsp;'+ConvertToHTML(arrAttributes[i].options[j].name)+'</td>';
						str_content += '			<td class="td-row"></td>';
						str_content += '			<td align="left" valign="middle" class="td-row"><input type="radio" name="default_option[]" id="odefault_'+arrAttributes[i].options[j].okey+'" value="'+arrAttributes[i].options[j].okey+'" class="input-checkbox" '+odefault+' /></td>';
						str_content += '			<td class="td-row"></td>';
						str_content += '			<td align="left" valign="middle" class="td-row"><input type="text" class="input-text" style="width:70px" id="option_cost_'+arrAttributes[i].options[j].okey+'" value="'+cost+'" onkeypress="return isNumberFloatKey(event)"/></td>';
						str_content += '			<td class="td-row"></td>';
						str_content += '			<td align="left" valign="middle" class="td-row"><input type="text" class="input-text" style="width:70px" id="option_price_'+arrAttributes[i].options[j].okey+'" value="'+price+'" onkeypress="return isNumberFloatKey(event)"/></td>';
						str_content += '			<td class="td-row"></td>';
						str_content += '			<td align="left" valign="middle" class="td-row"><input type="text" class="input-text" style="width:50px" id="option_weight_'+arrAttributes[i].options[j].okey+'" value="'+weight+'" onkeypress="return isNumberIntKey(event)" /></td>';
						str_content += '		</tr>';	
					}
				}else{
					str_content += '<tr class="tr-row"><td align="left" valign="middle" class="td-row" colspan="9">This attribute does not have any options.</td></tr>';
				}
				str_content += '</tbody>';		
			}
			str_content += '</table>';
			str_content += '</div></div>';
			str_content += '<div style="clear:both; width:100%; padding-top:20px; margin-right:5px; margin-bottom:5px;" align="right">';
			str_content += '	<input type="button" class="button" value="Update" onclick="SaveOptions(\''+akey+'\')" />';
			str_content += '    <input type="button" class="button" value="Cancel" style="margin-left:5px" id="cancel" />';
			str_content += '</div>';
			$("#content_popup").empty().append(str_content);
			$("#options_list_add").jScrollPane({showArrows:false, verticalGutter:0});
			showbox('box_popup');
			if(document.getElementById("cancel")){
				document.getElementById("cancel").onclick = function(){
					closeBox("box_popup");
				}	
			}
			break;	
		}	
	}
}

function ViewShippingListing(){
	var st = '<div id="__shipping_exist__">';
	if(arr_shipping__.length > 0){
		st += '<table cellpadding="0" cellspacing="0" border="0">';
		for(var i = 0; i < arr_shipping__.length; i++){
			st += '	<tr><td align="left" valign="top" style="font-weight:bold; padding-bottom:5px; padding-top:10px" colspan="2">';
			st += '<div style="float:left; padding-top:2px">'+arr_shipping__[i].label+' (&nbsp;</div>';
			st += '<div style="float:left; padding-top:2px">Handling fee:</div>';
			st += '<div style="float:left; padding-left:10px; overflow:hidden">';
			st += '	<div style="clear:both; overflow:hidden">';
			st += '		<span style="float:left; padding-top:2px">$</span>';
			st += '		<span style="float:left; padding-left:3px"><input type="text" class="input-text" id="'+arr_shipping__[i].skey+'_handling_fee" size="10" value="'+arr_shipping__[i].new_handling+'" style="width:80px" onkeypress="return isNumberFloatKey(event)"></span>';
			st += '	</div>';
			st += '	<span style="clear:both;" class="description_class">Default rate: $'+formatAsMoney(arr_shipping__[i].handling_fee)+'</span>';
			st += '</div>';
			st += '<div style="float:left; padding-top:2px">&nbsp;)</div>';
			
			st += '</td></tr>';
			var countries = arr_shipping__[i].countries;
			for(var j = 0; j < countries.length; j++){
				st += '	<tr>';
				st += '		<td align="left" valign="top" style="padding-left:20px;"><b>'+countries[j].name+'</b></td>';
				st += '		<td align="left" valign="top" style="padding-left:10px; padding-bottom:10px;">';
				if(countries[j].rate_type == 1){
					st += '<div style="clear:both; overflow:hidden">';
					st += '	<span style="float:left; padding-top:2px">$</span>';
					st += '	<span style="float:left; padding-left:3px"><input type="text" class="input-text" id="'+arr_shipping__[i].skey+'_country_'+j+'" size="10" value="'+countries[j].value+'" style="width:80px" onkeypress="return isNumberFloatKey(event)"></span>';
					st += '</div>';
					st += '<span style="clear:both;" class="description_class">Default rate: $'+formatAsMoney(countries[j].rate)+'</span>';
				}else{
					var states = countries[j].states;
					for(var k = 0; k < states.length; k++){
						st += '<div style="clear:both;"><b>'+states[k].name+'</b></div>';	
						st += '<div style="clear:both; overflow:hidden">';
						st += '	<span style="float:left; padding-top:2px">$</span>';
						st += '	<span style="float:left; padding-left:3px"><input type="text" class="input-text" id="'+arr_shipping__[i].skey+'_'+j+'_state_'+k+'" size="10" value="'+states[k].value+'" style="width:80px" onkeypress="return isNumberFloatKey(event)"></span>';
						st += '</div>';
						st += '<div style="clear:both; margin-bottom:10px" class="description_class">Default rate: $'+formatAsMoney(states[k].rate)+'</div>';	
					}	
				}
				st += '		</td>';
				st += '	</tr>';		
			}	
		}
		st += '</table>';	
	}
	st += '</div>';
	$("#shipping_content").empty().append(st);
}

function showBoxUploadImgProduct(){
	$("#fancy_box").show();
	$("#box_uploadImgProduct").show();
	fileId = '';
	file_name = '';
	extend = '';
	$("#gallery-main").empty();
	$("#zoom-img").hide();
	$("#upload_bt").show();
}

function add_location(){
	getValue_location();
	add_location_detail('',0);
}

function add_location_detail(value, dbid){
	var obj = new Object();
	obj.id = 'location_'+dem_locations;
	obj.value = value;
	obj.dbid = dbid;
	arr_locations[arr_locations.length] = obj;
	dem_locations ++;
}

function remove_location(id){
	var i = 0;
	for(i = 0; i < arr_locations.length; i++){
		if(arr_locations[i].id == id){
			arr_locations.splice(i, 1);
			break;	
		}	
	}
	getValue_location();
	showLocations();
}

function showLocations(){
	var st = '', i = 0, bt = '';
	if(arr_locations.length == 0) add_location();
	for(i = 0; i < arr_locations.length; i++){
		bt = '<a href="javascript:void(0)" onclick="remove_location(\''+arr_locations[i].id+'\');" title="Remove">&ndash;</a>';
		if(i == arr_locations.length-1) bt = '<a href="javascript:void(0)" onclick="add_location(); showLocations();" title="Add">+</a>';
		
		st += '<div class="location_item">';
    	st += '<div class="text">';
        st += '<input type="text" name="locations[]" id="'+arr_locations[i].id+'" value="'+arr_locations[i].value+'" class="address_text" placeholder="Address '+(i+1)+'" />';
        st += '</div>';
        st += '<div class="add">';
        st += bt;
        st += '</div>';
    	st += '</div>';	
	}
	$("#location_setting").empty().append(st);
}

function getValue_location(){
	var i = 0;
	for(i = 0; i < arr_locations.length; i++){
		if(document.getElementById(arr_locations[i].id)){
			arr_locations[i].value = document.getElementById(arr_locations[i].id).value;	
		}	
	}
}