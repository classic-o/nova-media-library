<?php

return [

	/**
	 * Here you may specify the default filesystem disk.
	 * Available `public` or `s3`.
	 *
	 * @var string
	 * @since 0.2.0
	 */

	'disk'      => 's3' == env('FILESYSTEM_DRIVER') ? 's3' : 'public',

	/**
	 * Will use to return base url of media file.
	 *
	 * @var string
	 * @since 0.2.0
	 */

	'url'       => 's3' == env('FILESYSTEM_DRIVER') ? env('AWS_URL', '') : env('APP_URL', '') . '/storage',

	/**
	 * Save all files in a separate folder.
	 *
	 * @example `media-library`
	 *
	 * @var string
	 * @since 0.1.0
	 */

	'folder'    => '',

	/**
	 * Organize uploads into date based folders.
	 * Available date characters: `Y`, `m`, `d` and symbols: `-`, `_`, `/`
	 *
	 * @example Y-m
	 * @example Y/m
	 * @example Y/m-d
	 *
	 * @var string|null
	 * @since 0.2.0
	 */

	'by_date'   => null,

	/**
	 * This option allow you to filter your files by types and extensions.
	 * Format: Label => ['array of extensions'].
	 *
	 * @example ['*'] - allow you to save any file extensions to the specified type.
	 *
	 * @var array
	 * @since 0.2.0
	 */

	'types'     => [
		'Image'     => [ 'jpg', 'jpeg', 'png', 'gif', 'svg' ],
		'Docs'      => [ 'doc', 'xls', 'docx', 'xlsx' ],
		'Audio'     => [ 'mp3' ],
		'Video'     => [ 'mp4' ],
		#'Other'     => [ '*' ],
	],

	/**
	 * Maximum size of file uploads in bytes for each types.
	 * Add `Label` => `max_size` in bytes for needed types to enable limitation for some types.
	 * If you want to disable the limitation - leave empty array
	 *
	 * @var array
	 * @since 0.2.0
	 */

	'max_size'  => [
		'Image'     => 2097152,
		'Docs'      => 5242880,
	],

	/**
	 * The number of files that will be returned with each step.
	 * The tool loads files from a folder not all at once.
	 *
	 * @var integer
	 * @since 0.1.0
	 */

	'step'      => 40,

	/**
	 * Allow you to resize images by width\height. Using http://image.intervention.io library
	 * Width and height can be integer or null. If one of them is null - will resize image proportionally
	 * Supports image formats: http://image.intervention.io/getting_started/formats
	 *
	 * @var array
	 * @since 0.2.0
	 */

	'resize'    => [

		'image'     => 'Image',     # Label from types (Set `null` to disable resizing)

		'width'     => 1200,        # Maximum width in pixels

		'height'    => null,        # Maximum height in pixels

		'driver'    => 'gd',        # `gd` or `imagick`

		'quality'   => 80,          # 0 - 100

		'crop'      => true,        # Cropping image on the frontend

	],

	/**
	 * Crop additional image variations
	 * Supports image formats: http://image.intervention.io/getting_started/formats
	 *
	 * @var array|null
	 * @since 0.5.0
	 */
	'image_sizes' => [

		'image'     => 'Image',     # Label from types (Set `null` to disable resizing)

		'driver'    => 'gd',        # `gd` or `imagick`

		'quality'   => 80,          # 0 - 100

		/**
		 * @example `name` => [ width, height, upSize ]
		 * Width and Height {int|null}
		 * upSize {bool} - Crop image even if size will be larger. (If set to `false` - size image will be as original).
		 */
		'labels'    => [
			'thumb' => [ 200, 200, false ],
			'medium' => [ 800, null, false ],
		]
	]

];
