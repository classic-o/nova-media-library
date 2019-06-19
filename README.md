# Laravel Nova Media Library

Tool and Field for [Laravel Nova](https://nova.laravel.com) that will let you managing files and add them to the posts.

##### Table of Contents
* [Features](#features)
* [Migration from 0.1 to 0.2](#migration)
* [Requirements](#requirements)
* [Install](#install)
* [Configuration](#configuration)
* [Usage](#usage)
* [Customization](#customization)
* [Localization](#localization)
* [Screenshots](#screenshots)

### Features

- [x] Store and manage your media files
- [x] Use field as single image
- [x] Use field as gallery
- [x] Use field as list
- [x] Integrate Media Field with Trix editor
- [x] Implement a custom callback with the field
- [ ] Integrated cropping image on the frontend

### Migration from 0.1 to 0.2

In version 0.2, the configuration file and database migration have been changed.  
After upgrading to version 0.2+, you need to remove the old table from the database, then reinstall and reconfigure these files.

### Requirements

- Laravel 5.8+
- Nova 2
- [intervention/image](http://image.intervention.io) package for image resizing (optional)

### Install

```php
composer require classic-o/nova-media-library

php artisan vendor:publish --provider="ClassicO\NovaMediaLibrary\ToolServiceProvider"

php artisan migrate

php artisan storage:link
```

### Configuration

For more information and examples see the [configuration file](https://github.com/classic-o/nova-media-library/blob/master/config/media-library.php)
```php
# config/media-library.php

return [

    # Filesystem disk. Available `public` or `s3`.
    'disk' => 's3' == env('FILESYSTEM_DRIVER') ? 's3' : 'public',
	
    # Will use to return base url of media file.
    'url' => 's3' == env('FILESYSTEM_DRIVER') ? env('AWS_URL', '') : env('APP_URL', '') . '/storage',

    # Save all files in a separate folder
    'folder' => 'uploads',

    # Organize uploads into date based folders.
    'by_date' => null,
	
    # The number of files that will be returned with each step.
    'step' => 40,
	
    # This option allow you to filter your files by types and extensions.
    'types' => [
        'Image' => [ 'jpg', 'jpeg', 'png', 'gif', 'svg' ],
        'Docs'  => [ 'doc', 'xls', 'docx', 'xlsx' ],
        'Audio' => [ 'mp3' ],
        'Video' => [ 'mp4' ],
        'Other' => [ '*' ],
    ],
  	
    # Maximum size of file uploads in bytes for each types.
    'max_size' => [
        'Image' => 2097152,
        'Docs'  => 5242880,
    ],
    
    # Allow you to resize images by width\height. Using http://image.intervention.io library
    'resize' => [
        'image'   => 'Image',
        'width'   => 1200,
        'height'  => null,
        'driver'  => 'gd',
        'quality' => 80
    ]

];
```

### Usage

Add the below to the tools function in app/Providers/NovaServiceProvider.php
```php
public function tools()
{
    return [
        new \ClassicO\NovaMediaLibrary\NovaMediaLibrary(),
    ];
}
```

Add Field to the resource.
```php
use ClassicO\NovaMediaLibrary\MediaField;

class Post extends Resource
{
    ...
     public function fields(Request $request)
        {
            return [
                ...
                MediaField::make('Image'),
                ...
            ];
        }
    ...
}
```

### Customization

By default, this field is used as single image. If you need to set the field as a listing, add a method:
```php
# Display as a gallery
MediaField::make('Gallery')
          ->listing(),
    
# Display by line
MediaField::make('Listing')
          ->listing('line'),
```
_When you use a listing, set the casts as array to needed column in model and type `TEXT` in database_

If you want to hide the listing under the accordion, add the following method
```php
MediaField::make('Gallery')
          ->listing()
          ->isHidden()
```

You can limit files by type (Labels of types from configuration file).
```php
MediaField::make('Image')
          ->withTypes(['Image', 'Video'])
```

You can also integrate the media button with the Trix editor.
You need to set a unique name in the `forTrix` method and add an additional attribute with the same name in the Trix field:
```php
MediaField::make('For Trix', null)
          ->forTrix('unique_trix_name'),

Trix::make('Content')
    ->withMeta([ 'extraAttributes' => [ 'trix-nml' => 'unique_trix_name' ] ])
```

If you need to set a custom callback for the media button, use the method `jsCallback`.
- The first parameter you specified as the name of the JS function callback.
- The second (optional) is an array of advanced settings
```php
MediaField::make('With Callback', null)
	        ->jsCallback('callback_name', [ 'name' => 'Nova' ]),
```

Your JavaScript callback should have 2 parameters. The first will be an array of files, the second - the config.
```javascript
function callback_name(array, config) {
  console.log(array, config);
}
```

### Localization

To translate this tool, you need to add\change the language file `/resources/lang/vendor/nova-media-library/{lang}/messages.php` by adding phrases from `https://github.com/classic-o/nova-media-library/tree/master/resources/lang/en/messages.php`

### Screenshots

![Media Library](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_1.png)

![Details](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_2.png)

![Single image](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_3.png)

![Listing](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_4.png)

![Gallery](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_5.png)

![Gallery Index](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_6.png)

![Record](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/record.gif)
