<?php

namespace Kuainiu;

use Illuminate\Support\ServiceProvider;

/**
 * Class MollieServiceProvider.
 */
class KuainiuServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/kuainiu.php' => config_path('kuainiu.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('kuainiu', KuainiuApi::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'kuainiu',
        ];
    }
}
