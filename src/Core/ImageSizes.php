<?php

namespace ClassicO\NovaMediaLibrary\Core;

class ImageSizes {

	static function make($path, $type)
	{
		$config = config('media-library.image_sizes');
		if ( !is_array($config) or !is_string($config['image']) or $type != $config['image']
		     or !class_exists('\Intervention\Image\ImageManager') ) return;

		$file = Helper::storage()->get(Helper::getFolder($path));
		if ( !$file ) return;

		$manager = new \Intervention\Image\ImageManager([ 'driver' => $config['driver'] ]);

		foreach ($config['labels'] as $size => $data) {
			if ( !is_int($data[0]) and !is_int($data[1]) ) continue;
			$new_path = Helper::parseSize($path, $size);

			try {
				$fn = ( $data[0] && $data[1] ) ? 'fit' : 'resize';
				$img = $manager->make($file)->$fn($data[0], $data[1], function ($constraint) use ($data) {
					if ( !$data[0] or !$data[1] ) $constraint->aspectRatio();
					if ( $data[2] !== true ) $constraint->upsize();
				})->stream(null, $config['quality'])->__toString();

				Helper::storage()->put(Helper::getFolder($new_path), $img);
			} catch (\Exception $e) {
				# Makes a copy of the original of those images that the library cannot crop. For example svg.
				Helper::storage()->copy($path, $new_path);
			}
		}

	}

}
