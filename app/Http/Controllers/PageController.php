<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Level;

class PageController extends Controller
{
	public function index(Request $request) {
		$levels = Level::all();
		return view('home', compact('levels'));
	}

	public function new_level(Request $request) {
		return view('editor', ['level'=>null, 'start'=>[], 'end'=>[], 'walls'=>[]]);
	}

	public function select_level(Request $request, Level $level) {
		$walls = [];
		$start = [];
		$end = [];
		if (isset($level->map_data)) {
			$level->map_data = json_decode($level->map_data);
			$walls = $level->map_data->walls;
			$start = $level->map_data->start;
			$end = $level->map_data->end;
			unset($level->map_data);
		}
		return view('editor', compact('level', 'start', 'end', 'walls'));
	}

	public function play_level(Request $request, Level $level) {
		$walls = [];
		$start = [];
		$end = [];
		if (isset($level->map_data)) {
			$level->map_data = json_decode($level->map_data);
			$walls = $level->map_data->walls;
			$start = $level->map_data->start;
			$end = $level->map_data->end;
			unset($level->map_data);
		}
		return view('play', compact('level', 'start', 'end', 'walls'));
	}
}
