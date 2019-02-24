function Dot(start_pos = [0.0,0.0]) {
	this.pos = JSON.parse(JSON.stringify(start_pos));
	this.vel = [0.0,0.0];
	this.acc = [0.0,0.0];
	this.brain = new Brain(1000);
	this.dead = false;
	this.reachedGoal = false;
	this.isBest = false;
	this.fitness = 0;
	this.start_pos = start_pos;
	this.diedByWall = false;
}
Dot.prototype.draw = function(ctx) {
	ctx.beginPath();
	ctx.arc(this.pos[0], this.pos[1], start_radius, 0, 2 * Math.PI, false);
	if (this.isBest) {
		ctx.fillStyle = 'orange';
	} else {
		ctx.fillStyle = 'red';
	}
	ctx.fill();
};
Dot.prototype.move = function() {
	if (this.brain.directions.length > this.brain.step) {
		this.acc = this.brain.directions[this.brain.step];
		++this.brain.step;
	} else {
		this.dead = true;
	}
	this.vel[0] += this.acc[0];
	this.vel[1] += this.acc[1];
	// var limit = 5;
	// if (Math.abs(this.vel[0]) >= limit) {
	// 	var neg = this.vel[0] < 0;
	// 	this.vel[0] = limit;
	// 	if (neg) this.vel[0] = -this.vel[0];
	// }
	// if (Math.abs(this.vel[1]) >= limit) {
	// 	var neg = this.vel[1] < 0;
	// 	this.vel[1] = limit;
	// 	if (neg) this.vel[1] = -this.vel[1];
	// }
	this.pos[0] += this.vel[0];
	this.pos[1] += this.vel[1];
};
Dot.prototype.update = function(goal, walls) {
	var _this = this;
	if (!this.dead && !this.reachedGoal) {
		this.move();
		if(this.pos[0] < 2 || this.pos[1] < 2 || this.pos[0] > 638 || this.pos[1] > 478) {
			this.dead = true;
		} else if (crCollision(this.pos, start_radius, goal, end_width, end_height)) {
			this.reachedGoal = true;
		} else if (walls.findIndex(function(wall) {
			return crCollision(_this.pos, start_radius, wall, wall_width, wall_height);
		}) > -1) {
			this.dead = true;
			this.diedByWall = true;
		}
	}
};
Dot.prototype.calculateFitness = function(goal) {
	if (this.reachedGoal) {
		this.fitness = 1.0/16.0 + 10000 / (this.brain.step*this.brain.step);
	} else {
		var distToGoal = dist(this.pos[0], this.pos[1], goal[0], goal[1]);
		if (this.diedByWall) {
			// distToGoal *= 1.5; //0.9
		}
		this.fitness = 1.0/(distToGoal * distToGoal);
	}
};
Dot.prototype.createBaby = function() {
	var baby = new Dot(this.start_pos);
	baby.brain = this.brain.clone();
	return baby;
};
