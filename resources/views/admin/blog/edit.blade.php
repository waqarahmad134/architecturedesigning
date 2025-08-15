@extends('layouts.app')
@section('content')

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
</script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<div class="slider-area2">
    <div class="slider-height2 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="hero-cap hero-cap2 pt-70 text-center">
                        <h2>{{ $blog->title }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="" style="display:flex;justify-content:center;margin:50px 0px;">
    <div class="col-md-7 col-lg-8 col-xl-9" style="">
        <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
    
            <!-- Title -->
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $blog->title }}" required>
            </div>
    
            <!-- Slug -->
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ $blog->slug }}" required>
            </div>
    
            <!-- Content -->
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="summernote" name="content" id="content" class="form-control" required>{{ $blog->content }}</textarea>
            </div>
    
            <!-- Meta Title -->
            <div class="form-group">
                <label for="meta_title">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ $blog->meta_title }}">
            </div>
    
            <!-- Meta Description -->
            <div class="form-group">
                <label for="meta_description">Meta Description</label>
                <textarea name="meta_description" id="meta_description" class="form-control">{{ $blog->meta_description }}</textarea>
            </div>
    
            <!-- Meta Keywords -->
            <div class="form-group">
                <label for="meta_keywords">Meta Keywords</label>
                <textarea name="meta_keywords" id="meta_keywords" class="form-control">{{ $blog->meta_keywords }}</textarea>
            </div>
    
            <!-- Category -->
            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $blog->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <!-- Tag -->
            <div class="form-group">
                <label for="tag_id">Tag</label>
                <select name="tag_id" id="tag_id" class="form-control">
                    <option value="">Select Tag</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ $blog->tag_id == $tag->id ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>


    
            <!-- Featured Image -->
            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <img src="{{ asset('storage/app/public/' . $blog->featured_image) }}" alt="img" width="60">
            </div>
            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <input type="file" name="featured_image" id="featured_image" class="form-control">
            </div>
    
            <!-- Published At -->
            <div class="form-group">
                <label for="published_at">Published At</label> {{ ($blog->published_at) }}
                <input type="date" name="published_at" id="published_at" class="form-control" value="{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d') : '' }}">
            </div>
    
            <!-- Is Published -->
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_published" id="is_published" value="1" {{ $blog->is_published ? 'checked' : '' }}>
                    Publish Now
                </label>
            </div>
    
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>


<script>
    $('#summernote').summernote({
        tabsize: 2,
        height: 200
    });
</script>

@endsection
