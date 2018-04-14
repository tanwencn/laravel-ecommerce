<?php

namespace Tanwencn\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tanwencn\Cms\Helpers\RelationHelper;
use Tanwencn\Cms\Http\Controllers\Admin\Traits\AdminTrait;
use Tanwencn\Ecommerce\Models\ProductAttribute;

class AttributeController extends Controller
{
    use AdminTrait;

    public function __construct()
    {
        $this->middleware('curd:'.ProductAttribute::class.',false');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->setPageTitle(trans_choice('ecommerce.product_attribute', 0));
        $model = ProductAttribute::tree(0, '*')->orderByDesc('id');

        $search = $request->query('search');
        if (!empty($search)) {
            $model->where('title', 'like', "%{$search}%");
        }

        $results = $model->paginate();

        return $this->view('product_attributes.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->setPageTitle(trans('ecommerce.add_attribute'));
        return $this->_form(new ProductAttribute());
    }

    public function edit($id)
    {
        $this->setPageTitle(trans('ecommerce.edit_attribute'));
        return $this->_form(ProductAttribute::findOrFail($id));
    }

    protected function _form(ProductAttribute $model)
    {
        $this->addBreadcrumb([
            'name' => trans('admin.posts_tag'),
            'url' => $this->_action('index')
        ]);

        return $this->view('product_attributes.add_edit', compact('model'));
    }

    public function store()
    {
        return $this->_save(new ProductAttribute());
    }

    public function update($id)
    {
        return $this->_save(ProductAttribute::findOrFail($id));
    }

    protected function _save(ProductAttribute $model)
    {
        $request = request();

        $this->validate($request, [
            'title' => 'required|max:80'
        ]);

        RelationHelper::boot($model)->save();

        return redirect($this->_action('index'))->with('toastr_success', trans('admin.save_succeeded'));
    }

    public function destroy($id)
    {
        $ids = explode(',', $id);
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $model = ProductAttribute::findOrFail($id);
            $model->delete();
        }

        return response([
            'status' => true,
            'message' => trans('admin.delete_succeeded'),
        ]);
    }
}
