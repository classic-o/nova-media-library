<?php

namespace ClassicO\NovaMediaLibrary\Core;

use ClassicO\NovaMediaLibrary\API;
use Illuminate\Support\Facades\Storage;

class Helper {

	static function storage()
	{
		return Storage::disk(config('nova-media-library.disk', 'public'));
	}

	static function directories()
	{
		$len = strlen(substr(self::folder(), 1));
		$array = [];

		foreach (self::storage()->allDirectories(config('nova-media-library.folder')) as $item) {
			if ( 'nml_temp' == $item ) continue;
			$path = str_replace('/', '.', substr($item, $len));
			if ( $path ) data_set($array, $path, 0);
		}


		return $array;
	}

	static function replace($str)
	{
		return preg_replace('/(\/)\\1+/', '$1', str_replace('\\', '/', $str));
	}

	static function folder($path = '')
	{
		return self::replace('/'. (string)config('nova-media-library.folder', '') .'/'. $path);
	}

	static function size($bytes)
	{
		if ( $bytes / 1073741824 >= 1 )
			return round($bytes / 1073741824, 2) .' '. __('gb');

		if ( $bytes / 1048576 >= 1 )
			return round($bytes / 1048576, 2) .' '. __('mb');

		if ( $bytes / 1024 >= 1 )
			return round($bytes / 1024, 2) .' '. __('kb');

		return $bytes .' '. __('b');
	}

	static function isPrivate($folder)
	{
		$disk = config('nova-media-library.disk');
		$private = false;

		if ( 's3' == $disk )
			$private = config('nova-media-library.private') ?? false;
		else if ( 'local' == $disk )
			$private = '/public/' != substr(self::folder($folder), 0, 8);

		return $private;
	}

	static function visibility($bool)
	{
		return $bool ? 'private' : 'public';
	}

	static function preview($item, $size)
	{
		if ( !in_array($size, data_get($item, 'options.img_sizes', [])) ) return null;

		$url = data_get($item, 'url');

		return data_get($item, 'private') ? $url . '&img_size='. $size : API::getImageSize($url, $size);
	}

	static function localPublic($folder, $private)
	{
		return (
			'local' == config('nova-media-library.disk') and
			!$private and
			'/public/' == substr(self::folder($folder), 0, 8)
		);
	}

}
