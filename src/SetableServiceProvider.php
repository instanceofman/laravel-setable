<?php
namespace Isofman\LaravelSetable;

use Illuminate\Support\ServiceProvider;
use Isofman\LaravelSetable\Commands;

class SetableServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Create handler
        $this->app->singleton('setable', function() {
            return new SettingHandler;
        });

        // Register commands
        $this->commands([
            Commands\ListSettings::class,
            Commands\Setup::class,
            Commands\Set::class,
            Commands\Get::class,
        ]);

        app('events')->listen('cache:cleared', function() {
            $this->loadSettings();
        });
    }

    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->registerConfig();
        }

        $this->loadSettings();
    }

    public function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/setable.php' => config_path('setable.php')
        ], 'setable-config');
    }

    protected function loadSettings()
    {
        if (! app('cache')->get('__cached_sets')) {
            $settings = Setting::all()->mapWithKeys(function($item) {
                return [$item->opt_key => $item->opt_value];
            })->toArray();
            $this->cacheSettings($settings);
        }

        else {
            $settings = app('cache')->get('__sets');
        }

        app('config')->set('setable', $settings);
    }

    protected function cacheSettings($items)
    {
        app('cache')->set('__cached_sets', true);

        app('cache')->set('__sets', $items);
    }
}