<?php

namespace Tanwencn\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use Tanwencn\Blog\Http\Controllers\Controller;
use Tanwencn\Blog\Http\Resources\DestroyResource;
use Tanwencn\Blog\Http\Resources\SaveResource;
use Tanwencn\Ecommerce\Database\Eloquent\ProductAttribute;

class ProductAttributeController extends Controller
{
    use SaveResource,DestroyResource;

    protected $model = ProductAttribute::class;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = ProductAttribute::tree(0, '*');

        $search = $request->query('search');
        if (!empty($search)) {
            $model->where('title', 'like', "%{$search}%");
        }

        $results = $model->paginate();

        return $this->view('index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->_form(new ProductAttribute());
    }

    public function edit($id)
    {
        return $this->_form(ProductAttribute::findOrFail($id));
    }

    protected function _form(ProductAttribute $model)
    {
        return $this->view('add_edit', compact('model'));
    }

    public function store()
    {
        return $this->save(new ProductAttribute(), [
            'title' => 'required|max:80'
        ]);
    }

    public function update($id)
    {
        return $this->save(ProductAttribute::findOrFail($id), [
            'title' => 'required|max:80'
        ]);
    }
}
