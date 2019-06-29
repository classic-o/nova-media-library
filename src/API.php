<?php

namespace ClassicO\NovaMediaLibrary;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use ClassicO\NovaMediaLibrary\Core\Upload;

class API {

	/**
	 * Upload image by path\url
	 *
	 * @param $path
	 * @throws \Exception
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

}
