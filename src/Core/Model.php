<?php

namespace ClassicO\NovaMediaLibrary\Core;

use Illuminate\Support\Facades\Route;

/**
 * @property $id
 * @property $title
 * @property $created
 * @property $type
 * @property $folder
 * @property $name
 * @property $private
 * @property $lp
 * @property $options
 *
 * @property $url
 * @property $path
 */
class Model extends \Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;

    protected $table = 'nova_media_library';

    protected $fillable = ['id', 'title', 'created', 'type', 'folder', 'name', 'private', 'lp', 'options'];

    protected $appends = ['url', 'path'];

    protected $casts = [
        'created' => 'datetime',
        'options' => 'object',
    ];

    public function getUrlAttribute()
    {
        if ($this->lp) {
            return config('nova-media-library.url', '') . substr($this->path, 7);
        }

        if (! $this->private) {
            return config('nova-media-library.url', '') . $this->path;
        }

        if (Route::has('nml-private-file')) {
            return route('nml-private-file', [ 'id' => $this->id, 'img_size' => request('img_size') ]);
        }

        return null;
    }

    public function getPathAttribute()
    {
        return Helper::folder($this->folder . $this->name);
    }

    public function search()
    {
        $param = request()->all();
        $title = trim(htmlspecialchars(request('title', '')));
        $folder = trim(htmlspecialchars(request('folder', '')));

        $step = config('nova-media-library.step');

        if (! is_int($step) or $step < 1) {
            $step = 40;
        }

        $data = $this
            ->where(function ($query) use ($folder) {
                if ($folder) {
                    $query->where('folder', $folder);
                }
            })
            ->where(function ($query) use ($param) {
                if (is_array($param['type']) and $param['type']) {
                    $query->whereIn('type', $param['type']);
                }
            })
            ->where(function ($query) use ($param) {
                if ($param['from']) {
                    $query->where('created', '>=', $param['from'] . ' 00:00:00');
                }
            })
            ->where(function ($query) use ($param) {
                if ($param['to']) {
                    $query->where('created', '<=', $param['to'] . ' 23:59:59');
                }
            })
            ->where(function ($query) use ($title) {
                if ($title) {
                    $query->where('title', 'LIKE', "%{$title}%");
                }
            });

        return [
            'total' => (clone $data)->count(),
            'array' => $data->skip($param['page'] * $step)
                ->take($step)
                ->orderBy('id', 'DESC')
                ->get() ?? [],
        ];
    }
}
