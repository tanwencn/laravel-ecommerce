<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */

namespace Tanwencn\Ecommerce\Bootstrap;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Tanwencn\Cms\Helpers\AdminHelper;
use Tanwencn\Ecommerce\Models;

class AdminBootstrap
{
    public static function boot()
    {
        $bootstrap = new static();
        $bootstrap->registerRoutes();
        $bootstrap->registerMenus();
        $bootstrap->registerAssets();
        $bootstrap->registerMultipleChoiceSidebar();
    }

    protected function registerAssets(){
        AdminHelper::addJsFile('/vendor/laravel-cms/commerce/js/products.select.attributes.js');
    }

    protected function registerMenus()
    {


    }

    protected function registerRoutes()
    {
        AdminHelper::route()->group(function ($router) {
            $router->namespace('Tanwencn\Ecommerce\Http\Controllers\Admin')->group(function ($router) {

                $router->resource('products', 'ProductController');

                $router->resource('ecommerce/categories', 'CategoryController');

                $router->resource('ecommerce/tags', 'TagController');

                $router->resource('ecommerce/attributes', 'AttributeController');

            });
        });
    }

    protected function registerMultipleChoiceSidebar(){
        AdminHelper::addMultipleChoiceSidebar(trans_choice('ecommerce.product_category', 0), Models\ProductCategory::class, true);
        AdminHelper::addMultipleChoiceSidebar(trans('ecommerce.product'), Models\Product::class);
    }
}
