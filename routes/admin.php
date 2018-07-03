<?php

Admin::router()->group(function ($router) {

    $router->namespace('Tanwencn\Ecommerce\Http\Controllers')->group(function ($router) {

        $router->resource('products', 'ProductController');

        $router->resource('ecommerce/categories', 'CategoryController');

        $router->resource('ecommerce/tags', 'TagController');

        $router->resource('ecommerce/attributes', 'AttributeController');

    });

});
