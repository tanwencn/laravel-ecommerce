<?php

namespace Tanwencn\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tanwencn\Cms\Http\Controllers\Admin\Traits\AdminTrait;
use Tanwencn\Cms\Models\Term;
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
        $model = Product::with('terms', 'skus')->withCount('skus')->byOrder('new');


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
            $model->orWhereHas('terms', function ($query) use ($search) {
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

        return $this->view('products.index', compact('results', 'categories', 'terms', 'statistics', 'title'));
    }

    public function create()
    {
        return $this->_form(new Product());
    }

    public function edit($id)
    {
        $model = Product::with([
            'terms' => function ($query) {
                $query->select('term_id');
            }, 'skus' => function ($query) {
                return $query->withTrashed();
            }, 'metas'
        ])->findOrFail($id);


        $this->setPageTitle(trans('Ecommerce::admin.edit_product'));

        return $this->_form($model);
    }

    protected function _form(Product $product)
    {
        $categories = ProductCategory::tree()->get();
        $tags = ProductTag::select('id', 'parent_id', 'title')->get();
        $attriibutes = ProductAttribute::tree()->get();

        $skus = collect(old('skus', []))->pipe(function ($collect) use ($product) {
            if ($product->id && $collect->isEmpty()) {
                return $product->skus->keyBy('sku_code');
            } else {
                return $collect;
            }
        })->toJson();

        $terms = collect(old('terms', []))->pipe(function ($collect) use ($product) {
            if ($product->id && $collect->isEmpty()) {
                return $product->terms->pluck('term_id');
            } else {
                return $collect;
            }
        });

        return $this->view('products.add_edit', compact('product', 'attriibutes', 'categories', 'tags', 'skus', 'terms'));
    }

    protected function save(Product $model)
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
        $input = $request->all();

        $model->fill($input);

        $model->save();

        if (!empty($input['skus'])) {
            $model->syncMany('skus', 'sku_code', $input['skus']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->save(new Product());
        return redirect(app('request')->getUri())->with('toastr_success', trans('admin.save_succeeded'));
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
                    $model->fill($input)->save();
                }
            }
            return response([
                'status' => true,
                'message' => trans('admin.save_succeeded'),
            ]);
        }

        $model = Product::withUnReleased()->findOrFail($id);
        $this->save($model);

        return redirect(route('admin.products.index'))->with('toastr_success', trans('admin.save_succeeded'));
    }

    public function destroy($id)
    {
        $ids = explode(',', $id);
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $model = Product::withUnReleased()->withTrashed()->findOrFail($id);
            if ($model->trashed()) {
                $model->forceDelete();
                $model->terms()->detach();
                $model->skus()->forceDelete();
                $model->metas()->forceDelete();
            } else {
                $model->delete();
            }
        }
        return response([
            'status' => true,
            'message' => trans('admin.save_succeeded'),
        ]);
    }
}
