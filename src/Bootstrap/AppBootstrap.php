<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */

namespace Tanwencn\Ecommerce\Bootstrap;

use Illuminate\Support\Facades\Blade;

class AppBootstrap
{
    public static function boot()
    {
        $boot = new static();
        $boot->registerBlades();
    }


    protected function registerBlades(){
        
    }
}
