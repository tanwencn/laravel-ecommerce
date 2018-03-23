<?php

namespace Tanwencn\Ecommerce\Helpers;

use Encore\Admin\Admin;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Extension;

class LaravelAdmin extends Extension
{
    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();

        static::importAssets();

        Admin::extend('ecommerce', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    public static function registerRoutes()
    {
        parent::routes(function ($router) {
            $router->resource('ecommerce/products', 'Tanwencn\Ecommerce\Http\Admin\Controllers\ProductController', [
                'names' => [
                    'index' => 'Ecommerce.admin.products.index',
                    'create' => 'Ecommerce.admin.products.create',
                    'store' => 'Ecommerce.admin.products.store',
                    'show' => 'Ecommerce.admin.products.show',
                    'edit' => 'Ecommerce.admin.products.edit',
                    'update' => 'Ecommerce.admin.products.update',
                    'destroy' => 'Ecommerce.admin.products.destroy'
                ]
            ]);
            $router->get('ecommerce/product/findAttrValues', 'Tanwencn\Ecommerce\Http\Admin\Controllers\ProductController@findAttrValues')->name('Ecommerce.admin.products.findAttrValues');

            $router->resource('ecommerce/categories', 'Tanwencn\Ecommerce\Http\Admin\Controllers\CategoryController');

            $router->resource('ecommerce/tags', 'Tanwencn\Ecommerce\Http\Admin\Controllers\TagController');

            $router->resource('ecommerce/attributes', 'Tanwencn\Ecommerce\Http\Admin\Controllers\AttributeController');
        });
    }

    public static function import()
    {
        $lastOrder = Menu::max('order');

        $root = [
            'parent_id' => 0,
            'order'     => $lastOrder++,
            'title'     => trans_choice('TanwenCms::admin.product', 0),
            'icon'      => 'fa-align-center',
            'uri'       => '',
        ];

        $root = Menu::create($root);



        $menus = [
            [
                'title'     => __('TanwenCms::admin.all_product'),
                'icon'      => 'fa-circle-o',
                'uri'       => 'ecommerce/products',
            ],
            [
                'title'     => __('TanwenCms::admin.add_product'),
                'icon'      => 'fa-circle-o',
                'uri'       => 'ecommerce/products/create',
            ],
            [

                'title'     => trans_choice('TanwenCms::admin.product_attribute', 0),
                'icon'      => 'fa-circle-o',
                'uri'       => 'ecommerce/attributes',
            ],
            [

                'title'     => trans_choice('TanwenCms::admin.product_category', 0),
                'icon'      => 'fa-circle-o',
                'uri'       => 'ecommerce/categories',
            ],
            [
                'title'     => trans_choice('TanwenCms::admin.product_tag', 0),
                'icon'      => 'fa-circle-o',
                'uri'       => 'ecommerce/tags',
            ]
        ];

        foreach ($menus as $menu) {

            $menu['parent_id'] = $root->id;
            $menu['order'] = $lastOrder++;

            Menu::create($menu);
        }


        parent::createPermission(trans_choice('TanwenCms::admin.product', 0), 'products', 'ecommerce/products*');
        parent::createPermission(trans_choice('TanwenCms::admin.product_category', 0), 'product.categories', 'ecommerce/categories*');
        parent::createPermission(trans_choice('TanwenCms::admin.product_tag', 0), 'product.tags', 'ecommerce/tags*');
    }

    public static function importAssets()
    {
        Admin::js(asset('vendor/laravel-ecommerce/js/admin/products.select.attributes.js'));
    }
}