<?php

namespace ClassicO\NovaMediaLibrary\Core;

use Illuminate\Support\Facades\Storage;

class Helper {

	/**
	 * Return all global config (for frontend)
	 *
	 * @return array
	 */
	static function frontConfig()
	{
		$config_types = config('media-library.types');

		if ( is_array($config_types) ) {

			$types = [];
			$labels = [];

			if ( count($config_types) > 1 ) {
				$labels = array_keys($config_types);
			}

			foreach ($config_types as $key) {
				$types = array_merge($types, $key);
			}

			if ( in_array('*', $types) ) $types = [];

			return [
				'nml_types' => $labels,
				'nml_accept' => preg_filter('/^/', '.', $types),
				'nml_crop' => config('media-library.resize.crop'),
				'nml_lang' => self::getLang()
			];
		}

		return [];
	}

	private static function getLang()
	{
		$file = resource_path('lang/vendor/nova-media-library/'.app()->getLocale().'/messages.php');
		if ( !is_readable($file)) return [];

		$nml = [];
		$lang = include $file;

		if ( 'array' == gettype($lang) ) {
			foreach ($lang as $key => $val) {
				$nml['nml_'.$key] = $val;
			}
		}

		return $nml;
	}

	static function storage()
	{
		return Storage::disk(config('media-library.disk'));
	}

	static function getFolder($path = '')
	{
		return preg_replace('/(\/)\\1+/', '$1',
			'/'. (string)config('media-library.folder') .'/'. $path
		);
	}

	static function getDate()
	{
		$folder = '/';
		$by_date = config('media-library.by_date');

		if ( is_string($by_date) ) {
			$date = preg_replace('/[^Ymd_\-\/]/', '', $by_date);
			$folder .= date($date) .'/';
		}

		return preg_replace('/(\/)\\1+/', '$1', $folder);
	}

	static function getType($extension)
	{
		$types = config('media-library.types');
		if ( !is_array($types) ) return false;

		foreach ($types as $label => $array) {
			if ( in_array($extension, $array) or in_array('*', $array) )
				return $label;
		}

		return false;
	}

	static function parseSize($url, $name)
	{
		$array = explode('.', $url);
		$array[count($array)-2] .= '-'. $name;
		return implode('.', $array);
	}

}
