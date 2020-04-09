<?php

namespace ClassicO\NovaMediaLibrary;

use ClassicO\NovaMediaLibrary\Core\CategoryModel;
use ClassicO\NovaMediaLibrary\Core\Helper;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaMediaLibrary extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        if (Auth::user()->can('Mediaverwaltung') === false) {
            return false;
        }

	    Nova::script('nova-media-library', __DIR__.'/../dist/js/tool.js');
	    Nova::style('nova-media-library', __DIR__.'/../dist/css/tool.css');

	    Nova::provideToScript([ 'novaMediaLibrary' => $this->config() ]);
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        if (Auth::user()->can('Mediaverwaltung')) {
            return view('nova-media-library::navigation');
        }
    }



    private function config()
    {
    	$cfg = config('nova-media-library');
	    $types = data_get($cfg, 'types');

    	$config = [
		    'can_private' => 's3' == data_get($cfg, 'disk'),
		    'disk' => data_get($cfg, 'disk', 'public'),
		    'front_crop' => data_get($cfg, 'resize.front_crop', false),
		    'lang' => $this->lang(),
		    'store' => data_get($cfg, 'store', 'together'),
	    ];

    	if ( 'folders' == $config['store'])
    		$config['folders'] = Helper::directories();

	    if ( is_array($types) ) {
		    $accept = [];

		    foreach ($types as $key)
			    $accept = array_merge($accept, $key);

		    if ( in_array('*', $accept) )
		    	$accept = [];

		    $config['accept'] = preg_filter('/^/', '.', $accept);
		    $config['types'] = array_keys($types);
		    $config['categories'] = CategoryModel::all();
	    }

	    return $config;
    }

	private function lang()
	{
		$file = resource_path('lang/vendor/nova-media-library/'.app()->getLocale().'.json');
		if ( !is_readable($file)) return [];

		$json = json_decode(file_get_contents($file));
		return is_object($json) ? $json : [];
	}
}
