
function get_shared1_line_list(g_G)
{
	var line_list = 
	[
	{x:25, y:37, x2:75, y2:37 ,color:'black' , width:5},
	{x:25, y:42, x2:75, y2:42 ,color:'black' , width:5},
	{x:25, y:42, x2:50, y2:70 ,color:'black' , width:5},
	{x:75, y:42, x2:50, y2:70 ,color:'black' , width:5},
	];
		
	return line_list;
	
}

function get_shared1_image_list()
{
	
	var img_list =
	[
	{
		name : 'j_server',
		src : "img/j_server.png",
		
		draw_list : [
		{is_draw : true , x : 25,y : 40,w : 120 , h : 150,},
		{is_draw : true , x : 75,y : 40,w : 120 , h : 150,},
		]
	},
	{
		name : 'j_hdd',
		src : "img/j_hdd.png",
		
		draw_list : [
		{is_draw : true , x : 49,y : 70,w : 120 , h : 120,},
		]
	},	
	];
	
	return img_list;
}


function get_img_shared_bg(g_G)
{
	
	var img_list =
	[
		{
		name : 'bg_share2',
		src : "img/bg_share2.png",
		
		draw_list : [
		{is_draw : true , x : 50,y : 50,w : g_G.W , h : g_G.H,},
		]
		},
	];
	
	return img_list;
}
