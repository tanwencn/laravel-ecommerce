<?php

namespace Tanwencn\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tanwencn\Cms\Helpers\RelationHelper;
use Tanwencn\Cms\Http\Controllers\Admin\Traits\AdminTrait;
use Tanwencn\Ecommerce\Models\ProductTag;

class TagController extends Controller
{
    use AdminTrait;

    public function __construct()
    {
        $this->middleware('curd:'.ProductTag::class.',false');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->setPageTitle(trans('ecommerce.product_tag'));
        $model = ProductTag::orderByDesc('id');

        $search = $request->query('search');
        if (!empty($search)) {
            $model->where('title', 'like', "%{$search}%");
        }

        $results = $model->paginate();

        return $this->view('product_tags.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->setPageTitle(trans('admin.add_tag'));
        return $this->_form(new ProductTag());
    }

    public function edit($id)
    {
        $this->setPageTitle(trans('admin.edit_tag'));
        return $this->_form(ProductTag::findOrFail($id));
    }

    protected function _form(ProductTag $model)
    {
        $this->addBreadcrumb([
            'name' => trans('admin.posts_tag'),
            'url' => $this->_action('index')
        ]);

        return $this->view('product_tags.add_edit', compact('model'));
    }

    public function store()
    {
        $this->_save(new ProductTag());
        return redirect($this->_action('index'))->with('toastr_success', trans('admin.save_succeeded'));
    }

    public function update($id)
    {
        $this->_save(ProductTag::findOrFail($id));
    }

    protected function _save(ProductTag $model)
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
            $model = ProductTag::findOrFail($id);
            $model->delete();
        }

        return response([
            'status' => true,
            'message' => trans('admin.delete_succeeded'),
        ]);
    }
}
