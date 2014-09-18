function topNavToSelect() {
	// building select menu
	$('<select class="upper-nav" />').appendTo('.upperHeader .container');

	// building an option for select menu
	$('<option />', {
		'selected': 'selected',
		'value' : '',
		'text': 'Choise Page...'
	}).appendTo('.upperHeader .container select');

	$('.upperHeader ul.inline li a').each(function(){
		var target = $(this);

		$('<option />', {
			'value' : target.attr('href'),
			'text': target.text()
		}).appendTo('.upperHeader .container select');
	});
	// on clicking on link
	$('.upperHeader .container select').on('change',function(){
		window.location = $(this).find('option:selected').val();
	});
}

// building select .navbar for mobile width only
function NavToSelect() {

	// building select menu
	$('<select />').appendTo('.navbar');

	// building an option for select menu
	$('<option />', {
		'selected': 'selected',
		'value' : '',
		'text': 'Choise Page...'
	}).appendTo('.navbar select');

	$('.navbar ul li a').each(function(){
		var target = $(this);

		$('<option />', {
			'value' : target.attr('href'),
			'text': (target.attr('pre')+target.text())=="undefined"?"Home":target.attr('pre')+target.text()
		}).appendTo('.navbar select');
	});
	// on clicking on link
	$('.navbar select').on('change',function(){
		var str = "" + $(this).find('option:selected').attr('value');
		if (str != "undefined")
		window.location = $(this).find('option:selected').val();
	});

}


// bootstrap tooltip invocking
function showtooltip() {
	$('a[data-toggle=tooltip], button[data-toggle=tooltip], input[data-toggle=tooltip]')
	.tooltip({
		animation:false
	});
}

function cartContent() {
	var $cartContent = $('.cart-content');
	$cartContent.find('table').click(function(e){
		e.stopPropagation();
	});
}

// flexslider on home page
function flexSlideShow() {
	$('.flexslider').flexslider({
		 controlNav:false,
		 animation: "slide",
		 slideshowSpeed: 4000,
		 directionNav: false,
		 pauseOnHover: true,
		 directionNav: false
	});
}

// bootstrap carousel in caregories grid and list
function productSlider() {
	$('.carousel').carousel();
}


// link fancybox plugin on product detail
function productFancyBox() {
	$(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});
}

// dropdown mainnav
function dropdownMainNav() {
	var navLis = $('div.navbar > ul.nav > li');
	navLis.hover(
		function () {
			// hide the css default behavir
			$(this).children('div').css('display', 'none');
			//show its submenu
			$(this).children('div').slideDown(150);
		}, 
		function () {
			//hide its submenu
			$(this).children('div').slideUp(350);		
		}
	);

}


// range price product
function rangePriceSlider() {
	var $slideRange = $("#slider-range"),
		amount = $( "#amount" );
	$slideRange.slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 75, 300 ],
      slide: function( event, ui ) {
        amount.val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
      }
    });
    amount.val( "$" + $slideRange.slider( "values", 0 ) +
      " - $" + $slideRange.slider( "values", 1 ) );
}


$(document).ready(function(){
	topNavToSelect();
	NavToSelect();
	//showtooltip();
	cartContent();
	flexSlideShow();
	//productSlider();
	//productFancyBox();
	dropdownMainNav();
	//latestTweets();
	//openSidePanel();
	//changeBackgroundPattern();
	//changeLayoutStyle();
	//changeColorStyle();
	//rangePriceSlider();
});

function searchPress(e)
{
	// look for window.event in case event isn't passed in
	if (typeof e == 'undefined' && window.event) { e = window.event; }
	if (e.keyCode == 13)
	{
		search_data_main();
	}
}

function search_data_main()
{
	window.location = url_server__+"shop/itemsearch#key="+escape($('#appendedInputButton').val());
}

Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
 
 function showPopup(element){
    $(element).on('hover',function(){
       $(this).tooltip();
    }).trigger('hover');
}