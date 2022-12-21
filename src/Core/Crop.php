<?php

namespace ClassicO\NovaMediaLibrary\Core;

use Illuminate\Support\Str;
use ClassicO\NovaMediaLibrary\API;
use Intervention\Image\ImageManager;

class Crop
{
    public $image = null;
    public $form = [];

    private $config = [];
    private $file = null;
    private $bytes = 0;

    public function __construct($form)
    {
        $this->config = config('nova-media-library.resize');

        if (! $this->config['front_crop'] or ! class_exists('\Intervention\Image\ImageManager')) {
            return;
        }

        $this->form = $form;
        $this->image = Model::findOrFail($this->form['id'])->toArray();
    }

    public function make()
    {
        $manager = new ImageManager([ 'driver' => $this->config['driver'] ]);
        $image = $manager->make(
            Helper::storage()->readStream(
                Helper::folder($this->image['folder']) . $this->image['name']
            )
        );

        $image->rotate(-1 * $this->form['rotate']);
        $image->crop((int) $this->form['width'], (int) $this->form['height'], (int) $this->form['x'], (int) $this->form['y']);

        $this->file = $image->stream(null, $this->config['quality'])->__toString();
        $this->bytes = strlen($this->file);
        $this->image['options']->size = Helper::size($this->bytes);
        $this->image['options']->wh = [(int) $this->form['width'], (int) $this->form['height']];
    }

    public function save()
    {
        $this->image['created'] = now();

        if (0 === $this->form['over']) {
            $ext = explode('.', $this->image['name']);
            $name = explode('-', $ext[0]);
            array_pop($name);

            unset($this->image['id']);
            $this->image['name'] = implode('-', $name) . '-' . time() . Str::random(5) . '.' . $ext[1];
        }

        if (
            Helper::storage()->put(
                Helper::folder($this->image['folder'] . $this->image['name']),
                $this->file,
                Helper::visibility($this->image['private'])
            )
        ) {
            if (1 === $this->form['over']) {
                $item = Model::find($this->form['id']);
                $item->update($this->image);
                self::createSizes($item);

                return $item;
            } else {
                $item = Model::create($this->image);
                self::createSizes($item);

                return $item;
            }
        }

        return false;
    }

    //#### Crop additional image sizes #####

    public static function createSizes($item)
    {
        $config = config('nova-media-library.resize');

        if (
            'image' != data_get($item, 'options.mime')
             or ! is_array($config)
             or ! class_exists('\Intervention\Image\ImageManager')
        ) {
            return;
        }

        $sizes = [];
        $name = explode('.', $item->name);
        $ext = '.' . array_pop($name);
        $name = implode('.', $name) . '-';

        $folder = Helper::folder($item->folder . $item->name);
        $file = Helper::storage()->get($folder);

        if (! $file) {
            return;
        }

        $img_sizes = data_get($item->options, 'img_sizes');

        if ($img_sizes) {
            data_set($item->options, 'img_sizes', []);

            foreach ($img_sizes as $key) {
                Helper::storage()->delete(API::getImageSize($folder, $key));
            }
        }

        $manager = new \Intervention\Image\ImageManager([ 'driver' => $config['driver'] ]);

        foreach ($config['sizes'] as $size => $data) {
            if (! is_int($data[0]) and ! is_int($data[1]) or self::cantResize($item, $data)) {
                continue;
            }

            try {
                $fn = ($data[0] and $data[1]) ? 'fit' : 'resize';
                $img = $manager->make($file)->$fn($data[0], $data[1], function ($constraint) use ($data) {
                    if (! $data[0] or ! $data[1]) {
                        $constraint->aspectRatio();
                    }

                    if ($data[2] !== true) {
                        $constraint->upsize();
                    }
                })->stream(null, $config['quality'])->__toString();

                if (
                    Helper::storage()->put(
                        Helper::folder($item->folder . $name . $size . $ext),
                        $img,
                        Helper::visibility($item->private)
                    )
                ) {
                    $sizes[] = $size;
                }
            } catch (\Exception $e) {
            }
        }

        if ($sizes) {
            $item->options = data_set($item->options, 'img_sizes', $sizes);
            $item->save();
        }
    }

    private static function cantResize($item, $data)
    {
        $width = data_get($item, 'options.wh.0');
        $height = data_get($item, 'options.wh.1');

        if (
            ! is_numeric($width) or ! is_numeric($height) or
            ! $data[3] and
            (! $data[0] or $data[0] > $width) and
            (! $data[1] or $data[1] > $height)
        ) {
            return true;
        }

        return false;
    }
}
