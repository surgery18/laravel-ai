@extends('layouts.app')
@section('head')
@endsection
@section('content')
<section class="jumbotron text-center bg-white">
	<div class="container">
		<h1 class="jumbotron-heading">AI Examples</h1>
		<p>
			<a href="{{route('editor')}}" class="btn btn-primary my-2">Make New Level</a>
		</p>
	</div>
</section>
<div class="levels py-5 bg-light">
	<div class="container">
		<div class="row">
			@foreach($levels as $level)
			<div class="col-md-4">
				<div class="card mb-4 shadow-sm">
					<div class="card-body">
						<div class="h3 card-text text-center">{{$level->name}}</div>
					</div>
					<div class="card-footer">
						<div class="d-flex justify-content-between align-items-center">
							<div class="btn-group">
								<a href="{{url('play/'.$level->id)}}" class="btn btn-sm btn-primary">Play</a>
								<a href="{{url('/editor/'.$level->id)}}" class="btn btn-sm btn-outline-dark">Edit</a>
							</div>
							<small class="text-muted">{{$level->updated_at->diffForHumans()}}</small>
						</div>
					</div>
				</div>
			</div>
			@endforeach
	</div>
</div>
@endsection
@section('footer')
@endsection
