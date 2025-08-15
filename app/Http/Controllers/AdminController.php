<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Blog;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function admin()
    {
        $settings = Setting::all();
        $blogs = Blog::all();
        return view('admin.setting.index', compact('settings' , 'blogs'));
    }
   
    public function blog()
    {
        $blogs = Blog::with('category')->get();
     
        return view('admin.blog.index', compact('blogs'));
    }
    

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required',
        ]);

        $data = $request->except(['_token', 'featured_image']);
        $data['user_id'] = auth()->id(); 
        if (!$request->filled('slug')) {
            $data['slug'] = Str::slug($request->title);
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Blog::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('images', 'public');
        }
        $blog = Blog::create($data);
        return redirect()->route('blog.index')->with('success', 'Blog created successfully.');
    }
    

    public function show(Blog $blog)
    {
        return view('blog.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.blog.edit', compact('blog', 'categories', 'tags'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tag_id' => 'nullable|exists:tags,id',
            'is_published' => 'nullable|boolean',
        ]);
        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image && file_exists(storage_path('app/public/' . $blog->featured_image))) {
                unlink(storage_path('app/public/' . $blog->featured_image));
            }
            $validated['featured_image'] = $request->file('featured_image')->store('images', 'public');
        }
        $blog->update($validated);
        return redirect()->route('blog')->with('success', 'Blog updated successfully.');
    }
    
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return back()->with('success', 'Blog deleted successfully.');
    }

    public function projects()
    {
        return view('admin.projects.index');
    }
    
}
