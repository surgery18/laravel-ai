var wall_width = 30;
var wall_height = 30;
var start_radius = 10;
var end_width = 20;
var end_height = 20;

var mutRate = 0.25;


function collision(point, pw, ph, wall, ww, wh) {
	var left = point.x;
	var right = point.x + pw;
	var top = point.y;
	var bottom = point.y + ph;
	var left2 = wall[0];
	var right2 = wall[0] + ww;
	var top2 = wall[1];
	var bottom2 = wall[1] + wh;

	return left < right2
	&& right > left2
	&& top < bottom2
	&& bottom > top2;
};

//https://stackoverflow.com/questions/21089959/detecting-collision-of-rectangle-with-circle
function crCollision(circle, radius, rect, rw, rh) {
	var distX = Math.abs(circle[0] - rect[0]-rw/2);
	var distY = Math.abs(circle[1] - rect[1]-rh/2);

	if (distX > (rw/2 + radius)) { return false; }
	if (distY > (rh/2 + radius)) { return false; }

	if (distX <= (rw/2)) { return true; }
	if (distY <= (rh/2)) { return true; }

	var dx=distX-rw/2;
	var dy=distY-rh/2;
	return (dx*dx+dy*dy<=(radius*radius));
}

function dist(px, py, p2x, p2y) {
	var a = px - p2x;
	var b = py - p2y;
	var c = Math.sqrt(a*a + b*b);
	return c;
}
