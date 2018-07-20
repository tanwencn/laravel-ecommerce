<?php

namespace Tanwencn\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use Tanwencn\Blog\Http\Controllers\Controller;
use Tanwencn\Blog\Http\Resources\ContentResource;
use Tanwencn\Ecommerce\Database\Eloquent\Product;
use Tanwencn\Ecommerce\Database\Eloquent\ProductAttribute;
use Tanwencn\Ecommerce\Database\Eloquent\ProductCategory;
use Tanwencn\Ecommerce\Database\Eloquent\ProductTag;

class ProductController extends Controller
{
    use ContentResource;

    protected $model = Product::class;

    public function index(Request $request)
    {
        //基础数据
        $model = Product::with('categories', 'tags', 'skus')->withCount('skus');

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
            'total' => $this->model::withUnReleased()->count(),
            'release' => $this->model::count(),
            'unrelease' => $this->model::onlyUnReleased()->count(),
            'delete' => $this->model::onlyTrashed()->withUnReleased()->count()
        ];

        return $this->view('index', compact('results', 'statistics', 'title', 'att'));
    }

    protected function _form($model)
    {
        $model->load(['metas', 'skus' => function ($query) {
            $query->withTrashed();
        }]);

        $data = compact('model');

        $data['categories'] = ProductCategory::tree()->get();
        $model->categories = old('categories', $model->categories->pluck('id')->toArray());

        $data['tags'] = ProductTag::select('id', 'parent_id', 'title')->get();
        $model->tags = old('tags', $model->tags->pluck('id')->toArray());

        $data['attributes'] = ProductAttribute::tree()->get();
        $model->attributes = collect(old('attributes', $model->attributes->pluck('id')->toArray()));

        $data['skus'] = collect(old('skus', $model->skus->keyBy('sku_code')->toArray()));

        return $this->view('add_edit', $data);
    }

    protected function validatesMap(){
        return [
            'title' => 'required|max:120',
            'excerpt' => 'max:80',
            'gallery' => 'array',
            'skus.*.price' => 'numeric|min:1',
            'skus.*.cost_price' => 'numeric|min:1',
            'skus.*.market_price' => 'numeric|min:1',
            'skus.*.price' => 'numeric|min:1',
            'skus.*.stock' => 'numeric|min:1',
        ];
    }
}
