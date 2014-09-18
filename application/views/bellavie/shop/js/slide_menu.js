var div;
var total_width_nav = 0;
var left_nav = 0;
var div_width_nav = 0;
var status_nav = -1;
function move_previous()
{
	$('.ext_navbar').remove();
	if (left_nav<0)
	{
		left_nav += 70;
	    str_nav = left_nav + "px";
		$(".navbar_item").animate({'left':str_nav},{queue:false});
	}
}
function move_next()
{	$('.ext_navbar').remove();
	if (total_width_nav == 0)
	{
		get_nav_width();
	}
	if ((total_width_nav + left_nav) > $('#main_navbar').width()-55)
	{
		left_nav -= 70;
	    str_nav = left_nav + "px";
		$(".navbar_item").animate({'left':str_nav},{queue:false});
	}
}

$(function(){
	if($('#button_switch').next().css('display') == 'none')
	{
		$('#button_switch').show();
	}
	else
	{
		$('#button_switch').hide();
	}
	$(".navbar_item").hover(function(){
			if (($(this).offset().left > $('#main_navbar').offset().left) && ($(this).offset().left+$(this).width()-20<$('#main_navbar').offset().left+$('#main_navbar').width()))
			{
				var target = $(this);
				div = $('<div class="navbar ext_navbar"><ul class="nav"><li class="navbar_item">'+target.html()+'</li></ul></div>');
				div.css('position','absolute');
				div.find('a').css({'font-family':"'Open Sans', Tahoma, Arial sans-serif;",'font-size':'12px'});
				div.find('.nav > li:first-child').css({'border':'none'});
				div.find('div li a').css({'padding-top':"5px","padding-bottom":"5px"});
				div.css({'border':'none'});
				var offset = target.offset();
				div.offset(offset);
				div.width(target.width()-1);
				div.find('li:first').width(target.width());
				$('#mainContainer').append(div);
				div.hover(function(){},function(){$(".ext_navbar").remove()});
				div.trigger('hover');
			}
		},function(){});
	$(window).resize(function(){
		$('.ext_navbar').remove();
		change_layout_nav();
	});	
})
function change_layout_nav()
{
	var width = $(window).width();
	if (width>=980)
	{
		if (status_nav != 0)
		{
			status_nav = 0;
			get_nav_width();
		}
	}
	else {
			if (status_nav != 1)
			{
				status_nav = 1;
				get_nav_width();
			}
		}
	if($('#button_switch').next().css('display') == 'none')
	{
		$('#button_switch').show();
	}
	else
	{
		$('#button_switch').hide();
	}
}

function get_nav_width()
{
	arr_nav = $(".navbar_item");
	total_width_nav = 0;
	for (var i = 0;i<arr_nav.length;i++)
	{
		total_width_nav += $(arr_nav[i]).width();
	}
}