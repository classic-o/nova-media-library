<?php

namespace ClassicO\NovaMediaLibrary\Core;

class CategoryModel extends \Illuminate\Database\Eloquent\Model {

	protected $table = 'nova_media_categories';

	protected $guarded = [];

	protected $visible = ['id', 'title'];

	protected $casts = [
		'created' => 'datetime'
	];
}
