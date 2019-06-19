<?php

namespace ClassicO\NovaMediaLibrary\Core;

use Illuminate\Support\Str;

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

	function __construct($file) {
		$this->config = config('media-library');
		$this->resize = $this->config['resize'];
		$this->file = $file;
		$this->mime = explode('/', $file->getClientMimeType())[0];
		$this->extension = strtolower($file->getClientOriginalExtension());
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
		$this->file = $this->file->get();
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

}
