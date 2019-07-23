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


    /**
     * Get image uri for image size. Return null if image is not exists.
     *
     * @param mixed $image image (Model, id or path are accepted)
     * @param null|string $size image size
     * @param bool $empty return empty string if size is not exists or not
     *
     * @return string|null
     */
    static function getImageUri($image, $size = null, $empty = false)
    {
	    if (! is_a($image, 'ClassicO\NovaMediaLibrary\Core\Model')) {
	        $image = Model::where('id', $image)->orWhere('path', $image)->first();

	        if (!$image) {
	            return null;
            }
        }

	    $folder = self::getFolder($image->path);

	    if (!is_array(config('media-library.sizes')) || !in_array($size,array_keys(config('media-library.sizes')))) {
            return self::storage()->url($folder);
        }

        $extension = pathinfo($folder, PATHINFO_EXTENSION);
        $path = mb_substr($folder, 0, -(mb_strlen($extension)+1)) . "-$size.$extension";

        if (self::storage()->exists($path)) {
            return self::storage()->url($path);
        } else {
            return $empty ? '' : self::storage()->url($folder);
        }
    }

    /**
     * Get uri for all registered image sizes
     *
     * @param mixed $image image (Model, id or path are accepted)
     * @param bool $empty return empty string if size is not exists or not
     *
     * @return array
     */
    static function getImageSizes($image, $empty = false)
    {
	    $output = [];

	    if(!is_array(config('media-library.sizes'))) {
	        return $output;
        }

	    if (! is_a($image, 'ClassicO\NovaMediaLibrary\Core\Model')) {
	        $image = Model::where('id', $image)->orWhere('path', $image)->first();

	        if (!$image) {
	            return $output;
            }
        }

	    foreach (array_keys(config('media-library.sizes')) as $size) {
	        $uri = self::getImageUri($image, $size, $empty);

            $output[$size] = $uri;
        }

	    $folder = self::getFolder($image->path);

        $output['original'] = self::storage()->url($folder);

	    return $output;
    }

}
