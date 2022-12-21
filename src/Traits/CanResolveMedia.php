<?php

namespace ClassicO\NovaMediaLibrary\Traits;

use ClassicO\NovaMediaLibrary\Core\Model;
use ClassicO\NovaMediaLibrary\Core\Helper;

trait CanResolveMedia
{

    public function resolveFieldValues(string|array $mediaIds, bool $isArray = true)
    {
        if (is_string($mediaIds)) {
            $mediaIds = [$mediaIds];
        }
        $resolvedMedia =  collect($mediaIds)->map(function ($mediaId) {
            $model = Model::find($mediaId);
            if (!$model) {
                return;
            }
            return $this->setPrivateUrlOnAttachment($model);
        })->filter()->all();
        
        if (array_key_exists('nmlArray', $this->meta ?? []) || $isArray) {
            return $resolvedMedia;
        }
        return $resolvedMedia[0];
    }

    private function setPrivateUrlOnAttachment($item)
    {
        $preview = config('nova-media-library.resize.preview');
        if ($item and $item->private and !$item->url) {
            $item = $item->toArray();
            $item['url'] = route('nml-private-file-admin', ['id' => $item['id']]);
        }
        data_set($item, 'preview', Helper::preview($item, $preview));

        return $item;
    }
}
