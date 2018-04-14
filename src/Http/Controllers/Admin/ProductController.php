<?php

namespace Tanwencn\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tanwencn\Cms\Helpers\RelationHelper;
use Tanwencn\Cms\Http\Controllers\Admin\Traits\AdminTrait;
use Illuminate\Support\Facades\DB;
use Tanwencn\Ecommerce\Models\ProductAttribute;
use Tanwencn\Ecommerce\Models\ProductCategory;
use Tanwencn\Ecommerce\Models\Product;
use Tanwencn\Ecommerce\Models\ProductTag;

class ProductController extends Controller
{
    use AdminTrait;

    public function __construct()
    {
        $this->middleware('curd:'.Product::class.',ecommerce');
    }

    public function index(Request $request)
    {
        //基础数据
        $categories = ProductCategory::select('id', 'parent_id', 'title', 'taxonomy')->get();
        $model = Product::with('categories', 'tags', 'skus')->withCount('skus')->byOrder('new');


        //筛选器
        $trashed = $request->query('trashed');
        $search = $request->query('search');
        $release = $request->query('release');

        if (!$trashed && !$release) {
            $model->withUnReleased();
        } else {
            if ($trashed) {
                $model->onlyTrashed();
                $model->withUnReleased();
            } else if ($release != 'up') {
                $model->onlyUnReleased();
            }
        }

        if (!empty($search)) {
            $model->where('title', 'like', "%{$search}%");
            $model->orWhereHas('categories', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            });
            $model->orWhereHas('tags', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            });
            $model->orWhereHas('attributes', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            });
        }

        $results = $model->paginate();

        $statistics = [
            'total' => Product::withUnReleased()->count(),
            'release' => Product::count(),
            'unrelease' => Product::onlyUnReleased()->count(),
            'delete' => Product::onlyTrashed()->withUnReleased()->count()
        ];

        return $this->view('products.index', compact('results','statistics', 'title'));
    }

    public function create()
    {
        return $this->_form(new Product());
    }

    public function edit($id)
    {
        $model = Product::withUnReleased()->findOrFail($id);

        $this->setPageTitle(trans('ecommerce.edit_product'));

        return $this->_form($model);
    }

    protected function _form(Product $product)
    {
        $product->load(['metas', 'skus' => function($query){
            $query->withTrashed();
        }]);
        $this->addBreadcrumb([
            'name' => trans_choice('ecommerce.product', 0),
            'url' => $this->_action('index')
        ]);
        $categories = ProductCategory::tree()->get();
        $tags = ProductTag::select('id', 'parent_id', 'title')->get();
        $attributes = ProductAttribute::tree()->get();

        $product->categories = old('categories', $product->categories->pluck('id')->toArray());
        $product->tags = old('tags', $product->tags->pluck('id')->toArray());
        $product->attributes = collect(old('attriibutes', $product->attributes->pluck('id')->toArray()));

        $skus = collect(old('skus', $product->skus->keyBy('sku_code')->toArray()));

        return $this->view('products.add_edit', compact('product', 'attributes', 'categories', 'tags', 'skus'));
    }

    protected function _save(Product $model)
    {
        $request = request();

        $this->validate($request, [
            'title' => 'required|max:120',
            'excerpt' => 'max:80',
            'gallery' => 'array',
            'skus.*.price' => 'numeric|min:1',
            'skus.*.cost_price' => 'numeric|min:1',
            'skus.*.market_price' => 'numeric|min:1',
            'skus.*.price' => 'numeric|min:1',
            'skus.*.stock' => 'numeric|min:1',
        ]);

        RelationHelper::boot($model)->save();

        return redirect($this->_action('index'))->with('toastr_success', trans('admin.save_succeeded'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->_save(new Product());
    }

    public function update($id, Request $request)
    {
        $action = $request->input('_only');
        $only = ['is_release', 'restore'];
        if (in_array($action, $only)) {
            $input = $request->only($only);
            $ids = explode(',', $id);
            foreach ($ids as $id) {
                if (empty($id)) {
                    continue;
                }

                if ($action == 'restore') {
                    $model = Product::withUnReleased()->onlyTrashed()->findOrFail($id);
                    $model->restore();
                } else {
                    $model = Product::withUnReleased()->findOrFail($id);
                    $model->fill($input)->_save();
                }
            }
            return response([
                'status' => true,
                'message' => trans('admin.save_succeeded'),
            ]);
        }

        $model = Product::withUnReleased()->findOrFail($id);
        return $this->_save($model);
    }

    public function _destroy($id)
    {
        $model = Product::withUnReleased()->withTrashed()->findOrFail($id);
        if ($model->trashed()) {
            $model->forceDelete();
        } else {
            $model->delete();
        }
    }
}
