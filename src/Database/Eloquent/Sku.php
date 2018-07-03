<?php

namespace Tanwencn\Ecommerce\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Tanwencn\Blog\Database\Eloquent\Model;

class Sku extends Model
{
    use SoftDeletes;

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