
function	os_poker_slider_reset(elem, step)
{
	elem.css("position", "absolute");
	elem.css("left", "0px");
	
	var children = elem.children(":visible");
	var vis = children.length * step;
	elem.css("width", vis + "px");
}

/*
**
*/

function	os_poker_slider_get_page(elem, step)
{
	var cursor = elem.find(".cursor");
	var pos = parseInt(cursor.css("left"));
	var index = -pos / step;
	var children = cursor.children();
	
	return children.get(index);
}

/*
**
*/

function	os_poker_register_slider(elem, step)
{
	var next = elem.find(".next");
	var previous = elem.find(".previous");
	var cursor = elem.find(".cursor");
	
	os_poker_slider_reset(cursor);
	
	next.bind("click", cursor, function(event) {
		var pos = parseInt(event.data.css("left"));
		var children = event.data.children(":visible");
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
		var nextpos = pos + step;
		
		if (nextpos <= 0)
		{
			event.data.css("left", nextpos + "px");
		}
		return false;
	});

}