// JavaScript Document
$( document ).ready(function(){
	active_current_menu();
	process_menu_layout();
});

function active_current_menu()
{
	var arr_a = $('.inner-nav li a');
	var href = window.location.href;
	if (href.indexOf('store/orders/voucher_details') > 0) href = href.substring(0,href.indexOf('store/orders/voucher_details'))+"store/orders/vouchers";
	if (href.indexOf('representatives/reports') > 0) href = href.substring(0,href.indexOf('representatives/reports'))+"report/salerep";
	if (href.indexOf('store/orders/vouchers/') > 0)	 href = href.substring(0,href.indexOf('store/orders/vouchers'))+"store/orders/vouchers";
	if (href.indexOf('events/add')>0) href = href.substring(0,href.indexOf('events/add'))+'events/listEvents';
	if (href.indexOf('rolepermission')>0) href = href.substring(0,href.indexOf('rolepermission'))+'roles';
	
	for (i = 0; i < arr_a.length; i++)
	{
		if (href == $(arr_a[i]).attr('href'))
		{
				$(arr_a[i]).parent().addClass('active').parent().parent().addClass('active');
				return;
		}
	}
	
	for (i = 0; i < arr_a.length; i++)
	{
		if (href.indexOf($(arr_a[i]).attr('href')) == 0)
		{
				$(arr_a[i]).parent().addClass('active').parent().parent().addClass('active');
		}
	}
}

function process_menu_layout()
{
	//Function
	function has_parent_menu()
	{
		return ($('#navigation li').length > 1);
	}
	function has_child_menu()
	{
		return ($('#navigation > ul > li.active > ul > li').length > 0)
	}
	//Main
	if (!has_parent_menu())
	{
		$('#sidebar').remove();
		$('#content-inner').attr('style','background-image: none !important');
		$('#main').attr('style','margin-left:0px !important;')
	}
	if (!has_child_menu())
	{
		$('#content').addClass('sidebar-minimized');
		$('#sidebar-toggle-wrap').attr('style','display:none !important');
	}

}