<?php

return [
    /**
     * Default filesystem disk.
     *
     * @example `local` or `public` or `s3`
     * @var string
     */
    'disk' => env('FILESYSTEM_DRIVER', 'public'),

    /**
     * Will use to return base url of media file.
     *
     * @var string
     */
    'url' => 's3' == env('FILESYSTEM_DRIVER') ? env('AWS_URL', '') : env('APP_URL', '') . '/storage',

    /**
     * Store files `together` or in separate `folders`
     *
     * @var string
     */
    'store' => 'together',

    /**
     * Default file visibility (only for s3)
     * For disk `local` will be `true`, for `public` - `false`
     *
     * @var boolean
     */
    'private' => false,

    /**
     * Store all files in a separate folder of storage
     *
     * @var string
     */
    'folder' => '',

    /**
     * Organize uploads into date based folders
     * Available date characters: `Y`, `m`, `d` and symbols: `-`, `_`, `/`
     * Does not work when parameter `store` != `together`
     *
     * @example `Y-m`, `Y/m`, `Y-m/d`
     * @var string
     */
    'by_date' => '',

    /**
     * This option allow you to filter your files by types and extensions
     * Format: Label => ['array', 'of', 'extensions']
     *
     * @example ['*'] - allow you to save any file extensions to the specified type
     * @var array - not empty!
     */
    'types' => [
        'Image' => ['jpg', 'jpeg', 'png', 'gif', 'svg'],
        'Docs' => ['doc', 'xls', 'docx', 'xlsx', 'pdf'],
        'Audio' => ['mp3'],
        'Video' => ['mp4'],
        //'Other' => ['*'],
    ],

    /**
     * Maximum upload size for each type
     * Add `Label` => `max_size` in bytes for needed types to enable limitation
     * If you want to disable the limitation - leave empty array
     *
     * @var array
     */
    'max_size' => [
        'Image' => 2097152,
        'Docs' => 5242880,
    ],

    /**
     * The number of files that will be returned with each step
     *
     * @var integer
     */
    'step' => 40,

    /**
     * Allow duplicate files in field (when use as array)
     *
     * @var bool
     */
    'duplicates' => true,

    /**
     * Allow you to resize original images by width\height. Using http://image.intervention.io library.
     * Width and height can be integer or null. If one of them is null - will resize image proportionally.
     *
     * @see supports image formats: http://image.intervention.io/getting_started/formats.
     * @var array
     */
    'resize' => [
        // `gd` or `imagick`
        'driver' => 'gd',

        // 0 - 100
        'quality' => 80,

        // Cropping image on the frontend
        'front_crop' => true,

        // Maximum width and height in pixels for the original image [ width, height, upSize, upWH ]
        // upSize {bool} - Crop image even if size will be larger. (If set to `false` - size image will be as original).
        // upWH {bool} - Crop even if width and height image less than limits.
        'original' => [ 1200, null, false, false ],

        // Crop additional image variations [ width, height, upSize, upWH ]
        'sizes' => [
            'thumb' => [ 200, 200, true, false ],
            'medium' => [ 800, null, true, false ],
        ],

        // Set `size name` from `sizes` above for preview in admin area or leave `null`
        'preview' => 'thumb',
    ],
];
