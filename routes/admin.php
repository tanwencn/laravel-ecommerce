<?php

Admin::router()->middleware(['admin'])->group(function ($router) {

    $router->namespace('Tanwencn\Ecommerce\Http\Controllers')->group(function ($router) {

        $router->resource('products', 'ProductController');

        $router->resource('ecommerce/categories', 'ProductCategoryController')->names('product_categories');

        $router->post('ecommerce/categories/order', 'ProductCategoryController@order')->name('admin.product_categories.order');

        $router->resource('ecommerce/tags', 'ProductTagController')->names('product_tags');

        $router->resource('ecommerce/attributes', 'ProductAttributeController')->names('product_attributes');

    });

});
