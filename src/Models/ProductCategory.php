<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */
namespace Tanwencn\Ecommerce\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Tanwencn\Cms\Models\Abstracts\TermAbstract;
use Tanwencn\Cms\Models\Contracts\LinkContracts;
use Tanwencn\Cms\Models\Traits\ContentTrait;

class ProductCategory extends TermAbstract implements LinkContracts
{
    use Cachable;

    public function getUrlAttribute()
    {
        return url("/product-category/{$this->id}");
    }
}