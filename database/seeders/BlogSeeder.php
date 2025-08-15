<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $categories = Category::pluck('id')->toArray();
        $tags = Tag::pluck('id')->toArray(); // Only for one tag per blog (optional)

        for ($i = 1; $i <= 10; $i++) {
            $title = "Sample Blog Post $i";

            Blog::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => 'This is the content for blog post ' . $i,
                'meta_title' => $title . ' Meta',
                'meta_description' => 'Meta description for blog post ' . $i,
                'meta_keywords' => 'blog,post,sample,' . $i,
                'featured_image' => 'uploads/blog/default.jpg',
                'user_id' => fake()->randomElement($users),
                'category_id' => fake()->randomElement($categories),
                'tag_id' => fake()->randomElement($tags), // If still using one tag_id
                'published_at' => now(),
                'is_published' => true,
            ]);
        }
    }
}
