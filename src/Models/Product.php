<?php

namespace Tanwencn\Ecommerce\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Tanwencn\Cms\Models\Abstracts\ContentAbstract;

class Product extends ContentAbstract
{
    use Cachable;

    public function categories(){
        return $this->terms(ProductCategory::class);
    }

    public function tags(){
        return $this->terms(ProductTag::class);
    }

    public function attributes(){
        return $this->terms(ProductAttribute::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class, 'target_id');
    }

    public function getImageAttribute(){
        $gallery = $this->gallery;
        if(!empty($gallery)){
            return $gallery[0];
        }
    }

    public function getGalleryAttribute(){
        return $this->getMetas('gallery')?json_decode($this->getMetas('gallery')):[];
    }
}