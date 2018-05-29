<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */
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

        return $this->view('product_category.add_edit', compact('model', 'data'));
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
            return $this->save(new ProductCategory());
        }
    }

    public function update($id)
    {
        return $this->save(ProductCategory::findOrFail($id));
    }

    protected function save(ProductCategory $model)
    {
        $request = request();

        $this->validate($request, [
            'title' => 'required|max:80'
        ]);

        RelationHelper::boot($model)->save();

        return redirect($this->_action('index'))->with('toastr_success', trans('admin.save_succeeded'));
    }

    public function _destroy($id)
    {
        $model = ProductCategory::findOrFail($id);
        $model->delete();
    }
}
