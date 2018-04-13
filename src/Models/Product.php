<?php

namespace Tanwencn\Ecommerce\Models;

use Tanwencn\Cms\Models\Abstracts\ContentAbstract;

class Product extends ContentAbstract
{
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