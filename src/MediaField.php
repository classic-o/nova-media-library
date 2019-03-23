<?php

namespace ClassicO\NovaMediaLibrary;

use Laravel\Nova\Fields\Field;

class MediaField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'media-field';

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
	 * Set field as gallery.
	 * Table column must by `TEXT` nullable
	 * Model $casts: `column` => `array`
	 *
	 * @return $this
	 */
	public function isGallery()
	{
		return $this->withMeta([ 'isGallery' => true ]);
	}

}
