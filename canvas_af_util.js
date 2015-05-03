function calc_backgroud_size(g_G)
{
	g_G.W = g_G.canvasDiv.clientWidth;
	g_G.H = g_G.canvasDiv.clientHeight;
	
}

// 배경에서의 상대적인 위치를 구한다. x는 100을 기준으로의 위치
function jX(g_G, Image, x ,w)
{
	// 100 : x = g_G.W : ?
	// ? = x* g_G.W / 100;
	var _x = x * g_G.W / 100;
	return _x - w/2;
}
function jY(g_G, Image, y ,h )
{
	var _y = y * g_G.H / 100;
	return _y - h/2;
}

function drawImageV(g_G, Image , x, y , w, h)
{
	x = jX(g_G, Image, x ,w );
	y = jY(g_G, Image, y , h);
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
		img.is_loaded = true;
		main_redraw(g_G);
	};
	img.Image.src = img.src;		
}


function main_redraw(g_G)
{
	calc_backgroud_size(g_G);
	clearCanvas(g_G.W, g_G.H);
	draw_background(g_G , g_G.W , g_G.H , "#f1f100" );
	
	g_G.img_list.forEach(function(img){
		if(img.is_loaded== false) return;
		img.draw_list.forEach(function(p){
		
			if(p.is_draw)drawImageV(g_G, img.Image , p.x, p.y , p.w, p.h);
		});
	});
}


