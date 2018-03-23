<?php

namespace Tanwencn\Ecommerce\Models\LaravelAdmin;


use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class ProductCategory extends \Tanwencn\Ecommerce\Models\ProductCategory
{
    use ModelTree;use AdminBuilder;
}