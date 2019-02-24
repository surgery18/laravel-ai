<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Level;

class LevelController extends Controller
{
	public function store(Request $request) {
		$level = new Level();
		return response()->json($this->save($request, $level));
	}

	public function update(Request $request, Level $level) {
		return response()->json($this->save($request, $level));
	}

	private function save(Request &$request, Level &$level) {
		//just a simple function for now
		$level->name = $request->name ?? "No Name";
		$level->map_data = json_encode($request->data);
		return ['ok'=>$level->save(), 'id'=>$level->id??null];
	}
}
