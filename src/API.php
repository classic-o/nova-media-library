<?php

namespace ClassicO\NovaMediaLibrary;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use ClassicO\NovaMediaLibrary\Core\Upload;
use ClassicO\NovaMediaLibrary\Core\Helper;
use ClassicO\NovaMediaLibrary\Core\ImageSizes;

class API {

	/**
	 * Upload image by path\url
	 *
	 * @param $path
	 * @return bool
	 */
	static function upload($path)
	{
		try {
			$base = basename(parse_url(($path), PHP_URL_PATH));
			$content = file_get_contents($path);
			Storage::disk('local')->put('nml_temp/' . $base, $content);

			$file = new UploadedFile( storage_path('app/nml_temp/' . $base), $base );
			if ( !$file ) return __('nova-media-library::messages.not_uploaded');

			$upload = new Upload($file);

			$upload->setType();
			if ( !$upload->type ) return __('nova-media-library::messages.forbidden_file_format');

			$upload->setName($file->getClientOriginalName());

			$upload->setFile();

			if ( !$upload->checkSize() ) return __('nova-media-library::messages.size_limit_exceeded');

			if ( $upload->save() ) {
				ImageSizes::make($upload->path, $upload->type);
				if ( $upload->noResize ) {
					return __('nova-media-library::messages.unsupported_resize', [ 'file' => $file->getClientOriginalName() ]);
				}
				return true;
			}

			return __('nova-media-library::messages.not_uploaded');
		} catch (\Exception $e) {
			return __($e->getMessage());
		} finally {
			Storage::disk('local')->deleteDirectory('nml_temp');
		}
	}

	/**
	 * Returns image url of the given size.
	 *
	 * @param string $url - full url of the source image
	 * @param string $size - label from config `media-library.image_sizes.labels`
	 * @param bool $check - if true, checks the image path and, if it does not exist, returns original url.
	 * @return string
	 */
	static function getImageBySize($url, $size, $check = true)
	{
		$new_url = Helper::parseSize($url, $size);
		if ( !$check ) return $new_url;

		$path = str_replace(config('media-library.url', ''), '', $new_url);
		return Helper::storage()->exists($path) ? $new_url : $url;
	}

}
