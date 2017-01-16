<?php

namespace Infinety\ConfigManager;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Infinety\ConfigManager\Helpers\ConfigHelper;
use Infinety\Config\ConfigServiceProvider;

class ConfigManagerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     */
    public function boot(Router $router)
    {
        // Publishing configs
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('config-manager.php'),
        ], 'config');

        // Publishing views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'configmanager');

        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/vendor/infinety/configmanager'),
        ], 'views');

        // Loading translations
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'configmanager');

        $this->publishes([
            __DIR__.'/resources/lang' => base_path('resources/lang'),
        ], 'lang');

        // Loading routes
        $config = $this->app['config']->get('config-manager.route', []);
        $config['namespace'] = 'Infinety\ConfigManager';
        $router->group($config, function ($router) {
            $router->get('/{file?}', ['as' => 'configmanager.index', 'uses' => 'Controllers\ConfigManagerController@index']);
            $router->get('view/{file?}', ['as' => 'configmanager.view', 'uses' => 'Controllers\ConfigManagerController@view'])->where('file', '.*');
            $router->put('/update', ['as' => 'configmanager.update', 'uses' => 'Controllers\ConfigManagerController@update']);
        });

        // Publishing public assets
        // $this->publishes([
        //     __DIR__.'/assets' => public_path('my-vendor/my-package'),
        // ], 'public');
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->app->singleton('config.helper', function () {
            return new ConfigHelper($this->app['config']);
        });

        // Register dependency packages
        $this->app->register(ConfigServiceProvider::class);

        // Register dependancy aliases
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ConfigHelper', ConfigManagerFacade::class);
    }
}
