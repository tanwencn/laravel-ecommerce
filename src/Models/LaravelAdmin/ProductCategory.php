<?php

namespace Tanwencn\Ecommerce\Models\LaravelAdmin;


use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Tanwencn\Cms\Models\Traits\LaravelAdminTrait;

class ProductCategory extends \Tanwencn\Ecommerce\Models\ProductCategory
{
    use ModelTree;use AdminBuilder;use LaravelAdminTrait;
}