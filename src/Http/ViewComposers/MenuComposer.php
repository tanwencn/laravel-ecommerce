<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/3/19 16:16
 */

namespace Tanwencn\Ecommerce\Http\ViewComposers;


use Illuminate\View\View;
use Tanwencn\Ecommerce\Models\Product;
use Tanwencn\Ecommerce\Models\ProductCategory;

class MenuComposer
{
    public function compose(View $view)
    {
        $view->multiple_choice_sidebar =array_merge($view->multiple_choice_sidebar, [
            ProductCategory::tree()->get(),
            Product::all()
        ]);
    }
}