var g_G = {
	canvas:null,
	context:null,
	canvasDiv:null,
	img_list : [],
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
	
	g_G.img_list =
	[
	{
		name : 'j_server',
		src : "img/j_server.png",
		
		draw_list : [
			{is_draw : true , x : 25,y : 50,w : 80 , h : 100,},
			{is_draw : true , x : 75,y : 50,w : 80 , h : 100,},
		]
	},
	{
		name : 'j_db2',
		src : "img/j_db2.png",
		
		draw_list : [
			{is_draw : true , x : 25,y : 10,w : 100 , h : 100,},
			{is_draw : true , x : 75,y : 10,w : 100 , h : 100,},
		]
	},
	{
		name : 'j_hdd',
		src : "img/j_hdd.png",
		
		draw_list : [
			{is_draw : true , x : 25,y : 10,w : 100 , h : 100,},
			{is_draw : true , x : 75,y : 10,w : 100 , h : 100,},
		]
	},	
	];
	
	g_G.img_list.forEach(function(img)
	{
		load_image(g_G , img);
	});
	
}
