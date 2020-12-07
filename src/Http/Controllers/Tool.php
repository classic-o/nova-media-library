<?php

namespace ClassicO\NovaMediaLibrary\Http\Controllers;

use ClassicO\NovaMediaLibrary\API;
use ClassicO\NovaMediaLibrary\Http\Requests\{
	CropFr,
	DeleteFr,
	GetFr,
	UploadFr,
	UpdateFr,
	FolderNewFr,
	FolderDelFr
};
use ClassicO\NovaMediaLibrary\Core\{
	Crop,
	Helper,
	Model,
	Upload
};

class Tool {

	function get(GetFr $fr)
	{
		$preview = config('nova-media-library.resize.preview');

		$data = (new Model)->search();
		$data['array'] = collect($data['array'])->map(function ($item) use ($preview) {
			if ( !$item->url ) {
				$item = $item->toArray();
				$item['url'] = route('nml-private-file-admin', [ 'id' => $item['id'] ]);
			}
			$item['preview'] = Helper::preview($item, $preview);

			return $item;
		});

		return $data;
	}

	function private()
	{
		$item = Model::find(request('id'));
		$size = request('img_size');

		if ( !$item or !$item->path )
			return response()->noContent(404);

		if ( !in_array($size, data_get($item, 'options.img_sizes', [])) ) $size = null;

		return API::getPrivateFile($item->path, $size);
	}

	function upload(UploadFr $fr)
	{
		$file = request()->file('file');
		$file_name = " ({$file->getClientOriginalName()})";

		$upload = new Upload($file);

		if ( !$upload->setType() )
			abort(422, __('Forbidden file format'));

		$upload->setWH();

		$upload->setFolder(request('folder'));

		$upload->setPrivate();

		$upload->setFile();

		if ( !$upload->checkSize() )
			abort(422, __('File size limit exceeded') . $file_name);

		$item = $upload->save();
		if ( $item ) {
			Crop::createSizes($item);
			if ( $upload->noResize ) {
				abort(200, __('Unsupported image type for resizing, only the original is uploaded') . $file_name);
			}
			return;
		}

		abort(422, __('The file was not downloaded for unknown reasons') . $file_name);
	}

	function delete(DeleteFr $fr)
	{
		$get = Model::find(request('ids'));
		$delete = Model::whereIn('id', request('ids'))->delete();

		if ( count($get) > 0 ) {
			$array = [];
			foreach ($get as $key) {
				$sizes = data_get($key, 'options.img_sizes', []);
				$array[] = Helper::folder($key->folder) . $key->name;

				if ( $sizes ) {
					foreach ($sizes as $size) {
						$name = explode('.', $key->name);
						$array[] = Helper::folder($key->folder . implode('-'. $size .'.', $name));
					}
				}
			}

			Helper::storage()->delete($array);
		}

		return [ 'status' => !!$delete ];
	}

	function update(UpdateFr $fr)
	{
		$item = Model::find(request('id'));
		if ( !$item ) abort(422, __('Invalid id'));

		$item->title = request('title');
		$img_sizes = data_get($item->options, 'img_sizes', []);

		if ( request()->has('private') and 's3' === config('nova-media-library.disk') ) {
			$item->private = (boolean)request('private');
			$visibility = Helper::visibility($item->private);

			Helper::storage()->setVisibility($item->path, $visibility);
			foreach ($img_sizes as $key)
				Helper::storage()->setVisibility(API::getImageSize($item->path, $key), $visibility);
		}

		$folder = request('folder');
		if ( $folder and 'folders' === config('nova-media-library.store') and $folder !== $item->folder ) {
			$private = Helper::isPrivate($folder);
			$array = [ [$item->path, Helper::folder($folder . $item->name)] ];

			foreach ($img_sizes as $key) {
				$name = API::getImageSize($item->name, $key);
				$array[] = [Helper::folder($item->folder . $name), Helper::folder($folder . $name)];
			}

			foreach ($array as $key) {
				Helper::storage()->move($key[0], $key[1]);
				if ( $private != $item->private )
					Helper::storage()->setVisibility($key[1], Helper::visibility($private));
			}

			$item->private = $private;
			$item->folder = Helper::replace('/'. $folder .'/');
			$item->lp = Helper::localPublic($item->folder, $private);
		}

		$item->save();

		return $item;
	}

	function crop(CropFr $fr)
	{
		$crop = new Crop(request()->toArray());
		if ( !$crop->form )
			abort(422, __('Crop module disabled'));

		if ( !$crop->image )
			abort(422, __('Invalid request data'));

		$crop->make();

		if ( $crop->save() ) return;

		abort(422, __('The file was not downloaded for unknown reasons'));
	}

	function folderNew(FolderNewFr $fr)
	{
		if ( Helper::storage()->makeDirectory(Helper::folder(request('base') . request('folder') .'/')) ) {
			return [ 'folders' => Helper::directories() ];
		}

		abort(422, __('Cannot manage folders'));
	}

	function folderDel(FolderDelFr $fr)
	{
		if ( Helper::storage()->deleteDirectory(Helper::folder(request('folder'))) ) {
			return [ 'folders' => Helper::directories() ];
		}

		abort(422, __('Cannot manage folders'));
	}

    function folders()
    {
        return Helper::directories();
    }
}
