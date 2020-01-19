# Laravel Nova Media Library

Tool and Field for [Laravel Nova](https://nova.laravel.com) that will let you managing files and add them to the posts.

##### Table of Contents
* [Features](#features)
* [Migration from 0.x to 1.x](#migration-from-0x-to-1x)
* [Requirements](#requirements)
* [Install](#install)
* [Configuration](#configuration)
* [Usage](#usage)
* [Customization](#customization)
* [Upload by url or path](#upload-by-url-or-path)
* [Get files by ids](#get-files-by-ids)
* [Private files](#private-files)
* [Localization](#localization)
* [Screenshots](#screenshots)

### Features

- [x] Store and manage your media files
- [x] Use field for single file
- [x] Use field for array of files
- [x] Upload files by url/path
- [x] Integrate Media Field with Trix editor
- [x] Implement custom JS callback for field
- [x] Automatic resize image on the backend by width\height
- [x] Cropping image on the frontend
- [x] Ability to create image size variations
- [x] Organize files in single folder, separate by date or by manageable folders
- [x] Ability to control files visibility

### Migration from 0.x to 1.x

In version 1.x, the configuration file and database migration have been changed.  
After upgrading to version 1.x, you need to remove the old table from the database, then reinstall and reconfigure these files.

### Requirements

- Laravel 5.8+
- Nova 2
- [intervention/image](http://image.intervention.io) package for image resizing (optional)

### Install

```
composer require classic-o/nova-media-library

php artisan vendor:publish --provider="ClassicO\NovaMediaLibrary\ToolServiceProvider"

php artisan migrate

php artisan storage:link
```

### Configuration

[See configuration file](https://github.com/classic-o/nova-media-library/blob/master/config/nova-media-library.php)

### Usage

Add tool in app/Providers/NovaServiceProvider.php

```
public function tools()
{
    return [
        new \ClassicO\NovaMediaLibrary\NovaMediaLibrary()
    ];
}
```

Add Field to the resource.

```
use ClassicO\NovaMediaLibrary\MediaLibrary;

class Post extends Resource
{
    ...
     public function fields(Request $request)
        {
            return [
                ...
                MediaLibrary::make('Image'),
                ...
            ];
        }
    ...
}
```

### Customization

By default, this field is used as single file. If you need to use as array of files, add option:

```
# Display
MediaLibrary::make('Gallery')
            ->array(),
```
    
By default this files display automatically, `gallery` or `list` as in tool.  
You can set in first parameter needed display type:

```
MediaLibrary::make('Documents')
            ->array('list'),
```

_When you use array, set the casts as array to needed column in model and set type `nullable TEXT` in database_  
_For single file - `nullable INT`_

If you want to hide files under the accordion, add the following option:
```
MediaLibrary::make('Gallery')
            ->array()
            ->hidden()
```

You can limit the selection of files by type (Labels of types from configuration file).

```
MediaLibrary::make('File')
            ->types(['Audio', 'Video'])
```

To set preview size of images in fields, add the following option (Label of cropped additional image variation)  
By default, the preview size is set in the configuration file.

```
MediaLibrary::make('File')
            ->preview('thumb')
```

You can also integrate the Media Field with the Trix editor.
You need to set a unique name in the `trix` option and add an additional attribute with the same name in the Trix field:

```
MediaLibrary::make('For Trix')
            ->trix('unique_trix_name'),

Trix::make('Content')
    ->withMeta([ 'extraAttributes' => [ 'nml-trix' => 'unique_trix_name' ] ])
```

For set a custom callback for the Media Field, use the method `jsCallback`.
- The first parameter set as the name of the JS function callback.
- The second (optional) is an array of advanced options

```
MediaLibrary::make('JS Callback')
	        ->jsCallback('callbackName', [ 'example' => 'Nova' ]),
```

Your JavaScript callback should have 2 parameters. The first will be an array of files, second - your options.

```
window.callbackName = function (array, options) {
  console.log(array, options);
}
```

_When you use JS Callback or Trix option two or more times on one resource, set second parameter of make method to any unique name_

```
MediaLibrary::make('JS Callback', 'js_cb_name_1')
	        ->jsCallback('callbackName', [ 'example' => 'Nova' ]),
MediaLibrary::make('Trix Field', 'trix_name_1')
	        ->trix('unique_trix_name'),
```

### Upload by url or path

Also you can programmatically add files to the media library by url or path.

```
use \ClassicO\NovaMediaLibrary\API;

$result = API::upload('https://pay.google.com/about/static/images/social/og_image.jpg');
```

If upload done successfully, function return instance of model.  
If an error occurred while loading, function will throw exception.
  
Exceptions (`code => text`):  
`0` - `The file was not downloaded for unknown reasons`  
`1` - `Forbidden file format`  
`2` - `File size limit exceeded`

### Get files by ids

In your model stores only id of file(s). To get files, use the API class method:

```
$files = API::getFiles($ids, $imgSize = null, $object = false);

# First parameter - id or array of ids
# Second - if you want to get images by size variation, write label of size
# Third - by default function return array of urls. If you want to get full data of files - set true (returns object / array of objects)
```

### Private files

When you use local storage or s3 (with private visibility), you can't get files by url.    
To get file, you need to create a GET Route with the name `nml-private-file` with parameter `id` and optional `img_size`. In controller add validation user access.    
If access is allowed, you can get file by API method:

```
... Verifying access

$file = API::getFiles($id, null, true);
return API::getPrivateFile($file->path, request('img_size'))
```

### Localization

To translate this tool another language, you need to add the translation file `/resources/lang/vendor/nova-media-library/{lang}.json` by adding phrases from [en.json](https://github.com/classic-o/nova-media-library/tree/master/resources/lang/en.json)

### Screenshots

![Media Library](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_1.png)

![Media Library](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_2.png)

![Details](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_3.png)

![Crop Image](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_4.png)

![Index Field](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_5.png)

![Form Field](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/screenshot_6.png)

![Record](https://raw.githubusercontent.com/classic-o/nova-media-library/master/docs/record.gif)
