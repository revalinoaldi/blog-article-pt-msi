<?php

namespace App\Helpers;

use App\Models\Article;
use Illuminate\Support\Facades\Cache;

/**
 * Format response.
 */
class RedisCacher
{
    public static function setCache(){
        try {
            $article = Cache::remember('article', now()->addMinute(1440), function () {
                $article = Article::with(['category'])
                    ->where('is_publish', "1")
                    ->whereHas('category', function($q){
                        return $q->where('deleted_at', NULL);
                    });
                return self::getCache();
            });
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public static function findCache(){
        return Cache::has('article');
    }

    public static function getCache(){
        if (!Cache::has('article')) {
            return self::setCache();
        }

        return Cache::get('article');
    }

    public static function rebuildCache(){
        if (Cache::has('article')) {
            self::destroyCache();
        }
        return self::getCache();
    }

    public static function destroyCache(){
        return Cache::forget('article');
    }
}
