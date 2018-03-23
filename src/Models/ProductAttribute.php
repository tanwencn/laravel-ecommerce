<?php

namespace Tanwencn\Ecommerce\Models;

use Tanwencn\Cms\Models\Abstracts\TermAbstract;
use Tanwencn\Cms\Models\Traits\ContentTrait;

class ProductAttribute extends TermAbstract
{
    use ContentTrait;

    public function getTaxonomy()
    {
        return 'product_attr';
    }

    public function children(){
        return $this->hasMany(static::class, 'parent_id');
    }

    public function scopeOnlyName($query, $exchange = false)
{
    return $query->where('parent_id', ($exchange?'!=':'='), 0);
}
}