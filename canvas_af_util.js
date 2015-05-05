function calc_backgroud_size(g_G)
{
	g_G.W = g_G.canvasDiv.clientWidth;
	g_G.H = g_G.canvasDiv.clientHeight;
	
}

// 배경에서의 상대적인 위치를 구한다. x는 100을 기준으로의 위치
function jX(g_G,  x ,w)
{
	// 100 : x = g_G.W : ?
	// ? = x* g_G.W / 100;
	var _x = x * g_G.W / 100;
	return _x - w/2;
}
function jY(g_G,  y ,h )
{
	var _y = y * g_G.H / 100;
	return _y - h/2;
}

function drawImageV(g_G, Image , x, y , w, h)
{
	x = jX(g_G,  x ,w );
	y = jY(g_G,  y , h);
	g_G.context.drawImage(Image, x,y ,w,h );
}


function draw_background(g_G,w,h,color)
{
	g_G.canvas.setAttribute('width', w);
	g_G.canvas.setAttribute('height', h);
	
	g_G.context.beginPath();
	g_G.context.moveTo(0,0)
	g_G.context.lineTo(0,h);
	g_G.context.lineTo(w,h);
	g_G.context.lineTo(w,0);
	g_G.context.fillStyle = color;
	g_G.context.fill();
}

function clearCanvas(w,h)
{
	g_G.context.clearRect(0, 0, w,h);
}

function load_image(g_G , img)
{
	
	img.Image = new Image();
	img.Image.onload = function () 
	{
		if(++g_G.loaded_img < g_G.img_list.length) return;
		img.is_loaded = true;
		main_redraw(g_G);
	};
	img.Image.src = img.src;		
}
function draw_box(g_G, x , y , x2, y2 ,color)
{
	g_G.context.beginPath();
	g_G.context.rect(x, y , x2, y2);
	g_G.context.closePath();
	g_G.context.fillStyle = color;
	g_G.context.fill();		
	
}
function draw_line(g_G, x, y, x2, y2 , strokeStyle , width)
{
	g_G.context.beginPath();
	g_G.context.moveTo(x,y);
	g_G.context.lineTo(x2,y2);
	g_G.context.closePath();
	g_G.context.strokeStyle = strokeStyle;
	g_G.context.lineJoin = "round";
	g_G.context.lineWidth = width;
	g_G.context.stroke();
	
}
// 가상화면을 기준으로 그린다. 100 x 100
function draw_lineV(g_G, x, y, x2, y2 , strokeStyle , width)
{
	//console.log('prev x = ', x , ' y = ' , y  , ' x2= ', x2 , ' y2= ' ,y2);
	x = jX(g_G,  x , 0 );
	y = jY(g_G,  y , 0 );
	x2 = jX(g_G,  x2 , 0 );
	y2 = jY(g_G,  y2 , 0 );
	//console.log('after x = ', x , ' y = ' , y  , ' x2= ', x2 , ' y2= ' ,y2);
	
	draw_line(g_G, x, y, x2, y2 , strokeStyle , width);
}

function main_redraw(g_G)
{
	calc_backgroud_size(g_G);
	clearCanvas(g_G.W, g_G.H);
	draw_background(g_G , g_G.W , g_G.H , "#f1f100" );

	g_G.line_list.forEach(function(line){
		draw_lineV(g_G, line.x, line.y, line.x2, line.y2 ,line.color , line.width);
	});

	g_G.img_list.forEach(function(img){
		if(img.is_loaded== false) return;
		img.draw_list.forEach(function(p){
			
			if(p.is_draw)drawImageV(g_G, img.Image , p.x, p.y , p.w, p.h);
		});
	});
	
	//draw_box (g_G ,10, 10, 20 ,20 ,'#333333');
	//draw_line(g_G, 10, 30, 50, 50 ,'white' , 5);
	
	g_G.context.lineWidth=1;
	g_G.context.fillStyle="#CC00FF";
	g_G.context.lineStyle="#ffff00";
	g_G.context.font="18px sans-serif";
	g_G.context.fillText("Fill Text, 18px, sans-serif", 20, 20);


	
}


