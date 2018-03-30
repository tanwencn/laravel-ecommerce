<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */

namespace Tanwencn\Ecommerce;

use Illuminate\Support\Facades\View;
use Tanwencn\Ecommerce\Http\ViewComposers\MultipleChoiceSidebarComposer;

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
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'Ecommerce');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'TanwenCms');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/laravel-ecommerce')], 'tanwencms-publish');
        }

        View::composer('TanwenTanwenCms::admin.menus.add_edit', MultipleChoiceSidebarComposer::class);

        LaravelAdmin::boot();
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
