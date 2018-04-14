<?php

namespace Tanwencn\Ecommerce\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Tanwencn\Cms\Models\Abstracts\TermAbstract;
use Tanwencn\Cms\Models\Traits\ContentTrait;

class ProductCategory extends TermAbstract
{
    use Cachable;
}