<?php

namespace ClassicO\NovaMediaLibrary;

use Laravel\Nova\Fields\Field;
use ClassicO\NovaMediaLibrary\Core\Model;
use ClassicO\NovaMediaLibrary\Core\Helper;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\SupportsDependentFields;
use ClassicO\NovaMediaLibrary\Traits\CanResolveMedia;

class MediaLibrary extends Field
{
    use SupportsDependentFields;
    use CanResolveMedia;
    
    public $component = 'media-library-field';
    private $preview = null;

    private function privateUrl($item)
    {
        if ($item and $item->private and ! $item->url) {
            $item = $item->toArray();
            $item['url'] = route('nml-private-file-admin', [ 'id' => $item['id'] ]);
        }
        data_set($item, 'preview', Helper::preview($item, $this->preview));

        return $item;
    }

    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);

        if (! $this->value) {
            return $this->value = null;
        }

        $value = $this->value;
        $this->value = null;
        $data = Core\Model::find($value);

        if (is_array($value)) {
            if (! count($data)) {
                return $this->value = null;
            }
            $data = $data->keyBy('id');
            $this->value = [];

            foreach ($value as $i) {
                if (isset($data[$i])) {
                    $this->value[] = $data[$i];
                }
            }
        } else {
            if (! $data) {
                return $this->value = null;
            }
            $this->value = $data;
        }

        $this->preview = array_key_exists('nmlPreview', $this->meta)
            ? $this->meta['nmlPreview'] : config('nova-media-library.resize.preview');

        if (is_array($value)) {
            $this->value = collect($this->value)->map(function ($item) {
                return $this->privateUrl($item);
            });
        } elseif ($value) {
            $this->value = $this->privateUrl($this->value);
        }

        if (! $this->value) {
            $this->value = null;
        }
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (! isset($this->meta['nmlTrix']) and ! isset($this->meta['nmlJsCallback']) and $request->exists($requestAttribute)) {
            $value = $request[$requestAttribute];

            if (! $value or 'null' == $value) {
                $value = null;
            }

            if (isset($this->meta['nmlArray'])) {
                $value = json_decode($request[$requestAttribute], true);

                if (is_array($value) and true != config('nova-media-library.duplicates')) {
                    $value = array_unique($value);
                }
            }
            $model->{$attribute} = $value;
        }
    }

    /**
     * Hide image on detail page and show by click on button
     *
     * @return $this
     */
    public function hidden()
    {
        return $this->withMeta([ 'nmlHidden' => true ]);
    }

    /**
     * Preview size of images (Label of cropped additional image variation)
     *
     * @param null|string $size - label from config: resize.sizes
     * @return $this
     */
    public function preview($size)
    {
        return $this->withMeta([ 'nmlPreview' => $size ]);
    }

    /**
     * Contain array of files. Display as list
     * Table column must be `TEXT` nullable
     * Set casts as `array` in model
     *
     * @param string $display - display method (gallery or list)
     * @return $this
     */
    public function array($display = 'auto')
    {
        if (! in_array($display, ['gallery', 'list'])) {
            $display = 'auto';
        }

        return $this->withMeta([ 'nmlArray' => $display ]);
    }

    /**
     * Limit display by file extension
     *
     * @param array|string $types
     * @return $this
     */
    public function types($types)
    {
        return $this->withMeta([
            'nmlTypes' => is_array($types)
                ? $types : [$types],
        ]);
    }

    /**
     * Snap media field to Trix editor
     * To connect media field to trix editor, set here unique name
     * and to Trix field add extra attribute `nml-trix` with this name
     *
     * @param string $name - unique name of Trix field
     * @return $this
     *
     * @example
     *  MediaLibrary::make('Media Library')->trix('unique_trix_name')
     *  Trix::make('Content')->withMeta([ 'extraAttributes' => [ 'nml-trix' => 'unique_trix_name' ] ])
     */
    public function trix($name = 'unique_trix_name')
    {
        return $this->onlyOnForms()
            ->withMeta([ 'nmlTrix' => $name ]);
    }

    /**
     * Use media field with custom callback.
     *
     * @param string $callback - Name of the callback function in JS
     *        (First parameter will be array of files, second - options)
     * @param mixed $options - add custom options
     * @return $this
     *
     * @example
     *  MediaLibrary::make('Media Library')->jsCallback('callback', [ 'example' => true ])
     *  // In any JS file create function:
     *  window.callbackName = (array, options) => { ... }
     */
    public function jsCallback($callback, $options = [])
    {
        return $this->onlyOnForms()
            ->withMeta([ 'nmlJsCallback' => [$callback, $options] ]);
    }
}
