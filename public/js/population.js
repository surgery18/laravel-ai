var counter = 0;
function Population(num_dots = 100, start_pos) {
	this.dots = [];
	this.fitnessSum = 0;
	this.gen = 1;
	this.bestDot = 0;
	this.minStep = 250;
	this.bestSteps = 0;
	this.play = true;
	this.num_dots = num_dots;
	this.start_pos = start_pos;

	for(var i = 0; i < num_dots; i++) {
		this.dots.push(new Dot(start_pos));
	}
}
Population.prototype.draw = function(ctx) {
	for(var i = 1; i < this.dots.length; i++) {
		this.dots[i].draw(ctx);
	}
	this.dots[0].draw(ctx);
};
Population.prototype.update = function(goal, walls) {
	if (this.play) {
		for(var i = 0; i < this.dots.length; i++) {
			if (this.dots[i].brain.step > this.minStep) {
				this.dots[i].dead = true;
			} else {
				this.dots[i].update(goal, walls);
			}
		}
	}
};
Population.prototype.calculateFitness = function(goal) {
	for(var i = 0; i < this.dots.length; i++) {
		this.dots[i].calculateFitness(goal);
	}
};
Population.prototype.allDotsDead = function() {
	for (var i = 0; i < this.dots.length; i++) {
		if (!this.dots[i].dead && !this.dots[i].reachedGoal) {
			return false;
		}
	}
	return true;
};
Population.prototype.naturalSelection = function() {
	var newDots = [];
	this.setBestDot();
	this.calculateFitnessSum();
	newDots.push(this.dots[this.bestDot].createBaby());
	newDots[0].isBest = true;
	for(var i = 1; i < this.dots.length; i++) {
		var par = this.selectParent();
		newDots[i] = par.createBaby();
	}
	this.dots = newDots;
	++this.gen;
};
Population.prototype.calculateFitnessSum = function() {
	this.fitnessSum = this.dots.reduce(function(a,c) {return a + c.fitness}, 0);
};
Population.prototype.selectParent = function() {
	var rand = Math.random() * this.fitnessSum;
	var runningSum = 0;
	for(var i = 0; i < this.dots.length; i++) {
		runningSum += this.dots[i].fitness;
		if (runningSum > rand) {
			return this.dots[i];
		}
	}
	return null;
};
Population.prototype.mutateBabies = function() {
	for(var i = 1; i < this.dots.length; i++) {
		this.dots[i].brain.mutate();
	}
};
Population.prototype.setBestDot = function() {
	var max = 0;
	var maxIndex = 0;
	for(var i = 0; i < this.dots.length; i++) {
		if (this.dots[i].fitness > max) {
			max = this.dots[i].fitness;
			maxIndex = i;
		}
	}
	this.bestDot = maxIndex;
	if (this.dots[this.bestDot].reachedGoal) {
		//this eliminates the number of steps that can be taken
		// this.minStep = this.dots[this.bestDot].brain.step;
		this.bestSteps = this.dots[this.bestDot].brain.step;
	}
};
Population.prototype.pause = function() {
	this.play = false;
};
Population.prototype.resume = function() {
	this.play = true;
};
Population.prototype.restart = function() {
	this.dots = [];
	this.fitnessSum = 0;
	this.gen = 1;
	this.bestDot = 0;
	this.bestSteps = 0;
	this.play = true;

	for(var i = 0; i < this.num_dots; i++) {
		this.dots.push(new Dot(this.start_pos));
	}
};
