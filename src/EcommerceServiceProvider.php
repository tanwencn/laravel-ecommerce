<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */

namespace Tanwencn\Ecommerce;

use Illuminate\Support\Facades\View;
use Tanwencn\Ecommerce\Bootstrap\AdminBootstrap;
use Tanwencn\Ecommerce\Bootstrap\AppBootstrap;

class EcommerceServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    //protected $defer = true;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tanwencms');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/laravel-ecommerce')]);

            $this->publishes([__DIR__ . '/../resources/assets' => public_path('vendor/laravel-ecommerce')], 'public');
            $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang')], 'lang');
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'migrations');
            $this->publishes([__DIR__ . '/../database/seeds' => database_path('seeds')], 'seeds');
        }
        
        AppBootstrap::boot();

        AdminBootstrap::boot();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {

        $this->app->singleton('Ecommerce', function () {

        });
    }
}
