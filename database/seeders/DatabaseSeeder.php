<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(4)->create();

        ArticleCategory::create([
            'name' => 'Programming',
            'slug' => 'programming'
        ]);

        ArticleCategory::create([
            'name' => 'Personal',
            'slug' => 'personal'
        ]);

        ArticleCategory::create([
            'name' => 'Web Design',
            'slug' => 'web-design'
        ]);

        ArticleCategory::create([
            'name' => 'Android Developer',
            'slug' => 'android-developer'
        ]);

        Article::factory(25)->create();

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'username' => 'administrator',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10)
        ]);
    }
}
