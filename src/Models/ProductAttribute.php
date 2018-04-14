<?php

namespace Tanwencn\Ecommerce\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Tanwencn\Cms\Models\Abstracts\TermAbstract;
use Tanwencn\Cms\Models\Traits\ContentTrait;

class ProductAttribute extends TermAbstract
{
    use Cachable;
}