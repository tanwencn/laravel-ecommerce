<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use Tanwencn\Blog\Database\Eloquent\PostCategory;
use Tanwencn\Blog\Http\Controllers\Controller;
use Tanwencn\Blog\Http\Resources\DestroyResource;
use Tanwencn\Blog\Http\Resources\SaveResource;
use Tanwencn\Ecommerce\Database\Eloquent\ProductCategory;

class ProductCategoryController extends Controller
{
    use SaveResource, DestroyResource;
    protected $model = ProductCategory::class;

    public function create()
    {
        return $this->_form(new $this->model);
    }

    public function index()
    {
        return $this->view('index', [
            'data' => $this->model::tree()->get()
        ]);
    }

    public function edit($id)
    {
        return $this->_form($this->model::findOrFail($id));
    }

    protected function _form($model)
    {
        $data = $this->model::tree()->get();

        return $this->view('add_edit', compact('model', 'data'));
    }

    public function store()
    {
        return $this->save(new $this->model, [
            'title' => 'required|max:80'
        ]);
    }

    public function order(Request $request)
    {
        if ($request->input('_nestable')) {
            $data = json_decode($request->input('nestable', []), true);
            $this->model::saveOrder($data);

            return response([
                'status' => true,
                'message' => trans('admin.save_succeeded'),
            ]);
        }
    }

    public function update($id)
    {
        return $this->save($this->model::findOrFail($id), [
            'title' => 'required|max:80'
        ]);
    }

    protected function abilitiesMap()
    {
        return array_merge(parent::abilitiesMap(), [
            'order' => 'edit'
        ]);
    }


}
