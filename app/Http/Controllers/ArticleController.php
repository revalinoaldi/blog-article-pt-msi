<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\ArticleCategory;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $params = [];
        if (request('search')) {
            $params['search'] = request('search');
        }
        $response = Http::get('http://article-blog.test/api/article', $params);
        $data = $response->json();
        // dd($data);

        return view('article.index', [
            'article' => $data['data'],
            'articles' => $this->paginate($data['data']['data'])
        ]);
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path'  => url()->current()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = ArticleCategory::all();
        return view('article.form', [
            'categories' => $category
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $data = [
            'category_id' => $request->categories,
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'slug' => SlugService::createSlug(Article::class, 'slug', $request->title),
            'excerpt' => Str::limit(strip_tags($request->body), 130, '...'),
            'body' => $request->body,
            'is_publish' => $request->is_publish,
        ];

        if($data['is_publish'] == "1"){
            $data['publish_at'] = now();
        }

        if ($request->hasFile('file_input')) {
            $pureFiles = $this->_uploadFiles($request, $data);

            if(@$pureFiles != false){
                $data['image'] = $pureFiles;
            }
        }

        Article::create($data);

        return redirect()->route('article.index')->banner('Article Created Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('article.single-post', [
            'article' => $article,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $category = ArticleCategory::all();
        return view('article.form', [
            'article' => $article,
            'categories' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        if ($request->publish == "only_publish") {
            $data = $request->validate([
                'is_publish' => 'required'
            ]);
            $status = $this->_processPublish($data, $article);

            return redirect()->route('article.show', $article->slug)->banner('Article Was Successfully '.$status.' on '.date('d F Y H:i:s'));
        }

        $validRules = [
            'title' => 'required|max:255|min:4',
            'categories' => 'required|exists:article_categories,id',
            'body' => 'required||min:4',
            'is_publish' => 'required'
        ];

        if ($request->hasFile('file_input')) {
            $validRules['file_input'] = 'image|file|mimes:png,jpg,jpeg,svg|max:2048';
        }

        $valid = $request->validate($validRules);

        $data = [
            'category_id' => $valid['categories'],
            'user_id' => auth()->user()->id,
            'title' => $valid['title'],
            'slug' => SlugService::createSlug(Article::class, 'slug', $valid['title']),
            'excerpt' => Str::limit(strip_tags($valid['body']), 130, '...'),
            'body' => $valid['body'],
            'is_publish' => $valid['is_publish'],
        ];

        if($data['is_publish'] == "1"){
            $data['publish_at'] = now();
        }else{
            $data['publish_at'] = null;
        }

        if ($request->hasFile('file_input')) {
            $pureFiles = $this->_uploadFiles($request, $data);

            if(@$pureFiles != false){
                $data['image'] = $pureFiles;
            }
        }

        $article->update($data);

        return redirect()->route('article.show', $article->slug)->banner('Article Was Updated Successfully on '.date('d F Y H:i:s'));

    }

    private function _processPublish(array $data, $article)
    {
        if($data['is_publish'] == "1"){
            $data['publish_at'] = now();
        }else{
            $data['publish_at'] = null;
        }
        $article->update($data);
        if($data['is_publish'] == "1"){
            $status = "Published";
        }else{
            $status = "Drafted";
        }
        return $status;
    }

    private function _uploadFiles($request, array $data)
    {
        if ($request->hasFile('file_input')) {
            // Get File Name with Ext
            $filenameWithExt = $request->file('file_input')->getClientOriginalName();
            // Get pure file name
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
            $extension = $request->file('file_input')->getClientOriginalExtension();
            // Generate file name
            $filenameSimpan = "{$data['slug']}-".auth()->user()->username.".".$extension;
            return $request->file('file_input')->storeAs('public/post-article', $filenameSimpan);
        }

        return false;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('article.index')->banner('Article Deleted Successfully.');
    }
}
