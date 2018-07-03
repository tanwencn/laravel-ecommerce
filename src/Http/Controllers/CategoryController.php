<?php

namespace Tanwencn\Ecommerce\Http\Controllers\Admin;


use Illuminate\Routing\Controller;
use Tanwencn\Cms\Helpers\RelationHelper;
use Tanwencn\Cms\Http\Controllers\Admin\Traits\AdminTrait;
use Tanwencn\Ecommerce\Models\ProductCategory;

class CategoryController extends Controller
{
    use AdminTrait;

    public function __construct()
    {
        $this->middleware('curd:' . ProductCategory::class . ',false');
    }

    public function index()
    {
        $this->setPageTitle(trans("ecommerce.product_category"));
        return $this->view('product_category.index', [
            'data' => ProductCategory::tree()->get()
        ]);
    }

    public function create()
    {
        $this->setPageTitle(trans('admin.add_category'));
        return $this->_form(new ProductCategory());
    }

    public function edit($id)
    {
        $this->setPageTitle(trans('admin.edit_category'));
        return $this->_form(ProductCategory::findOrFail($id));
    }

    protected function _form(ProductCategory $model)
    {
        $this->addBreadcrumb([
            'url' => request()->getPathInfo(),
            'name' => trans('admin.category')
        ]);


        $data = ProductCategory::tree()->get();

        $action = $model->id?$this->getUrl('update', $model->id):$this->getUrl('store');

        return $this->view('product_category.add_edit', compact('model', 'data', 'action'));
    }

    public function store()
    {
        $request = request();
        if ($request->input('_nestable')) {
            $data = json_decode($request->input('nestable', []), true);
            ProductCategory::saveOrder($data);

            return response([
                'status' => true,
                'message' => trans('admin.save_succeeded'),
            ]);
        } else {
            return $this->_save(new ProductCategory());
        }
    }

    public function update($id)
    {
        return $this->_save(ProductCategory::findOrFail($id));
    }

    protected function _save(ProductCategory $model)
    {
        $request = request();

        $this->validate($request, [
            'title' => 'required|max:80'
        ]);

        RelationHelper::boot($model)->save();
    }

    public function destroy($id)
    {
        $ids = explode(',', $id);
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $model = ProductCategory::findOrFail($id);
            $model->delete();
        }

        return response([
            'status' => true,
            'message' => trans('admin.delete_succeeded'),
        ]);
    }
}
