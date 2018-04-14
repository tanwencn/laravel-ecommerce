<?php

namespace Tanwencn\Ecommerce\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    use SoftDeletes, Cachable;

    public $relation_key = 'sku_code';

    protected $guarded = [];

    protected $touches = ['product'];

    protected $table = 'stock_keeping_units';

    public function product()
    {
        return $this->belongsTo(Product::class, 'body_id');
    }

    public function setSkuNameAttribute($value){
        $this->attributes['sku_name'] =  $value?:'';
    }
}