<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */

namespace Tanwencn\Ecommerce;

use Illuminate\Support\ServiceProvider;
use Tanwencn\Ecommerce\Database\Eloquent;

class AdminServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

        //$this->loadTranslationsFrom(__DIR__ . '/../resources/lang', '*');

        $this->registerMenus();

        $this->registerWidgets();

    }

    protected function registerMenus()
    {
        \Admin::menu()->define(trans('admin.product'), [
            'icon' => 'fa-shopping-bag',
            'authority' => 'view_product',
            'sort' => 55
        ], [
            trans('admin.all_product') => [
                'uri' => 'products',
                'authority' => 'view_product'
            ],
            trans('admin.add_product') => [
                'uri' => 'products/create',
                'authority' => 'add_product'
            ],
            trans('admin.product_attribute') => [
                'uri' => 'ecommerce/attributes',
                'authority' => 'view_product_attribute'
            ],
            trans('admin.category') => [
                'uri' => 'ecommerce/categories',
                'authority' => 'view_product_category'
            ],
            trans('admin.tag') => [
                'uri' => 'ecommerce/tags',
                'authority' => 'view_product_tag'
            ]
        ]);
    }

    protected function registerWidgets(){
        \Admin::side()->add(trans_choice('admin.product_category', 0), Eloquent\ProductCategory::class);
        \Admin::side()->add(trans_choice('admin.product_tag', 0), Eloquent\ProductTag::class);

        //\Admin::dashboard()->left(DashboardLeftWidget::class);
        //\Admin::dashboard()->right(DashboardRightWidget::class);

        \Admin::asset()->js('/vendor/laravel-ecommerce/js/products.select.attributes.min.js', 1);
    }
}
