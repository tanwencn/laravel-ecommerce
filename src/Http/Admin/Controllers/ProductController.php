<?php

namespace Tanwencn\Ecommerce\Http\Admin\Controllers;

use Illuminate\Http\Request;
use Tanwencn\Cms\Http\Admin\Controller;
use Tanwencn\Cms\Models\Term;
use Illuminate\Support\Facades\DB;
use Tanwencn\Ecommerce\Models\ProductAttribute;
use Tanwencn\Ecommerce\Models\ProductCategory;
use Tanwencn\Ecommerce\Models\Product;
use Tanwencn\Ecommerce\Models\ProductTag;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //基础数据
        $terms = ProductCategory::select('id', 'parent_id', 'title')->get();
        $categories = $terms->buildSelect();
        $model = Product::with('terms', 'skus')->withCount('skus')->byOrder('new');


        //筛选器
        $trashed = $request->query('trashed');
        $term_title = $request->query('term_title');
        $title = $request->query('title');
        $release = $request->query('release');

        if ($trashed) {
            $model->onlyTrashed();
        } else {
            if ($release) {
                if ($release != 'up')
                    $model->onlyUnReleased();
            } else {
                $model->withUnReleased();
            }
        }

        $title && $model->where('title', 'like', "%{$title}%");

        !empty($term_title) && $model->whereHas('terms', function ($query) use ($term_title) {
            $query->where('title', 'like', "%{$term_title}%");
        });

        $results = $model->paginate(2);

        $statistics = [
            'total' => Product::withUnReleased()->count(),
            'release' => Product::count(),
            'unrelease' => Product::onlyUnReleased()->count(),
            'delete' => Product::withUnReleased()->onlyTrashed()->count()
        ];

        $this->setPageTitle('商品列表');

        return $this->view('Ecommerce::admin.products.index', compact('results', 'categories', 'terms', 'statistics', 'title'));
    }

    public function create()
    {
        $this->setPageTitle('添加商品');
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


        $this->setPageTitle('编辑商品');

        return $this->_form($model);
    }

    protected function _form(Product $product)
    {

        $categories = ProductCategory::select('id', 'parent_id', 'title')->get()->buildSelect();
        $tags = ProductTag::select('id', 'parent_id', 'title')->get()->buildSelect();
        $attriibutes = ProductAttribute::onlyName()->select('id', 'parent_id', 'title')->get();

        $skus = collect(old('sku', []))->pipe(function ($collect) use ($product) {
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

        return $this->view('Ecommerce::admin.products.add_edit', compact('product', 'attriibutes', 'categories', 'tags', 'skus', 'terms'));
    }

    protected function save(Product $model)
    {
        $request = request();

        $this->validate($request, [
            'title' => 'required|max:120',
            'summary' => 'max:80',
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
        return redirect(app('request')->getUri())->with('prompt', '商品已添加！');
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
                'message' => '修改成功！',
            ]);
        }

        $model = Product::withUnReleased()->findOrFail($id);
        $this->save($model);

        return redirect(route('Ecommerce.admin.products.index'))->with('prompt', '修改成功！');
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
            'message' => '修改成功！',
        ]);
    }

    public function findAttrValues()
    {
        $parent_id = \request('parent_id');
        if (empty($parent_id)) {
            return [];
        }

        return ProductAttribute::whereIn('id', $parent_id)->with(['children' => function ($query) {
            $query->select('id', 'parent_id', 'title');
        }])->select('id', 'parent_id', 'title')->get();
    }
}
