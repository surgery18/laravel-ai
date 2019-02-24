<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Client;

class Helper
{
		public static function asset_nocache($url, $secure=null)
		{
			if (\App::environment('testing')) {
				return $url;
			}
			return asset($url, $secure)."?file_mod_time=".filemtime($url);
		}
}
