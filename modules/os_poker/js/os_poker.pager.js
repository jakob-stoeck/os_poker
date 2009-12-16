
function	os_poker_init_pager(target_item)
{
	var elems;

	if (typeof(target_item) != "undefined")
	{
		elems = target_item.find(".ajax-pager .pager a");
	}
	else
	{
		elems = $(".ajax-pager .pager a");
	}
	
	elems.each(function() {
		var target = $(this).attr("href");
		var container = $(this).closest(".ajax-pager").parent();
		
		$(this).attr("href", "javascript:void(0);");
		
		$(this).click(function () {
			$.get(target, function (data) { 
				container.html(data);
				os_poker_init_pager(container);
			});
		});
	});
	
}

