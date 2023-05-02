<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private $route = 'article-detail';

    public function __construct(){
        if(Auth::check()){
            $this->route = 'article.show';
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $article = Article::find(1);
        dd($article->id);
        return route('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'article_id' => $request->article_id,
            'body' => $request->comment
        ];

        Comment::create($data);

        return redirect()->route($this->route, $request->slug)->banner('Comment Article Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $article_comment)
    {
        $article_comment->delete();

        return redirect()->route($this->route, $article_comment->article->slug)->banner('Comment in Article Successful Deleted.');
    }
}
