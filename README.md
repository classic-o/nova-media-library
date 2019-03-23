# Laravel Nova Media Library

Tool and Field for [Laravel Nova](https://nova.laravel.com) that will let you managing images and add them to the posts as single image or gallery

##### Table of Contents  
* [Install](#install)
* [Usage](#usage)
* [Configuration](#configuration)
* [Customization](#customization)
* [Screenshots](#screenshots)

## Install
```
composer require classic-o/nova-media-library
```
```
php artisan vendor:publish --provider="ClassicO\NovaMediaLibrary\ToolServiceProvider"
```
```
php artisan migrate
```
```
php artisan storage:link
```

## Usage

Add the below to the tools function in app/Providers/NovaServiceProvider.php
```php
public function tools()
{
    return [
        new \ClassicO\NovaMediaLibrary\NovaMediaLibrary(),
    ];
}
```

Add Field to the resource
```php
use ClassicO\NovaMediaLibrary\MediaField;

class Post extends Resource
{
    ...
     public function fields(Request $request)
        {
            return [
                ...
                MediaField::make('Image', 'image'),
                ...
            ];
        }
    ...
}
```

## Configuration

```php
# config/media-library.php
return [

	# Will use to return base url of app.
	'url'       => env('APP_URL', '') . '/storage',

	# Will use to put file uploads in `/storage/app/public`
	'folder'    => '/media/',

	# Organize my uploads into year-month based folders.
	# `/storage/app/public/{folder}/YYYY-MM/`
	'split'     => true,

	# This option let you to filter your image by extensions.
	'type'      => ['jpg', 'jpeg', 'png', 'gif', 'svg'],

	# The number of files that will be returned with each step.
	# (The tool loads images from a folder not all at once).
	'step'      => 40,

];
```

## Customization

By default, this field is used as single image. If you need set field as gallery, add method:
```php
MediaField::make('Image', 'image')
          ->isGallery()
```

If you want to hide the gallery under the accordion, add the following method
```php
MediaField::make('Image', 'image')
          ->isGallery()
          ->isHidden()
```

## Screenshots

![Media Library](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_1.png)

![Details](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_2.png)

![Multiple select](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_3.png)

![Single image](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_4.png)

![Gallery](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_5.png)



