var g_G = {
	canvas:null,
	context:null,
	canvasDiv:null,
	loaded_img : 0,
	img_list : [],
	line_list : [],
	find_img : function(name)
	{
		var tot = img_list.length;
		for(var i=0; i< tot ; ++i)
		{
			var img = img_list[i];
			if( img.name == name) return img;
		}
	}
};
var g_outlineImage = new Image();

function Draw_Main(canvasDiv_name)
{
	
	g_G.canvasDiv = document.getElementById(canvasDiv_name),
	g_G.canvas = document.createElement('canvas');
	g_G.canvasDiv.appendChild(g_G.canvas);
	if (typeof G_vmlCanvasManager != 'undefined') {
		g_G.canvas = G_vmlCanvasManager.initElement(g_G.canvas);
	}
	g_G.canvas.setAttribute('id', 'canvas');
	g_G.context = g_G.canvas.getContext("2d"); // Grab the 2d canvas g_G.context
	// Note: The above code is a workaround for IE 8 and lower. Otherwise we could have used:
	//     g_G.context = document.getElementById('canvas').getContext("2d");
	
	//화면 크기.
	calc_backgroud_size(g_G);
	
	
	g_G.img_list = get_shared1_image_list(g_G);
	g_G.line_list = get_shared1_line_list(g_G);
	
	//g_G.img_list = get_img_shared_bg(g_G);
	
	g_G.img_list.forEach(function(img)
	{
		load_image(g_G , img);
	});
	
}


