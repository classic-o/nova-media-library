<?php

namespace ClassicO\NovaMediaLibrary\Core;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\Mime\MimeTypes;

class Upload {

	var $description;
	var $path;
	var $mime;
	var $size;
	var $type;

	var $noResize;

	private $config;
	private $file;
	private $bytes = 0;
	private $extension;
	private $resize;
	private $sizes;

	function __construct($file) {
		$this->config = config('media-library');
		$this->resize = $this->config['resize'];
		$this->file = $file;
		$this->mime = explode('/', $file->getMimeType())[0];
		$this->extension = $this->guessExtension($file);
		$this->sizes = $this->config['sizes'] ?? null;
	}

	function setType()
	{
		$this->type = Helper::getType($this->extension);
	}

	function setName($value)
	{
		$this->description = $value;
		$value = explode('.', $value);
		array_pop($value);

		$this->path = str_replace('//', '/',
			Helper::getDate() .
			substr(Str::slug(implode('.', $value)), 0, 100)
			.'-'. time() .'-'. Str::random(5) .'.'. $this->extension
		);
	}

	function setFile()
	{
		if ( !is_int($this->resize['width']) )  $this->resize['width'] = null;
		if ( !is_int($this->resize['height']) ) $this->resize['height'] = null;

        if (
			'image' == $this->mime and
            'svg' != $this->extension and
			$this->resize['image'] === $this->type and
			( $this->resize['width'] or $this->resize['height'] ) and
			class_exists('\Intervention\Image\ImageManager')
		) {
			$this->byResize();
		} else {
			$this->byDefault();
		}
	}

	function checkSize()
	{
		$max = $this->config['max_size'];
		if ( $max and isset($max[$this->type]) and $max[$this->type] < $this->bytes ) return false;

		$this->size = [ $this->bytes/1024, __('nova-media-library::messages.kb') ];
		if ( $this->size[0]/1024 > 1 )
			$this->size = [ $this->size[0]/1024, __('nova-media-library::messages.mb') ];

		$this->size[0] = round($this->size[0], 2);
		$this->size = implode(' ', $this->size);

		return true;
	}

	function save()
	{
	    if ( Helper::storage()->put(Helper::getFolder($this->path), $this->file) ) {

	        $this->make_sizes();

			$res = Model::create([
				'description' => $this->description,
				'path' => $this->path,
				'mime' => $this->mime,
				'size' => $this->size,
				'type' => $this->type,
				'created' => now()
			]);
			return !!$res;
		}
		return false;
	}

	##### Set File #####

	private function byDefault()
	{
		$this->bytes = $this->file->getSize();
		$this->file = file_get_contents($this->file);
		#$this->file = $this->file->get();
	}

	private function byResize()
	{
		try {
			$manager = new \Intervention\Image\ImageManager([ 'driver' => $this->resize['driver'] ]);
			$image = $manager->make($this->file);

			$data = $image->resize($this->resize['width'], $this->resize['height'], function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			})->stream(null, $this->resize['quality'])->__toString();

			$this->bytes = strlen($data);
			$this->file = $data;
		} catch (\Exception $e) {
			$this->noResize = true;
			$this->byDefault();
		}
	}

    private function guessExtension(UploadedFile $file) : string
    {
        $client_extension = strtolower($file->getClientOriginalExtension());
        $guess_extension = MimeTypes::getDefault()->getExtensions($file->getMimeType());

        if(!empty($guess_extension) && in_array($client_extension, $guess_extension)){
            return $client_extension == 'jpeg' ? 'jpg' : $client_extension;
        } else {
            //unify jpeg extension
            $extension = $file->extension() == 'jpeg' ? 'jpg' : $file->extension();

            return $extension ?: (
            $file->getMimeType() == 'image/svg' ?
                'svg' : $client_extension
            );
        }
    }

	private function make_sizes()
    {
	    if (
            'image' == $this->mime &&
            'svg' != $this->extension &&
	        !empty($this->sizes) &&
            class_exists('\Intervention\Image\ImageManager')
        ) {
            foreach ($this->sizes as $name => [
                    'width' => $width,
                    'height' => $height,
                    'crop' => $crop
            ]) {
                if($name) {
                    if((int) $width > 0 || (int) $height > 0){
                        $manager = new \Intervention\Image\ImageManager(['driver' => $this->resize['driver']]);
                        $image = $manager->make($this->file);
                        $start = $image->width();

                        if ((int)$width > 0 && (int)$height > 0 && $crop) {
                            $image->fit($width, $height, function ($constraint) {
                                $constraint->upsize();
                            });
                        } else {
                            $image->resize($width, $height, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                        }

                        if($start != $image->width()){
                            $image = $image->stream(null, $this->resize['quality'])->__toString();
                            $path = mb_substr($this->path, 0, -(mb_strlen($this->extension)+1)) . "-$name.{$this->extension}";

                            Helper::storage()->put(Helper::getFolder($path), $image);
                        }
                    }
                }
            }
        }
    }

}
