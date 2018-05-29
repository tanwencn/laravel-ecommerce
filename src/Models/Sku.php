<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */
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
        return $this->belongsTo(Product::class, 'target_id');
    }

    public function setSkuNameAttribute($value){
        $this->attributes['sku_name'] =  $value?:'';
    }

    public function getNameAttribute(){
        return $this->product->title." ".$this->sku_name;
    }
}