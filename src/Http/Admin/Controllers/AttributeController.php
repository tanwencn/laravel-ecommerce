<?php

namespace Tanwencn\Ecommerce\Http\Admin\Controllers;

use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Tanwencn\Cms\Http\Admin\Controller;
use Tanwencn\Ecommerce\Models\ProductAttribute;

class AttributeController extends Controller
{
    use ModelForm;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('商品属性');

            $content->breadcrumb(
                ['text' => '属性列表']
            );

            $content->body($this->grid());
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Admin::content(function ($content) {
            $content->header('创建分类');
            $content->body($this->form());
        });
    }

    public function edit($id)
    {
        return Admin::content(function ($content) use ($id) {
            $content->header('编辑分类');

            $content->breadcrumb(
                ['text' => '编辑分类']
            );

            $content->body($this->form()->edit($id));
        });
    }

    protected function grid()
    {
        return Admin::grid(ProductAttribute::class, function (Grid $grid) {

            if (request('trashed') == 1) {
                $grid->model()->onlyTrashed();
            }

            $grid->model()->onlyName();

            $grid->id('ID')->sortable();

            $grid->title('属性名称')->limit(30);

            $grid->children('属性值')->pluck('title')->label();

            $grid->created_at('创建时间');

            $grid->rows(function (Grid\Row $row) {
                if ($row->id % 2) {
                    //$row->setAttributes(['class' => 'odd']);
                }
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ProductAttribute::class, function (Form $form) {

            $form->text('title','属性名称')->rules('required');

            $form->hasMany('children', '属性值', function (Form\NestedForm $form) {
                $form->text('title', '');//->rules('required');
                $form->hidden('taxonomy')->value('product_attr_val');
            });
        });
    }
}
