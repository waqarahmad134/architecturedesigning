<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function blog(Request $request)
    {
        $limit = $request->input('limit', 5); // 5 will come from setting to make it dynamic
        $offset = $request->input('offset', 0);

        $blogs = Blog::with('category','tags')
               ->orderBy('published_at','desc')
               ->skip($offset)
               ->take($limit)
               ->get();

        if ($request->ajax()) {
            return view('blog.partials.blog_list', compact('blogs'))->render();
        }

        $categories = Category::withCount('blogs')->get(); 
        $tags = Tag::withCount('blogs')->get();

        return view('blog.blog', compact('blogs', 'categories', 'tags' , 'limit'));
    }

    public function blogpostview($slug)
    {
        $blog = Blog::with(['author', 'category', 'tags'])->where('slug', $slug)->first();
        $relatedBlogs = Blog::where('category_id', $blog->category_id)
        ->where('id', '!=', $blog->id)
        ->published()
        ->latest()
        ->take(3)
        ->get(); 

        if ($blog) {
            return view('blog.blog_details', compact('blog', 'relatedBlogs'));
        } else {
            return view('blog.blog_details', compact('blog', 'relatedBlogs'));
        }
    }
    
    public function categoryFilter($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $blogs = Blog::with('category', 'tags')
                    ->where('category_id', $category->id)
                    ->paginate(6);

        $categories = Category::withCount('blogs')->get();
        $tags = Tag::withCount('blogs')->get();

        return view('blog.blog', compact('blogs', 'categories', 'tags', 'category'));
    }

    public function categoryAjax($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $blogs = Blog::with('category', 'tags')
                    ->where('category_id', $category->id)
                    ->get();

        $view = view('blog.partials.blog_list', compact('blogs'))->render();

        return response()->json(['html' => $view]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $blogs = Blog::with('category', 'tags')
            ->where('title', 'like', "%{$keyword}%")
            ->orWhere('content', 'like', "%{$keyword}%")
            ->paginate(6)
            ->appends(['keyword' => $keyword]); // Retain search term in pagination links

        $categories = Category::withCount('blogs')->get();
        $tags = Tag::withCount('blogs')->get();

        return view('blog.blog', compact('blogs', 'categories', 'tags'))->with('search', $keyword);
    }

}
