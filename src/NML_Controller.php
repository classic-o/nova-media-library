<?php

namespace ClassicO\NovaMediaLibrary;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class NML_Controller
{

	/** Allowed extension types */
	public $types = [];
	/** Will put image to this folder */
	public $folder = '';
	/** Will split images by year-month */
	public $split = '';


	/** Updating our variables by config */
	function __construct() {
		$this->folder = storage_path('app/public'. config('media-library.folder', '/media/'));

		$this->types = config('media-library.type', ['jpg', 'jpeg', 'png', 'gif', 'svg']);

		if ( config('media-library.split', true) ) $this->split = date('Y-m') .'/';
	}

	/**
	 * Get all media (40 per request).
	 * You can filter by `title` or `created` date
	 *
	 * @param Request   $request
	 * @param NML_Model $model
	 *
	 * @return array
	 */
	function get(Request $request, NML_Model $model)
	{
		$title  = ('%'. trim(htmlspecialchars($request->get('title'))) .'%') ?? null;
		$step   = (int)$request->get('step') ?? null;
		$from   = $request->get('from') ?? null;
		$to     = $request->get('to') ?? null;

		$valid_from = Validator::make($request->only('from'), ['from' => 'nullable|date_format:Y-m-d']);
		$valid_to = Validator::make($request->only('to'), ['from' => 'nullable|date_format:Y-m-d']);

		if ( $title == '' ) $title = null;
		if ( $step < 0 ) $step = 0;
		if ( !$valid_from->passes() or !$from ) $from = null;
		if ( !$valid_to->passes() or !$to ) $to = null;

		return $model->search($title, $from, $to, $step);
	}

	/**
	 * Get info about media file
	 *
	 * @param Request $request
	 *
	 * @return array
	 */
	function single(Request $request)
	{
		$id = floor($request->get('id'));
		$item = NML_Model::find($id);
		if ( !$item ) return [];

		try {
			$file = $this->folder .'/'. $item->image['name'];
			$size = File::size($file) / 1024;
			$type = File::mimeType($file);
		} catch (\Exception $e) {
			return [];
		}

		$after = ' KB';
		if ( $size/1024 > 1 ) {
			$size = $size / 1024;
			$after = ' MB';
		}

		return [
			'id' => $item->id,
			'title' => $item->title,
			'url' => $item->image['url'],
			'created' => Carbon::make($item->created)->format('Y-m-d  H:i'),
			'size' => round($size, 2) . $after,
			'type' => $type
		];
	}

	/**
	 * Upload image to storage
	 *
	 * @param Request   $request
	 * @param NML_Model $model
	 *
	 * @return array
	 */
	function upload(Request $request, NML_Model $model)
	{
		$time = time() .'-';
		$num = (int)$request->get('num') ?? 0;
		$file = $request->file('file');
		if ( !$file ) return [ 'error' => 'No file' ];

		$type = strtolower($file->getClientOriginalExtension());
		if ( !in_array( $type, $this->types ) ) return [ 'error' => 'Forbidden file format' ];

		$image = $time . Str::random(40) . $num .'.'. $type;

		$_name = explode('.', $file->getClientOriginalName());
		array_pop($_name);
		$title = htmlspecialchars(implode('-', $_name));

		if ( !File::isDirectory($this->folder . $this->split) ) {
			$dirs = str_replace(storage_path('app/public/'), '', $this->folder . $this->split);
			$dirs = explode('/', $dirs);
			$dir = storage_path('app/public');
			foreach ($dirs as $d) {
				if ($d == '') continue;
				$dir .= '/'. $d;
				if ( !File::isDirectory($dir) ) {
					File::makeDirectory($dir);
				}
			}
		}

		$move = $file->move($this->folder . $this->split, $image);
		if ( $move ) {
			$model->insert([ 'title' => $title, 'image' => $this->split . $image ]);
			return [ 'status' => true ];
		}

		return [ 'error' => 'Unknown error. Try again' ];
	}

	/**
	 * Delete all selected images
	 *
	 * @param Request   $request
	 * @param NML_Model $model
	 *
	 * @return array
	 */
	function delete(Request $request, NML_Model $model)
	{
		$valid = Validator::make($request->only('ids'), ['ids' => 'required|array']);
		if ( !$valid->passes() ) return [];

		$get = NML_Model::find($request->get('ids'));
		$delete = $model->deleteByIds($request->get('ids'));

		if ( count($get) > 0 ) {
			foreach ($get as $key) {
				File::delete($this->folder . $key->image['name']);
			}
		}

		return [ 'status' => !!$delete ];
	}

	/**
	 * Update media file data
	 *
	 * @param Request $request
	 * @param NML_Model $model
	 *
	 * @return array
	 */
	function update(Request $request, NML_Model $model)
	{
		$valid = Validator::make($request->all(), [
			'id' => 'required|numeric',
			'title' => 'required|string'
		]);
		if ( !$valid->passes() ) return [];

		$model->updateData($request->get('id'), $request->get('title'));
		return [ 'status' => true ];
	}

}
