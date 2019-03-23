<?php

namespace ClassicO\NovaMediaLibrary;

use Illuminate\Database\Eloquent\Model;

class NML_Model extends Model {

	public $timestamps = false;

	protected $table = 'nova_media_library';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id', 'title', 'image', 'created'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'created' => 'datetime'
	];

	/**
	 * Create full url and name for each image
	 *
	 * @param $name
	 *
	 * @return array
	 */
	function getImageAttribute($name) {
		return [
			'url' => config('media-library.url', '/storage') . config('media-library.folder', '/media/') . $name,
			'name' => $name
		];
	}

	/**
	 * Search media files by some params
	 *
	 * @param string $title - part of title
	 * @param string $from  - from upload date
	 * @param string $to    - to upload date
	 * @param integer $step - 1 step = 40 items
	 * @return array
	 */
	function search($title, $from, $to, $step)
	{
		$nova_step = config('media-library.step');
		return $this
			       ->where(function($query) use ($title) {
				       if ( !is_null($title) ) $query->where('title', 'LIKE', $title);
			       })
			       ->where(function($query) use ($from) {
				       if ( !is_null($from) ) $query->where('created', '>=', $from . ' 00:00:00');
			       })
			       ->where(function($query) use ($to) {
				       if ( !is_null($to) ) $query->where('created', '<=', $to . ' 23:59:59');
			       })
			       ->skip($step * $nova_step)
			       ->take($nova_step)
			       ->orderBy('id', 'DESC')
			       ->get(['id', 'title', 'image']) ?? [];
	}

	/**
	 * Delete all rows by array of ids
	 *
	 * @param array $ids
	 * @return int
	 */
	function deleteByIds($ids = [])
	{
		return $this->whereIn('id', $ids)->delete();
	}

	/**
	 * Update title
	 *
	 * @param int $id
	 * @param string $title
	 */
	function updateData($id, $title = '')
	{
		$this
			->where('id', $id)
			->first()
			->update([ 'title' => $title ]);
	}

}
