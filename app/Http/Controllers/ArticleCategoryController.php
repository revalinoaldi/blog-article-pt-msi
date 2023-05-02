<?php

namespace App\Http\Controllers;

use App\Models\ArticleCategory;
use App\Http\Requests\StoreArticleCategoryRequest;
use App\Http\Requests\UpdateArticleCategoryRequest;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class ArticleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $category = ArticleCategory::all();
        return view('categories-article.index', [
            'categories' => $category
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories-article.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleCategoryRequest $request)
    {
        $data = $request->toArray();
        $data['slug'] = SlugService::createSlug(ArticleCategory::class, 'slug', $request['name']);

        ArticleCategory::create($data);

        return redirect()->route('category-article.index')->banner('Category Article Created Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ArticleCategory $category_article)
    {
        return redirect()->route('category-article.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ArticleCategory $category_article)
    {
        return view('categories-article.form', [
            'category' => $category_article
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleCategoryRequest $request, ArticleCategory $category_article)
    {
        $data = $request->all();
        $data['slug'] = SlugService::createSlug(ArticleCategory::class, 'slug', $request['name']);

        $category_article->update($data);


        return redirect()->route('category-article.index')->banner('Category Article Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArticleCategory $category_article)
    {
        $category_article->delete();

        return redirect()->route('category-article.index')->banner('Category Article Deleted Successfully.');
    }
}
