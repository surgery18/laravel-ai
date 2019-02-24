@extends("layouts.app")
@section('head')
<style>
.map {
	width: 640px;
	height: 480px;
}
</style>
@endsection
@section('content')
<div class="d-flex flex-row mt-2" id="app">
	<div class="info">
		<div class="card" style="width: 200px;">
			<div class="card-body">
				<h3 class="card-title text-center">Info</h3>
				<hr />
				<h4 class="text-center">{{$level->name}}</h4>
				<h4>Gen: <span v-text="gen"></span></h4>
			</div>
		</div>
	</div>
	<div class="map border border-dark mx-2">
		<canvas></canvas>
	</div>
</div>
@endsection
@section('footer')
<script src="{{Helper::asset_nocache('js/map.js')}}"></script>
<script src="{{Helper::asset_nocache('js/brain.js')}}"></script>
<script src="{{Helper::asset_nocache('js/dot.js')}}"></script>
<script src="{{Helper::asset_nocache('js/population.js')}}"></script>
<script>
var walls = {!! json_encode($walls) !!};
var start = {!! json_encode($start) !!};
var end = {!! json_encode($end) !!};
var population = new Population(500, start);
// console.log(population.dots[0].brain.directions[0], population.dots[1].brain.directions[0]);

var canvas = null, ctx = null;

function game_loop() {
	update();
	draw();
	requestAnimationFrame(game_loop);
}

function update() {
	if (population.allDotsDead()) {
		population.calculateFitness(end);
		population.naturalSelection();
		population.mutateBabies();
	} else {
		population.update(end, walls);
	}
}

function draw() {
	ctx.clearRect(0, 0, canvas.width, canvas.height); // clear canvas
	ctx.fillStyle = 'blue';
	walls.forEach(function(wall) {
		ctx.fillRect(wall[0], wall[1], wall_width, wall_height);
	});
	if (end.length > 0) {
		ctx.fillStyle = 'green';
		ctx.fillRect(end[0], end[1], end_width, end_height);
	}
	population.draw(ctx);
}

var app = new Vue({
	el: "#app",
	data: function(){
		return {
			pop: population,
		};
	},
	mounted: function() {
		var map = $(".map");
		canvas = $("canvas")[0];
		ctx = canvas.getContext('2d');
		canvas.width  = map.width();
		canvas.height = map.height();
		requestAnimationFrame(game_loop);
	},
	computed: {
		gen: function() {
			return this.pop.gen;
		},
	},
});
</script>
@endsection
