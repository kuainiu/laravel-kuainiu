<?php

namespace Kuainiu;

use Illuminate\Contracts\Container\Container;
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
        $this->setupConfig();
        $this->extendSocialite();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/kuainiu.php');

        // Check if the application is a Laravel OR Lumen instance to properly merge the configuration file.
        if ($this->app->runningInConsole()) {
            $this->publishes([$source => config_path('kuainiu.php')]);
        }

        $this->mergeConfigFrom($source, 'kuainiu');
    }

    /**
     * Extend the Laravel Socialite factory class, if available.
     *
     * @return void
     */
    protected function extendSocialite()
    {
        if (interface_exists('Laravel\Socialite\Contracts\Factory')) {
            $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');

            $socialite->extend('kuainiu', function (Container $app) use ($socialite) {
                $config = $app['config']['services.kuainiu'];

                return $socialite->buildProvider(KuainiuConnectProvider::class, $config);
            });
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerApiClient();
        $this->registerManager();
    }

    /**
     * Register the Mollie API Client.
     *
     * @return void
     */
    protected function registerApiClient()
    {
        $this->app->singleton('kuainiu.api', function (Container $app) {

            return new KuainiuApi();
        });

        $this->app->alias('kuainiu.api', KuainiuApi::class);
    }

    /**
     * Register the manager class.
     *
     * @return void
     */
    public function registerManager()
    {
        $this->app->singleton('kuainiu', function (Container $app) {
            return new KuainiuManager($app);
        });

        $this->app->alias('kuainiu', KuainiuManager::class);
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
            'kuainiu.api',
        ];
    }
}
