var status_resize = -1;
$(function(){
	$('.itm-wrapper').first().each(function(){
			while ($(this).find('li').length > 4)
			{
				var $newdiv = $('<div class="itm-wrapper clearfix"/>');
				for (var i = 1; i<= 4;i++)
				{
					$newdiv.append($(this).find('li').first().clone());
					$(this).find('li').first().remove();
				}
				$newdiv.insertBefore($(this));
			}
	});
	$(window).resize(function(){
		change_layout();
	});	
})

function change_layout()
{
	var width = $(window).width();
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
		fix_products_layout(null);
		}; 
}

$(window).bind("load", function() {
   change_layout();
});

function fix_products_layout(width)
{
	if (width == null)
	{
		var arr = $('.hProductItems div.thumbTitle');
		for (i=0;i<arr.length;i++)
		$(arr[i]).attr('style','');
	}
	else
	{
	var arr = $('.hProductItems div.thumbTitle');
	if (arr.length>0)
	{
		for (i = 0;i<arr.length;i++)
		{
			if ($(arr[i]).height() > 30)
			{
				var new_height = $(arr[i]).height();
				var position = $(arr[i]).position();
				var top = position.top;
				var m = i+1;
				while (m<(i+4) && (m<arr.length))
				{
					var mposition = $(arr[m]).position();
					if ((top < mposition.top+5)&& (top > mposition.top-5) && (new_height < $(arr[m]).height())) new_height = $(arr[m]).height();
					m++;
				}
				$(arr[i]).height(new_height);

				var k = i-1;
				while ((k>i-4) && (k>=0))
				{
					var position_compare = $(arr[k]).position();
					if (position_compare.top < top+5 && position_compare.top>top-5)
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
					if (position_compare.top < top+5 && position_compare.top>top-5)
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
}