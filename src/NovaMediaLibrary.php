<?php

namespace ClassicO\NovaMediaLibrary;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use ClassicO\NovaMediaLibrary\Core\Helper;

class NovaMediaLibrary extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-media-library', __DIR__ . '/../dist/js/tool.js');
        Nova::style('nova-media-library', __DIR__ . '/../dist/css/tool.css');

        Nova::provideToScript([ 'novaMediaLibrary' => $this->config() ]);
    }

    public function menu(Request $request)
    {
        return MenuSection::make('Media')
            ->path('/nova-media-library')
            ->icon('camera');
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

        if ('folders' == $config['store']) {
            $config['folders'] = [];
        }//Helper::directories();

        if (is_array($types)) {
            $accept = [];

            foreach ($types as $key) {
                $accept = array_merge($accept, $key);
            }

            if (in_array('*', $accept)) {
                $accept = [];
            }

            $config['accept'] = preg_filter('/^/', '.', $accept);
            $config['types'] = array_keys($types);
        }

        return $config;
    }

    private function lang()
    {
        $file = resource_path('lang/vendor/nova-media-library/' . app()->getLocale() . '.json');

        if (! is_readable($file)) {
            return [];
        }

        $json = json_decode(file_get_contents($file));

        return is_object($json) ? $json : [];
    }
}
