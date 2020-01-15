<?php

namespace ClassicO\NovaMediaLibrary;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class MediaField extends Field
{
    public $component = 'nml-field';

	protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
	{
		if ( !isset($this->meta['forTrix']) and $request->exists($requestAttribute) ) {
			$value = $request[$requestAttribute];
			if ( !$value or 'null' === $value ) $value = null;
			if ( isset($this->meta['listing']) ) $value = json_decode($request[$requestAttribute], true);
			$model->{$attribute} = array_unique($value);
		}
	}

	/**
	 * Hide image on detail page and show by click on button
	 *
	 * @return $this
	 */
	public function isHidden()
	{
		return $this->withMeta([ 'isHidden' => true ]);
	}

	/**
	 * Set field as list.
	 * Table column must be `TEXT` nullable
	 * Set casts as `array` in model:
	 * [ 'column_name' => 'array' ]
	 *
	 * @param string $as - display method
	 * @return $this
	 */
	public function listing($as = 'gallery')
	{
		if ( $as !== 'line' ) $as = 'gallery';
		return $this->withMeta([ 'listing' => $as ]);
	}

	/**
	 * Limit display by file extension
	 *
	 * @param array|string $types
	 * @return $this
	 */
	public function withTypes($types = [])
	{
		return $this->withMeta([
			'withTypes' => is_array($types)
				? $types : [$types]
		]);
	}

	/**
	 * Snap media field to Trix editor
	 * To connect media field to trix editor, set here unique name
	 * and to Trix field add extra attribute `trix-nml` with this name
	 *
	 * @param string $name - unique name of Trix field
	 * @return $this
	 *
	 * @example Trix::make('Content')
	 *              ->withMeta([ 'extraAttributes' => [ 'trix-nml' => 'unique_trix_name' ] ])
	 */
	public function forTrix($name = 'unique_trix_name')
	{
		return $this->onlyOnForms()
		            ->withMeta([ 'forTrix' => $name ]);
	}

	/**
	 * Use media field with custom callback.
	 *
	 * @param string $callback - Name of the callback function in JS
	 *        (First parameter will be array of files, second - config)
	 * @param array $config - add custom config
	 * @return $this
	 */
	public function jsCallback($callback = null, $config = [])
	{
		return $this->onlyOnForms()
		            ->withMeta([
		            	'jsCallback' => $callback,
			            'jsCbConfig' => $config
		            ]);
	}

}
