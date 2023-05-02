<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // $redis = Redis::connection(); diel
        $params = [
            'publish' => "1"
        ];

        if (request('search')) {
            $params['search'] = request('search');
        }
        $response = Http::get('http://article-blog.test/api/article',$params);
        $data = $response->json();

        return view('dashboard',[
            'articles' => $this->paginate($data['data']['data'])
        ]);
    }

    public function paginate($items, $perPage = 9, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path'  => url()->current()
        ]);
    }

    public function articleList(Article $article)
    {
        return view('article.single-post', [
            'article' => $article,
        ]);
    }
}
