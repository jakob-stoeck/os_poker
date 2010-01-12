
function	os_poker_slider_reset(elem)
{
	elem.css("position", "absolute");
	elem.css("left", "0px");
	var vis = 0;
	var children = elem.children(":visible").each(function(){
    vis += $(this).outerWidth();
  });
  elem.css("width", vis + "px");
}

/*
**
*/

function	os_poker_slider_get_page(elem)
{
	var cursor = elem.find(".cursor");
	
	var pos = parseInt(cursor.css("left"));
	var children = cursor.children();
	var fc = children.get(0);
	var step = $(fc).innerWidth( );
	var index = -pos / step;
	return children.get(index);
}

/*
**
*/

function	os_poker_register_slider(elem)
{
	var next = elem.find(".next");
	var previous = elem.find(".previous");
	var cursor = elem.find(".cursor");
	
	os_poker_slider_reset(cursor);
	
	next.bind("click", cursor, function(event) {
		var pos = parseInt(event.data.css("left"));
		var children = event.data.children(":visible");
		var fc = children.get(0);
		var step = $(fc).innerWidth( );
		var vis = children.length * step;
		var nextpos = pos - step;
		var max = event.data.parent().width();
		
		event.data.css("width", vis + "px");
		
		if (vis + nextpos >= max)
		{
			event.data.css("left", nextpos + "px");
		}
		return false;
	});	
	
	previous.bind("click", cursor, function(event) {
		var pos = parseInt(event.data.css("left"));
		var children = event.data.children(":visible");
		var fc = children.get(0);
		var step = $(fc).innerWidth( );
		var nextpos = pos + step;
		
		if (nextpos <= 0)
		{
			event.data.css("left", nextpos + "px");
		}
		return false;
	});

}