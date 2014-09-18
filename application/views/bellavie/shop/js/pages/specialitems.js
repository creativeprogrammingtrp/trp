var direction = "desc";
var sort_by = "itm_date";
var items_per_page = 8;
var type_display = 0;
var page = 1;
var status_resize = -1;

var data_clients = [];
var total_items = 0;
var number_link_display = 0;

function change_sort(e){
	var str = $(e).val().split('|');
	sort_by = 'itm_date';
	direction = "desc";
	if (str.length == 2)
	{
		sort_by = str[0];
		direction = str[1];
	}
	Quicksort.sort(data_clients);
	show_objects_items();
	setHash();
	fix_products_layout(0);
}

function change_display(value){
	items_per_page = value;
	show_objects_items();
	setHash();
	fix_products_layout(0);
	pagination( "reload_page", "pagination", total_items, page, number_link_display, items_per_page );
	generate_sequence_string('number_display');
}

function change_layout(e){
	if ($(e).hasClass('active')) return;
	$(e).addClass('btn-primary active');
	type_display = $(e).attr('type_display');
	if (type_display == 0)
	{
		$(e).next('button').removeClass('btn-primary active');
		$('#item_list').removeClass('listProductItems');
		$('#item_list').addClass('hProductItems');
		
	}
	else
	{
		$(e).prev('button').removeClass('btn-primary active');
		$('#item_list').removeClass('hProductItems');
		$('#item_list').addClass('listProductItems');
	}
	show_objects_items();
	setHash();
}

function show_objects_items(){
	if (data_clients.length == 0)
	{
		$('#error').show();
		$('#error').next().hide().next().hide().next().hide();
		$('#item_list').empty();
		return;
	}
	else
	{
		$('#error').next().show().next().show().next().show();
		$('#error').hide();
		$('#item_list').empty();
	}
	$('#item_list').empty();
	var start = (page - 1)*items_per_page;
	if (start > data_clients.length) 
	{
		start = 0;
		page = 1;
		pagination( "reload_page", "pagination", total_items, page, number_link_display, items_per_page );
		generate_sequence_string('number_display');
	}
	var str = "";
	for (var i = start; i < (parseInt(start)+parseInt(items_per_page));i++)
	{
		if (i<data_clients.length)
		{
			if (type_display == 0)
			{
				switch (i % 4)
				{
					case 0:	str += ('<div class="itm-wrapper clearfix">'+generate_an_item(data_clients[i]));
					break;
					case 3: str += (generate_an_item(data_clients[i])+'</div>');
					break;
					default: str += generate_an_item(data_clients[i]);
					break;
				}
			}
			if (type_display == 1)
			{
				str += (generate_an_item(data_clients[i]));
			}
		}
	}
	if (type_display == 0)
	{
		if (i % 4 != 0)
		{
			str += '</div>';
		}
		$('#item_list').append(str);
		fix_products_layout(1);
	} else $('#item_list').append(str);
}

function generate_an_item(obj)
{
	var file = (obj.file).indexOf('.')>=0?obj.file:dir_theme+"/shop/images/212x192.jpg";
	if (type_display == 0) return '<li class="span3 clearfix"><div class="thumbnail"><a href="'+obj.link+'"><img src="'+file+'" alt=""></a></div><div class="thumbSetting"><div class="thumbTitle"><a href="'+obj.link+'" class="invarseColor">'+obj.itm_name+'</a></div><div class="thumbPrice"><span>$'+(obj.itm_price).formatMoney(2, '.', ',')+'</span></div><div class="thumbButtons"><button class="btn btn-primary btn-small" style="margin-right:4px;" data-title="+To Cart" data-placement="top" data-toggle="tooltip"><i class="icon-shopping-cart"></i></button><button class="btn btn-small" data-title="+To WishList" data-placement="top" data-toggle="tooltip" onClick="addToWishlist(\''+obj.itm_key+'\')"><i class="icon-heart"></i></button></div>'+obj.stars+'</div></li>';
	return '<li class="clearfix"><div class="span3"><div class="thumbnail"><a href="'+obj.link+'"><img src="'+file+'" alt=""></a></div></div><div class="span9"><div class="thumbSetting"><div class="thumbTitle"><a href="'+obj.link+'" class="invarseColor">'+obj.itm_name+'</a></div><div class="thumbPriceRate clearfix"><span>$'+(obj.itm_price).formatMoney(2, '.', ',')+'</span>'+obj.stars+'</div><div class="thumbButtons"><button class="btn btn-primary btn-small" style="margin-right:4px;"><i class="icon-shopping-cart"></i>Add To Cart</button><button class="btn btn-small" style="margin-right:4px;" data-title="+To WishList" data-placement="top" data-toggle="tooltip" data-original-title="" title="" onClick="addToWishlist(\''+obj.itm_key+'\')"><i class="icon-heart"></i></button></div></div></div></li>';
}

function get_objects_items(column){
	getHash();
	data_clients = [];
	//ShowLoadingObj(document.getElementById("gspinner"));
	$.post("",
	{getdata:"yes"},
	function(data){
		//HideLoadingObj(document.getElementById("gspinner"));
		if(typeof(data) == 'object'){
			data_clients = data.data;
			total_items = data.total_items;
			number_link_display = data.number_link_display;
			$("#total_display").empty().append("Total Items: "+total_items);
			generate_sequence_string("current_sequence"); 
			Quicksort.sort(data_clients);
			show_objects_items();
		}
		pagination( "reload_page", "pagination", total_items, page, number_link_display, items_per_page );
		generate_sequence_string('number_display');
		setHash();
	}, "json");
	return false;
}

$(function() {
	$("#appendedInputButton").keyup(function(event) {
  		$("#key_word").val($("#appendedInputButton").val());
		if ( event.which == 13 ) {
     			search_data();
   		}
	});
	$('#mainbuttonsearch').click(function(){
		$("#key_word").val($("#appendedInputButton").val());
		search_data();
		})
	getHash();
	get_objects_items(); 
	$(window).resize(function(){
		change_layout_grid();
	});	
});

function change_layout_grid()
{
	width = $(window).width();
	if ((width>=980) && (status_resize!=1))
	{
		$('.hProductItems div.thumbTitle').attr('style','');
		status_resize = 1;
		fix_products_layout(width);
	}
	else if ((width<980) && (width>=768) && (status_resize!=2))
	{
		$('.hProductItems div.thumbTitle').attr('style','');
		status_resize = 2;
		fix_products_layout(1);
	}
	else if ((width<768) && (status_resize != 3)){
		status_resize = 3;
		$('.hProductItems div.thumbTitle').attr('style','');
		}; 
}


function reload_page(i)
{
	if ((i != null) && typeof(i)=="number" && i>0)
	{
		page = i;
	}
	else page = 1;
	setHash();
	pagination( "reload_page", "pagination", total_items, page, number_link_display, items_per_page );
	generate_sequence_string('number_display');
	show_objects_items();
}

function getHash()
{
	page = getParameter('page');
	if (page == null) page = 1;
	direction = getParameter('direction');
	if (direction == null) direction = 'desc';
	sort_by = getParameter('sortby');
	if (sort_by == null) sort_by = "itm_date";
	items_per_page = getParameter('pageitem');
	if (items_per_page == null) items_per_page = 8;
	type_display = getParameter('typedisplay');
	if (type_display == null) type_display = 0;
	set_value_to_input();
}

function set_value_to_input()
{
	$("#sort_direction").val(sort_by+"|"+direction);
	$("#numberdisplay").val(items_per_page);
	if (type_display == 0) $("#grid_display").click();
	else $('#list_display').click();
}

function setHash()
{
	url = "page="+escape(page);
	url += "&sortby="+escape(sort_by);
	url += "&direction="+escape(direction);
	url += "&pageitem="+escape(items_per_page);
	url += "&typedisplay="+escape(type_display);	
	window.location.hash = url;
}

function search_data()
{
	setHash();
	get_objects_items();
}

function generate_sequence_string(tab)
{
	var first = 0;
	first = ((page-1)*items_per_page)+1;
	var last = 0;
	last = (page)*items_per_page;
	if (total_items == 0) 
	{
		first = 0;
		last = 0;
		$("#"+tab).empty();
		return;
	}
	if (page*items_per_page > total_items)
	{
		$("#"+tab).empty().append("Showing "+first +" to "+total_items+" of "+total_items+" entries");
	}
	else
	{
		$("#"+tab).empty().append("Showing "+first +" to "+last+" of "+total_items+" entries");
	}
}
function searchKeyPress(e)
{
	// look for window.event in case event isn't passed in
	if (typeof e == 'undefined' && window.event) { e = window.event; }
	if (e.keyCode == 13)
	{
		search_data();
	}
	else
	{
		setHash();
	}
}

//
function fix_products_layout(width)
{
	var arr = $('#item_list .thumbTitle');
	if (arr.length>0)
	{
		var i;
		for (i = 0;i<arr.length;i++)
		{
			if ($(arr[i]).height() > 30)
			{
				var new_height = $(arr[i]).height();
				var position = $(arr[i]).position();
				var top = position.top;
				
				var m = i+1;
				while ((m<(i+4)) && (m<arr.length))
				{
					var mposition = $(arr[m]).position();
					if ((top < mposition.top+5)&& (top > mposition.top-5) && (new_height < $(arr[m]).height()))
					new_height = $(arr[m]).height();
					m++;
				}
				$(arr[i]).height(new_height);
				
				var k = i-1;
				while ((k>i-4) && (k>=0))
				{
					var position_compare = $(arr[k]).position();
					if ((position_compare.top < top+5)&&(position_compare.top > top -5 ))
					{
						$(arr[k]).height(new_height);
					}
					k--;
				}
				
				var j = i+1;
				var i_step = 0;
				while (j<(i+4) && (j<arr.length))
				{
					var position_compare = $(arr[j]).position();
					if ((position_compare.top < top+5)&&(position_compare.top > top -5 ))
					{
						$(arr[j]).height(new_height);
						i_step++;
					}
					j++;
				}
				i += i_step;
			}
		}
	}
}

//Quick Sort
var Quicksort = (function() {

  function swap(array, indexA, indexB) {
    var temp = array[indexA];
    array[indexA] = array[indexB];
    array[indexB] = temp;
  }
  function partition(array, pivot, left, right) {
 
    var storeIndex = left,
        pivotValue = array[pivot][sort_by];
 
    swap(array, pivot, right);

    for(var v = left; v < right; v++) {
      if ((array[v][sort_by] < pivotValue) && (direction == 'asc')) {
        swap(array, v, storeIndex);
        storeIndex++;
      }
	  if ((array[v][sort_by] > pivotValue) && (direction == 'desc')) {
        swap(array, v, storeIndex);
        storeIndex++;
      }
    }
    swap(array, right, storeIndex);
 
    return storeIndex;
  }
 
  function sort(array, left, right) {
 
    var pivot = null;
 
    if(typeof left !== 'number') {
      left = 0;
    }
 
    if(typeof right !== 'number') {
      right = array.length - 1;
    }
    if(left < right) {
      pivot     = left + Math.ceil((right - left) * 0.5);
      newPivot  = partition(array, pivot, left, right);
      sort(array, left, newPivot - 1);
      sort(array, newPivot + 1, right);
    }
 
  }
 
  return {
    sort: sort
  };
 
})();

