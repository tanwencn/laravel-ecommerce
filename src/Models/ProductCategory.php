<?php

namespace Tanwencn\Ecommerce\Models;

use Tanwencn\Cms\Models\Abstracts\TermAbstract;
use Tanwencn\Cms\Models\Traits\ContentTrait;

class ProductCategory extends TermAbstract
{
    use ContentTrait;

    public function getTaxonomy()
    {
        return 'product_cat';
    }


}