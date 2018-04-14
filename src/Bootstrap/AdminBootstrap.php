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
        AdminHelper::addMenu('products', [
            'name' => trans_choice('ecommerce.product', 0),
            'icon' => 'fa-shopping-bag'
        ]);
        AdminHelper::addMenu('all_product', [
            'name' => trans('ecommerce.all_product'),
            'uri' => 'products',
            'authority' => ['view', Models\Product::class]
        ], 'products');
        AdminHelper::addMenu('add_product', [
            'name' => trans('ecommerce.add_product'),
            'uri' => 'products/create',
            'authority' => ['add', Models\Product::class]
        ], 'products');
        AdminHelper::addMenu('attributes', [
            'name' => trans_choice('ecommerce.attribute', 0),
            'uri' => 'ecommerce/attributes',
            'authority' => ['view', Models\ProductAttribute::class]
        ], 'products');
        AdminHelper::addMenu('categories', [
            'name' => trans_choice('admin.category', 0),
            'uri' => 'ecommerce/categories',
            'authority' => ['view', Models\ProductCategory::class]
        ], 'products');
        AdminHelper::addMenu('tags', [
            'name' => trans_choice('admin.tag', 0),
            'uri' => 'ecommerce/tags',
            'authority' => ['view', Models\ProductTag::class]
        ], 'products');

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
