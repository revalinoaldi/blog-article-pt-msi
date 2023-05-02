<?php

namespace App\Models;

use App\Models\User;
use App\Models\ArticleCategory;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $guarded = ['id'];
    protected $with = ['category','author','comment'];

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id', 'id')->withTrashed();
    }

    public function author()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'article_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function scopeFilter($query, array $filter)
    {
        $query->when($filter['search'] ?? false, function($query, $search){
            return $query->where('title','like', "%" .$search. "%")
                  ->orWhere('body','like', "%" .$search. "%")
                  ->orWhere('excerpt','like', "%" .$search. "%")
                  ->orwhereHas('category', function($query) use($search){
                    $query->where('name','like',"%{$search}%");
                  })->orwhereHas('author', function($query) use($search){
                    $query->where('username','like',"%{$search}%")
                          ->orWhere('name','like',"%{$search}%");
                  });
        });

        $query->when($filter['category'] ?? false, function($query, $category){
            return $query->whereHas('category', function($query) use($category){
                $query->where('slug',$category);
            });
        });

        $query->when($filter['author'] ?? false, fn($query, $author) =>
            $query->whereHas('author', fn($query) =>
                $query->where('username', $author)
            )
        );
    }
}
