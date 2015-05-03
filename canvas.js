//http://stackoverflow.com/questions/6998341/javascript-canvas-overlaying-a-transparent-png-on-a-previousley-filled-color



// Copyright 2010 William Malone (www.williammalone.com)
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

var g_canvas;
var g_context;
var iCANVAS_WIDTH = 490;
var iCANVAS_HEIGHT = 220;

var padding = 25;
var lineWidth = 8;
var COLOR_PURPLE = "#cb3594";

var COLOR_GREEN = "#659b41";
var COLOR_YELLOW  = "#ffcf33";
var COLOR_BROWN = "#986928";

var g_outlineImage = new Image();
var g_crayonImage = new Image();
var g_markerImage = new Image();
var g_eraserImage = new Image();
var g_crayonBackgroundImage = new Image();
var g_markerBackgroundImage = new Image();
var g_eraserBackgroundImage = new Image();
var g_crayonTextureImage = new Image();

var clickX = new Array();
var clickY = new Array();
var clickColor = new Array();
var clickTool = new Array();
var g_clickSize = new Array();
var g_clickDrag = new Array();
var g_is_paint = false;
var g_curColor = COLOR_PURPLE;
var g_curTool = "crayon";
var g_curSize = "normal";

var g_mediumStartX = 18;
var g_mediumStartY = 19;
var g_mediumImageWidth = 93;
var g_mediumImageHeight = 46;

var g_drawingAreaX = 111;
var g_drawingAreaY = 11;
var g_drawingAreaWidth = 267;
var g_drawingAreaHeight = 200;

var g_toolHotspotStartY = 23;
var g_toolHotspotHeight = 38;

var g_sizeHotspotStartY = 157;
var g_sizeHotspotHeight = 36;

var g_sizeHotspotWidthObject = new Object();
g_sizeHotspotWidthObject.huge = 39;
g_sizeHotspotWidthObject.large = 25;
g_sizeHotspotWidthObject.normal = 18;
g_sizeHotspotWidthObject.small = 16;


var g_totalLoadResources = 8;
var g_curLoadResNum = 0;
/**
* Calls the redraw function after all neccessary resources are loaded.
*/

/**
* Creates a canvas element, loads images, adds events, and draws the canvas for the first time.
*/
function prepareCanvas(canvasDiv_name) {
    // Create the canvas (Neccessary for IE because it doesn't know what a canvas element is)
    var canvasDiv = document.getElementById(canvasDiv_name);
    g_canvas = document.createElement('canvas');
    g_canvas.setAttribute('width', iCANVAS_WIDTH);
    g_canvas.setAttribute('height', iCANVAS_HEIGHT);
    g_canvas.setAttribute('id', 'canvas');
    canvasDiv.appendChild(g_canvas);
    if (typeof G_vmlCanvasManager != 'undefined') {
        g_canvas = G_vmlCanvasManager.initElement(g_canvas);
    }
    g_context = g_canvas.getContext("2d"); // Grab the 2d canvas g_context
    // Note: The above code is a workaround for IE 8 and lower. Otherwise we could have used:
    //     g_context = document.getElementById('canvas').getContext("2d");

    // Load images
    // -----------
    prepareCanvas_loadImage();

    // Add mouse events
    // ----------------
    prepareCanvas_mouse_event();
}



function prepareCanvas_loadImage()
{
    g_crayonImage.onload = function () {resourceLoaded();};
    g_crayonImage.src = "images/crayon-outline.png";
    //g_context.drawImage(g_crayonImage, 0, 0, 100, 100);

    g_markerImage.onload = function () { resourceLoaded(); };
    g_markerImage.src = "images/marker-outline.png";

    g_eraserImage.onload = function () {resourceLoaded();};
    g_eraserImage.src = "images/eraser-outline.png";

    g_crayonBackgroundImage.onload = function () {resourceLoaded();};
    g_crayonBackgroundImage.src = "images/crayon-background.png";

    g_markerBackgroundImage.onload = function () {resourceLoaded();};
    g_markerBackgroundImage.src = "images/marker-background.png";

    g_eraserBackgroundImage.onload = function () {resourceLoaded();};
    g_eraserBackgroundImage.src = "images/eraser-background.png";

    g_crayonTextureImage.onload = function () {resourceLoaded();};
    g_crayonTextureImage.src = "images/crayon-texture.png";

    g_outlineImage.onload = function () {resourceLoaded();};
    g_outlineImage.src = "images/watermelon-duck-outline.png";

}

function prepareCanvas_mouse_event()
{
    $('#canvas').mousedown(function (e) {
        // Mouse down location
        var mouseX = e.pageX - this.offsetLeft;
        var mouseY = e.pageY - this.offsetTop;

        if (mouseX < g_drawingAreaX) // Left of the drawing area
        {
            if (mouseX > g_mediumStartX) {
                if (mouseY > g_mediumStartY && mouseY < g_mediumStartY + g_mediumImageHeight) {
                    g_curColor = COLOR_PURPLE;
                } else if (mouseY > g_mediumStartY + g_mediumImageHeight && mouseY < g_mediumStartY + g_mediumImageHeight * 2) {
                    g_curColor = COLOR_GREEN;
                } else if (mouseY > g_mediumStartY + g_mediumImageHeight * 2 && mouseY < g_mediumStartY + g_mediumImageHeight * 3) {
                    g_curColor = COLOR_YELLOW;
                } else if (mouseY > g_mediumStartY + g_mediumImageHeight * 3 && mouseY < g_mediumStartY + g_mediumImageHeight * 4) {
                    g_curColor = COLOR_BROWN;
                }
            }
        }
        else if (mouseX > g_drawingAreaX + g_drawingAreaWidth) // Right of the drawing area
        {
            if (mouseY > g_toolHotspotStartY) {
                if (mouseY > g_sizeHotspotStartY) {
                    var sizeHotspotStartX = g_drawingAreaX + g_drawingAreaWidth;
                    if (mouseY < g_sizeHotspotStartY + g_sizeHotspotHeight && mouseX > sizeHotspotStartX) {
                        if (mouseX < sizeHotspotStartX + g_sizeHotspotWidthObject.huge) {
                            g_curSize = "huge";
                        } else if (mouseX < sizeHotspotStartX + g_sizeHotspotWidthObject.large + g_sizeHotspotWidthObject.huge) {
                            g_curSize = "large";
                        } else if (mouseX < sizeHotspotStartX + g_sizeHotspotWidthObject.normal + g_sizeHotspotWidthObject.large + g_sizeHotspotWidthObject.huge) {
                            g_curSize = "normal";
                        } else if (mouseX < sizeHotspotStartX + g_sizeHotspotWidthObject.small + g_sizeHotspotWidthObject.normal + g_sizeHotspotWidthObject.large + g_sizeHotspotWidthObject.huge) {
                            g_curSize = "small";
                        }
                    }
                }
                else {
                    if (mouseY < g_toolHotspotStartY + g_toolHotspotHeight) {
                        g_curTool = "crayon";
                    } else if (mouseY < g_toolHotspotStartY + g_toolHotspotHeight * 2) {
                        g_curTool = "marker";
                    } else if (mouseY < g_toolHotspotStartY + g_toolHotspotHeight * 3) {
                        g_curTool = "eraser";
                    }
                }
            }
        }
        else if (mouseY > g_drawingAreaY && mouseY < g_drawingAreaY + g_drawingAreaHeight) {
            // Mouse click location on drawing area
        }
        g_is_paint = true;
        addClick(mouseX, mouseY, false);
        redraw();
    });//$('#canvas').mousedown()

    $('#canvas').mousemove(function (e) {
        if (g_is_paint == true) {
            addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
            redraw();
        }
    });

    $('#canvas').mouseup(function (e) {
        g_is_paint = false;
        redraw();
    });

    $('#canvas').mouseleave(function (e) {
        g_is_paint = false;
    });

}

/**
* Adds a point to the drawing array.
* @param x
* @param y
* @param dragging
*/
function addClick(x, y, dragging)
{
	clickX.push(x);
	clickY.push(y);
	clickTool.push(g_curTool);
	clickColor.push(g_curColor);
	g_clickSize.push(g_curSize);
	g_clickDrag.push(dragging);
}

/**
* Clears the canvas.
*/
function clearCanvas()
{
	g_context.clearRect(0, 0, iCANVAS_WIDTH, iCANVAS_HEIGHT);
}

function redraw_crayon_pen(locX, locY, color) {
    g_context.beginPath();
    g_context.moveTo(locX + 41, locY + 11);
    g_context.lineTo(locX + 41, locY + 35);
    g_context.lineTo(locX + 29, locY + 35);
    g_context.lineTo(locX + 29, locY + 33);
    g_context.lineTo(locX + 11, locY + 27);
    g_context.lineTo(locX + 11, locY + 19);
    g_context.lineTo(locX + 29, locY + 13);
    g_context.lineTo(locX + 29, locY + 11);
    g_context.lineTo(locX + 41, locY + 11);
    g_context.closePath();
    g_context.fillStyle = color;
    g_context.fill();

    if (g_curColor == color) {
        g_context.drawImage(g_crayonImage, locX, locY, g_mediumImageWidth, g_mediumImageHeight);
    } else {
        g_context.drawImage(g_crayonImage, 0, 0, 59, g_mediumImageHeight, locX, locY, 59, g_mediumImageHeight);
    }
}

function redraw_crayon(locX,locY)
{
		// Draw the crayon tool background
	g_context.drawImage(g_crayonBackgroundImage, 0, 0, iCANVAS_WIDTH, iCANVAS_HEIGHT);
	
	// Purple
	locX = (g_curColor == COLOR_PURPLE) ? 18 : 52;
	locY = 19;
    redraw_crayon_pen(locX, locY ,COLOR_PURPLE);

    // Green
	locX = (g_curColor == COLOR_GREEN) ? 18 : 52;
	locY += 46;

    redraw_crayon_pen(locX, locY ,COLOR_GREEN);

	// Yellow
	locX = (g_curColor == COLOR_YELLOW ) ? 18 : 52;
	locY += 46;

    redraw_crayon_pen(locX, locY ,COLOR_YELLOW);

	// Yellow
	locX = (g_curColor == COLOR_BROWN) ? 18 : 52;
	locY += 46;

    redraw_crayon_pen(locX, locY ,COLOR_BROWN);

}

function redraw_marker(locX , locY)
{
	// Draw the marker tool background
	g_context.drawImage(g_markerBackgroundImage, 0, 0, iCANVAS_WIDTH, iCANVAS_HEIGHT);
	
	// Purple
	locX = (g_curColor == COLOR_PURPLE) ? 18 : 52;
	locY = 19;
	
	g_context.beginPath();
	g_context.moveTo(locX + 10, locY + 24);
	g_context.lineTo(locX + 10, locY + 24);
	g_context.lineTo(locX + 22, locY + 16);
	g_context.lineTo(locX + 22, locY + 31);
	g_context.closePath();
	g_context.fillStyle = COLOR_PURPLE;
	g_context.fill();	

	if(g_curColor == COLOR_PURPLE){
		g_context.drawImage(g_markerImage, locX, locY, g_mediumImageWidth, g_mediumImageHeight);
	}else{
		g_context.drawImage(g_markerImage, 0, 0, 59, g_mediumImageHeight, locX, locY, 59, g_mediumImageHeight);
	}
	
	// Green
	locX = (g_curColor == COLOR_GREEN) ? 18 : 52;
	locY += 46;
	
	g_context.beginPath();
	g_context.moveTo(locX + 10, locY + 24);
	g_context.lineTo(locX + 10, locY + 24);
	g_context.lineTo(locX + 22, locY + 16);
	g_context.lineTo(locX + 22, locY + 31);
	g_context.closePath();
	g_context.fillStyle = COLOR_GREEN;
	g_context.fill();	

	if(g_curColor == COLOR_GREEN){
		g_context.drawImage(g_markerImage, locX, locY, g_mediumImageWidth, g_mediumImageHeight);
	}else{
		g_context.drawImage(g_markerImage, 0, 0, 59, g_mediumImageHeight, locX, locY, 59, g_mediumImageHeight);
	}
	
	// Yellow
	locX = (g_curColor == COLOR_YELLOW ) ? 18 : 52;
	locY += 46;
	
	g_context.beginPath();
	g_context.moveTo(locX + 10, locY + 24);
	g_context.lineTo(locX + 10, locY + 24);
	g_context.lineTo(locX + 22, locY + 16);
	g_context.lineTo(locX + 22, locY + 31);
	g_context.closePath();
	g_context.fillStyle = COLOR_YELLOW ;
	g_context.fill();	

	if(g_curColor == COLOR_YELLOW ){
		g_context.drawImage(g_markerImage, locX, locY, g_mediumImageWidth, g_mediumImageHeight);
	}else{
		g_context.drawImage(g_markerImage, 0, 0, 59, g_mediumImageHeight, locX, locY, 59, g_mediumImageHeight);
	}
	
	// Yellow
	locX = (g_curColor == COLOR_BROWN) ? 18 : 52;
	locY += 46;
	
	g_context.beginPath();
	g_context.moveTo(locX + 10, locY + 24);
	g_context.lineTo(locX + 10, locY + 24);
	g_context.lineTo(locX + 22, locY + 16);
	g_context.lineTo(locX + 22, locY + 31);
	g_context.closePath();
	g_context.fillStyle = COLOR_BROWN;
	g_context.fill();	

	if(g_curColor == COLOR_BROWN){
		g_context.drawImage(g_markerImage, locX, locY, g_mediumImageWidth, g_mediumImageHeight);
	}else{
		g_context.drawImage(g_markerImage, 0, 0, 59, g_mediumImageHeight, locX, locY, 59, g_mediumImageHeight);
	}

}

function calc_radius(currSize ) {
    var radius =0;
    if (currSize  == "small") {
        radius = 2;
    } else if (currSize  == "normal") {
        radius = 5;
    } else if (currSize  == "large") {
        radius = 10;
    } else if (currSize  == "huge") {
        radius = 20;
    } else {
        alert("Error: Radius is zero for click " + i);
        radius = 0;
    }
    return radius;
}


function resourceLoaded()
{
	if(++g_curLoadResNum >= g_totalLoadResources){
		redraw();
	}
}


/**
* Redraws the canvas.
*/
function redraw()
{
	// Make sure required resources are loaded before redrawing
	if(g_curLoadResNum < g_totalLoadResources){ return; }
	
	clearCanvas();
	
	var locX;
	var locY;
	if(g_curTool == "crayon")
	{
		redraw_crayon(locX , locY);
	}
	else if(g_curTool == "marker")
	{
		redraw_marker(locX , locY);
	}
	else if(g_curTool == "eraser")
	{
		g_context.drawImage(g_eraserBackgroundImage, 0, 0, iCANVAS_WIDTH, iCANVAS_HEIGHT);
		g_context.drawImage(g_eraserImage, 18, 19, g_mediumImageWidth, g_mediumImageHeight);	
	}else{
		alert("Error: Current Tool is undefined");
	}
	
	if(g_curSize == "small"){
		locX = 467;
	}else if(g_curSize == "normal"){
		locX = 450;
	}else if(g_curSize == "large"){
		locX = 428;
	}else if(g_curSize == "huge"){
		locX = 399;
	}
	locY = 189;
	g_context.beginPath();
	g_context.rect(locX, locY, 2, 12);
	g_context.closePath();
	g_context.fillStyle = '#333333';
	g_context.fill();	
	
	// Keep the drawing in the drawing area
	g_context.save();
	g_context.beginPath();
	g_context.rect(g_drawingAreaX, g_drawingAreaY, g_drawingAreaWidth, g_drawingAreaHeight);
	g_context.clip();
		
	var radius;
	var i = 0;
	for(; i < clickX.length; i++)
	{
        radius = calc_radius(g_clickSize[i] );

        g_context.beginPath();
		if(g_clickDrag[i] && i){
			g_context.moveTo(clickX[i-1], clickY[i-1]);
		}else{
			g_context.moveTo(clickX[i], clickY[i]);
		}
		g_context.lineTo(clickX[i], clickY[i]);
		g_context.closePath();
		
		if(clickTool[i] == "eraser"){
			//g_context.globalCompositeOperation = "destination-out"; // To erase instead of draw over with white
			g_context.strokeStyle = 'white';
		}else{
			//g_context.globalCompositeOperation = "source-over";	// To erase instead of draw over with white
			g_context.strokeStyle = clickColor[i];
		}
		g_context.lineJoin = "round";
		g_context.lineWidth = radius;
		g_context.stroke();
		
	}
	//g_context.globalCompositeOperation = "source-over";// To erase instead of draw over with white
	g_context.restore();
	
	// Overlay a crayon texture (if the current tool is crayon)
	if(g_curTool == "crayon"){
		g_context.globalAlpha = 0.4; // No IE support
		g_context.drawImage(g_crayonTextureImage, 0, 0, iCANVAS_WIDTH, iCANVAS_HEIGHT);
	}
	g_context.globalAlpha = 1; // No IE support
	
	// Draw the outline image
	g_context.drawImage(g_outlineImage, g_drawingAreaX, g_drawingAreaY, g_drawingAreaWidth, g_drawingAreaHeight);
}


/**/