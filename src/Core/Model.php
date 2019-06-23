<?php

namespace ClassicO\NovaMediaLibrary\Core;

class Model extends \Illuminate\Database\Eloquent\Model {

	public $timestamps = false;

	protected $table = 'nova_media_library';

	protected $fillable = [ 'id', 'description', 'path', 'mime', 'size', 'type', 'created' ];

	protected $appends = [ 'url' ];

	protected $casts = [
		'created' => 'datetime'
	];


	/**
	 * Create full url and name for each image
	 *
	 * @return string
	 */
	function getUrlAttribute() {
		return config('media-library.url', '') . Helper::getFolder($this->path);
	}

	/**
	 * Search media files by params
	 *
	 * @param $description - entered keyword
	 * @param $type - label of type from config
	 * @param $page - pagination
	 * @param $from - uploaded date from
	 * @param $to - uploaded date to
	 * @return array
	 */
	function search($description, $type, $page, $from, $to)
	{
		$step = config('media-library.step');
		if ( !is_int($step) or $step < 1 ) $step = 40;
		if ( !is_int($page) or $page < 1 ) $page = 0;

		return $this
			       ->where(function($query) use ($description) {
				        if ( !is_null($description) )
					        $query->where('description', 'LIKE', "%$description%");
			       })
			       ->where(function($query) use ($type) {
				        if ( !is_null($type) )
					        $query->where('type', $type);
			       })
			       ->where(function($query) use ($from) {
				       if ( $from )
				       	    $query->where('created', '>=', $from . ' 00:00:00');
			       })
			       ->where(function($query) use ($to) {
				        if ( $to )
				       	    $query->where('created', '<=', $to . ' 23:59:59');
			       })
			       ->skip($page * $step)
			       ->take($step)
			       ->orderBy('id', 'DESC')
			       ->get() ?? [];
	}

	/**
	 * Delete all rows by array of ids
	 *
	 * @param array $ids
	 * @return int
	 */
	function deleteByIDs($ids = [])
	{
		return $this->whereIn('id', $ids)->delete();
	}

	/**
	 * Update description
	 *
	 * @param int $id
	 * @param array $update
	 */
	function updateData($id, $update = [])
	{
		$this->where('id', $id)
			->first()
			->update($update);
	}

}
