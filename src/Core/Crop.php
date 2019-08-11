<?php

namespace ClassicO\NovaMediaLibrary\Core;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;

class Crop {

	var $image = null;
	var $form = [];

	private $config = [];
	private $model = null;
	private $file = null;
	private $bytes = 0;

	public function __construct($form) {
		$this->config = config('media-library.resize');
		if ( !$this->config['crop'] or !class_exists('\Intervention\Image\ImageManager')) return;

		$this->form = $form;
	}

	function check()
	{
		$valid = Validator::make($this->form, [
			'id' => 'required|numeric',
			'x' => 'required|numeric',
			'y' => 'required|numeric',
			'width' => 'required|numeric',
			'height' => 'required|numeric',
			'rotate' => 'required|numeric|min:0|max:360',
			'over' => 'required|integer|min:0|max:1',
		]);
		if ( $valid->fails() ) return false;

		$this->model = new Model;
		$this->image = Model::find($this->form['id']);
		return !!$this->image;
	}

	function make()
	{
		$manager = new ImageManager([ 'driver' => $this->config['driver'] ]);
		$image = $manager->make( Helper::storage()->readStream(Helper::getFolder($this->image->path)) );

		$image->rotate(-1 * $this->form['rotate']);
		$image->crop((int)$this->form['width'], (int)$this->form['height'], (int)$this->form['x'], (int)$this->form['y']);

		$this->file = $image->stream(null, $this->config['quality'])->__toString();
		$this->bytes = strlen($this->file);
	}

	function setSize()
	{
		$size = [ $this->bytes/1024, __('nova-media-library::messages.kb') ];
		if ( $size[0]/1024 > 1 )
			$size = [ $size[0]/1024, __('nova-media-library::messages.mb') ];

		$size[0] = round($size[0], 2);
		$this->image->size = implode(' ', $size);
	}

	function save()
	{
		$this->image->created = now();

		if ( 0 === $this->form['over'] ) {
			unset($this->image->id, $this->image->url);
			$path = explode('/', $this->image->path);
			$name = explode('-', end($path));
			$ext = explode('.', end($name));

			$this->image->path = str_replace('//', '/',
				Helper::getDate() .
				implode('-', array_slice($name, 0, -2))
				.'-'. time() .'-'. Str::random(5) .'.'. end($ext)
			);
		}

		if ( Helper::storage()->put(Helper::getFolder($this->image->path), $this->file) ) {
			if ( 1 === $this->form['over'] ) {
				$this->image->save();
				return true;
			} else {
				$res = Model::create($this->image->toArray());
				ImageSizes::make($this->image->path, Helper::getType(end($ext)));
				return !!$res;
			}
		}
		return false;
	}

}
