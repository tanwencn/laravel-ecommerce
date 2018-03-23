<?php

namespace Tanwencn\Ecommerce\Http\Admin\Controllers;

use Tanwencn\Ecommerce\Models\LaravelAdmin\ProductCategory;

class CategoryController extends \Tanwencn\Cms\Http\Admin\Controllers\CategoryController
{
    protected $model = ProductCategory::class;

}
