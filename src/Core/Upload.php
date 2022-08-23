<?php

namespace ClassicO\NovaMediaLibrary\Core;

use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Str;

class Upload
{
    public $title;
    public $folder;
    public $name;
    public $type = false;
    public $private = false;
    public $lp = false;
    public $options = [];

    public $resize = [];
    public $noResize = false;

    private $config;
    private $file;
    private $stream;
    private $extension;
    private $bytes = 0;

    public function __construct($file)
    {
        $this->config = config('nova-media-library');
        $this->file = $file;
        $this->extension = strtolower($file->getClientOriginalExtension());

        $this->title = data_get(pathinfo($file->getClientOriginalName()), 'filename', Str::random());
        $this->name = Str::slug($this->title) . '-' . time() . Str::random(5) . '.' . $this->extension;
        $this->options['mime'] = explode('/', $file->getMimeType())[0];
    }

    public function setType()
    {
        $types = config('nova-media-library.types');

        if (! is_array($types)) {
            return false;
        }

        foreach ($types as $label => $array) {
            if (in_array($this->extension, $array) or in_array('*', $array)) {
                $this->type = $label;

                return $label;

                break;
            }
        }

        return false;
    }

    public function setWH()
    {
        if ($this->options['mime'] == 'image') {
            [$width, $height] = getimagesize($this->file);

            if ($width and $height) {
                $this->options['wh'] = [$width, $height];
            }
        }
    }

    public function setFolder($folder = null)
    {
        if ('folders' != config('nova-media-library.store')) {
            $this->folder = $this->date();
        } elseif (is_string($folder)) {
            $this->folder = Helper::replace('/' . $folder . '/');
        } else {
            $this->folder = '/';
        }
    }

    public function setPrivate()
    {
        $this->private = Helper::isPrivate($this->folder);
        $this->lp = Helper::localPublic($this->folder, $this->private);
    }

    public function setFile()
    {
        $this->resize['width'] = data_get($this->config, 'resize.original.0');
        $this->resize['height'] = data_get($this->config, 'resize.original.1');
        $this->resize['upSize'] = data_get($this->config, 'resize.original.2');
        $this->resize['upWH'] = data_get($this->config, 'resize.original.3');

        if (! is_int($this->resize['width'])) {
            $this->resize['width'] = null;
        }

        if (! is_int($this->resize['height'])) {
            $this->resize['height'] = null;
        }

        if (
            'image' == $this->options['mime'] and
            ($this->resize['width'] or $this->resize['height']) and
            class_exists('\Intervention\Image\ImageManager')
        ) {
            $this->byResize();
        } else {
            $this->byDefault();
        }
    }

    public function checkSize()
    {
        $size = data_get($this->config, 'max_size.' . $this->type);

        if ($size and $size < $this->bytes) {
            return false;
        }

        $this->options['size'] = Helper::size($this->bytes);

        return true;
    }

    public function save()
    {
        if (
            Helper::storage()->put(
                Helper::folder($this->folder . $this->name),
                $this->stream,
                Helper::visibility($this->private)
            )
        ) {
            return Model::create([
                'title' => $this->title,
                'created' => now(),
                'type' => $this->type,
                'folder' => $this->folder,
                'name' => $this->name,
                'private' => $this->private,
                'lp' => $this->lp,
                'options' => $this->options,
            ]);
        }

        return false;
    }

    //#### Set File #####

    private function byDefault()
    {
        $this->bytes = $this->file->getSize();
        $this->stream = Utils::streamFor(fopen($this->file, 'r+'));
    }

    private function byResize()
    {
        try {
            [$width, $height] = getimagesize($this->file);

            if (
                ! is_numeric($width) or ! is_numeric($height) or
                ! $this->resize['upWH'] and
                (! $this->resize['width'] or $this->resize['width'] > $width) and
                (! $this->resize['height'] or $this->resize['height'] > $height)
            ) {
                return $this->noResize(false);
            }
        } catch (\Exception $e) {
            return $this->noResize();
        }

        try {
            $manager = new \Intervention\Image\ImageManager([ 'driver' => data_get($this->config, 'resize.driver') ]);
            $image = $manager->make($this->file);

            $data = $image->resize($this->resize['width'], $this->resize['height'], function ($constraint) {
                if (! $this->resize['width'] or ! $this->resize['height']) {
                    $constraint->aspectRatio();
                }

                if (true !== $this->resize['upSize']) {
                    $constraint->upsize();
                }
            });

            $stream = $data->stream(null, data_get($this->config, 'resize.quality'));

            $this->bytes = $data->filesize();
            $this->stream = $stream;
        } catch (\Exception $e) {
            $this->noResize();
        }
    }

    private function noResize($bool = true)
    {
        $this->noResize = $bool;
        $this->byDefault();

        return null;
    }

    private function date()
    {
        $folder = '/';
        $by_date = config('nova-media-library.by_date');

        if ($by_date) {
            $date = preg_replace('/[^Ymd_\-\/]/', '', $by_date);
            $folder .= date($date) . '/';
        }

        return Helper::replace($folder);
    }
}
