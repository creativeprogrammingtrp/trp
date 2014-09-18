// JavaScript Document
$(function(){
	$(".categories ul li.open").children("ul.submenu").show();
	$(".categories ul li").hover(function(){
		$(this).children("ul.submenu").show(400);	
	},function(){
		if(!$(this).hasClass("open"))
			$(this).children("ul.submenu").hide(400);		
	})
})