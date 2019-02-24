@extends('layouts.app')
@section('head')
<style>
.editor {
	width: 640px;
	height: 480px;
}
</style>
@endsection
@section('content')
<div class="d-flex flex-row mt-2" id="app">
	<div class="d-flex flex-column mx-2">
		<div class="toolbox">
			<div class="card" style="width: 200px;">
				<div class="card-body">
					<div class="text-center mb-2">
						<a href="{{url('/')}}" class="btn btn-primary">Home</a>
					</div>
					<h3 class="card-title text-center">Tools</h3>
					<hr />
					<div>
						<label>
							<input type="radio" v-model="tool" value="wall" />
							Wall
						</label>
					</div>
					<div>
						<label>
							<input type="radio" v-model="tool" value="remove" />
							Remove Wall
						</label>
					</div>
					<div>
						<label>
							<input type="radio" v-model="tool" value="start" />
							Start
						</label>
					</div>
					<div>
						<label>
							<input type="radio" v-model="tool" value="end" />
							End
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="properties mt-2">
			<div class="card" style="width: 200px;">
				<div class="card-body">
					<h3 class="card-title text-center">Properties</h3>
					<hr />
					<div>
						<label class="control-label">Level Name</label>
						<input type="text" class="form-control" v-model="name"/>
					</div>
					<div class="text-center mt-2">
						<button type="button" class="btn btn-success" @click="save">Save Level</button>
						<button type="button" class="btn btn-success" @click="play" :disabled="!level_id">Play</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="editor border border-dark mx-2" @mousedown="startProcess" @mouseup="doneProcessing" @mousemove="process">
		<canvas></canvas>
	</div>
</div>
@endsection
@section('footer')
<script src="{{Helper::asset_nocache('js/map.js')}}"></script>
<script>
var csrf_token = "{{csrf_token()}}";

var walls = {!! json_encode($walls) !!};
var start = {!! json_encode($start) !!};
var end = {!! json_encode($end) !!};

var canvas = null;
var ctx = null;

function draw() {
	ctx.clearRect(0, 0, canvas.width, canvas.height); // clear canvas
	ctx.fillStyle = 'blue';
	walls.forEach(function(wall) {
		ctx.fillRect(wall[0], wall[1], wall_width, wall_height);
	});
	if (start.length > 0) {
		ctx.beginPath();
		ctx.arc(start[0], start[1], start_radius, 0, 2 * Math.PI, false);
		ctx.fillStyle = 'red';
		ctx.fill();
		// context.lineWidth = 5;
		// context.strokeStyle = '#003300';
		// context.stroke();
	}
	if (end.length > 0) {
		ctx.fillStyle = 'green';
		ctx.fillRect(end[0], end[1], end_width, end_height);
	}
}

var app = new Vue({
	el: "#app",
	data: function() {
		return {
			tool: "wall",
			down: false,
			name: '{{$level->name ?? ""}}',
			level_id: {{$level->id ?? "null"}},
		};
	},
	mounted: function() {
		var editor = $(".editor");
		canvas = $("canvas")[0];
		ctx = canvas.getContext('2d');
		canvas.width  = editor.width();
		canvas.height = editor.height();
		draw();
	},
	methods: {
		play: function() {
			window.location = super_path + "/play/"+this.level_id;
		},
		save: function() {
			//save to db
			var _this = this;
			var type = this.level_id ? "put" : "post";
			var extra = this.level_id ? "/" + this.level_id : "";
			var info = {
				walls: walls,
				start: start,
				end: end,
			};
			axios[type](super_path + '/levels' + extra, {
				name: this.name,
				data: info,
			}, {
				headers: {
					"_token": csrf_token,
				}
			}).then(function(response) {
				if (response.data.ok) {
					console.log("Saved Level");
					if (!_this.level_id) {
						_this.level_id = response.data.id;
					}
				} else {
					console.log(response);
				}
			}).catch(function(error) {
				console.log(error);
			});
		},
		startProcess: function(evt) {
			this.down = true;
			this.process(evt);
		},
		doneProcessing: function() {
			this.down = false;
			this.save();
		},
		process: function(evt) {
			if (this.down) {
				//console.log(evt.offsetX, evt.offsetY)
				var pos = {x: evt.offsetX, y: evt.offsetY};
				if (this.tool === "wall") {
					var tmp = {
						x: pos.x - wall_width/2,
						y: pos.y - wall_height/2,
					};
					//will the wall collide with another wall?
					if (!walls.some(function(wall) {
						return collision(tmp, wall_width, wall_height, wall, wall_width, wall_height);
					})) {
						walls.push([tmp.x, tmp.y]);
					}
				} else if (this.tool === "remove") {
					var index = walls.findIndex(function(wall){
						return collision(pos, 1, 1, wall, wall_width, wall_height);
					});
					if (index > -1) {
						walls.splice(index, 1);
					}
				} else if (this.tool === "start") {
					start = [pos.x, pos.y];
				} else { //end
					var tmp = {
						x: pos.x - end_width/2,
						y: pos.y - end_height/2,
					};
					end = [tmp.x, tmp.y];
				}
				draw();
			}
		}
	}
});
</script>
@endsection
