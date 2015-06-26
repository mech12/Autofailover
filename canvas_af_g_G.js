var g_posLeft = 20;
var g_posLeft2 = 30;

var g_posRight = 100 - g_posLeft;
var g_posRight2 = 100 - g_posLeft2;
var g_drive_offset = 10;

var g_status_btn_list_shared =
[
    {nic:'Server1',id:'#btn_id_server1-1', x:g_posLeft+5, y:80},
    //{nic:'Server2' ,id:'#btn_id_server2-1', x:g_posLeft2, y:80},

    {nic:'DISK1' ,id:'#btn_id_disk1-1', x:g_posLeft, y:65},
    {nic:'DISK2' ,id:'#btn_id_disk2-1', x:g_posLeft2, y:65},

    {nic:'VIP1' ,id:'#btn_id_vip1-1', x:g_posLeft, y:20},
    {nic:'VIP2' ,id:'#btn_id_vip2-1', x:g_posLeft2, y:20},

    {nic:'DB1' ,id:'#btn_id_db1-1', x:g_posLeft, y:35},
    {nic:'DB2' ,id:'#btn_id_db2-1', x:g_posLeft2, y:35},

    {nic:'APP1' ,id:'#btn_id_app1-1', x:g_posLeft, y:50},
    {nic:'APP2' ,id:'#btn_id_app2-1', x:g_posLeft2, y:50},


    //{nic:'Server1',id:'#btn_id_server1-2', x:g_posRight2, y:80},
    {nic:'Server2' ,id:'#btn_id_server2-2', x:g_posRight-5, y:80},

    {nic:'DISK1' ,id:'#btn_id_disk1-2', x:g_posRight2, y:65},
    {nic:'DISK2' ,id:'#btn_id_disk2-2', x:g_posRight, y:65},

    {nic:'VIP1' ,id:'#btn_id_vip1-2', x:g_posRight2, y:20},
    {nic:'VIP2' ,id:'#btn_id_vip2-2', x:g_posRight, y:20},

    {nic:'DB1' ,id:'#btn_id_db1-2', x:g_posRight2, y:35},
    {nic:'DB2' ,id:'#btn_id_db2-2', x:g_posRight, y:35},

    {nic:'APP1' ,id:'#btn_id_app1-2', x:g_posRight2, y:50},
    {nic:'APP2' ,id:'#btn_id_app2-2', x:g_posRight, y:50},
];

function find_btn_info(id)
{
	var result = $.grep(g_status_btn_list_shared, function(e){ return e.id == id; });
	return result[0];
}

function get_shared1_line_list(g_G)
{
	if(g_G.type_pannel=='Mirrored')
	{
		var line_list = 
		[
		{x:25, y:37, x2:75, y2:37 ,color:'black' , width:5},
		{x:25, y:42, x2:75, y2:42 ,color:'black' , width:5},
		{x:25, y:42, x2:25, y2:70 ,color:'black' , width:5},
		{x:75, y:42, x2:75, y2:70 ,color:'black' , width:5},
		];
		
		return line_list;
	}
	else
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
	
	
}

function get_shared1_image_list()
{
	if(g_G.type_pannel=='Mirrored')
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
			{is_draw : true , x : 25,y : 70,w : 120 , h : 120,},
			{is_draw : true , x : 75,y : 70,w : 120 , h : 120,},
			]
		},	
		];
		
		return img_list;
	}
	else
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
	
}

