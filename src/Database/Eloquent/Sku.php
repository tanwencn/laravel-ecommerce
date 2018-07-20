<?php

namespace Tanwencn\Ecommerce\Database\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Tanwencn\Blog\Database\Eloquent\CacheModel;

class Sku extends CacheModel
{
    use SoftDeletes;

    public $relation_key = 'sku_code';

    protected $guarded = [];

    protected $touches = ['product'];

    protected $table = 'stock_keeping_units';

    public function product()
    {
        return $this->belongsTo(Product::class, 'target_id');
    }

    public function setSkuNameAttribute($value){
        $this->attributes['sku_name'] =  $value?:'';
    }
}