function Brain(size) {
	this.directions = [];
	this.step = 0;

	for(var i=0; i < size; i++) {
		this.directions.push([0,0]);
	}
	this.randomize();
}
Brain.prototype.randomize = function() {
	for(var i = 0; i < this.directions.length; i++) {
		var angle = 2 * Math.PI * Math.random();
		this.directions[i][0] = Math.cos(angle);
		this.directions[i][1] = Math.sin(angle);
	}
};
Brain.prototype.clone = function() {
	var clone = new Brain(this.directions.length);
	for(var i = 0; i < this.directions.length; i++) {
		//clone.directions[i] = JSON.parse(JSON.stringify(this.directions[i]));
		clone.directions[i][0] = this.directions[i][0];
		clone.directions[i][1] = this.directions[i][1];
	}
	return clone;
};
Brain.prototype.mutate = function() {
	//var mutRate = 0.25; //0.01
	for(var i = 0; i < this.directions.length; i++) {
		var rand = Math.random();
		if (rand < mutRate) {
			var angle = 2 * Math.PI * Math.random();
			this.directions[i][0] = Math.cos(angle);
			this.directions[i][1] = Math.sin(angle);
		}
	}
};
