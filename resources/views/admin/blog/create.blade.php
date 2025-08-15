@extends('layouts.app')
@section('title', 'Create Blog')
@section('admin_content')


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
</script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>


<div class="col-md-7 col-lg-8 col-xl-9">
    <h2>Create Blog</h2>
    <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Title -->
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <!-- Content -->
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="summernote" name="content" id="content" class="form-control"
                required>{{ old('content') }}</textarea>
        </div>

        <!-- Meta Title -->
        <div class="form-group">
            <label for="meta_title">Meta Title</label>
            <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title') }}">
        </div>

        <!-- Meta Description -->
        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <textarea name="meta_description" id="meta_description"
                class="form-control">{{ old('meta_description') }}</textarea>
        </div>

        <!-- Meta Keywords -->
        <div class="form-group">
            <label for="meta_keywords">Meta Keywords</label>
            <textarea name="meta_keywords" id="meta_keywords" class="form-control">{{ old('meta_keywords') }}</textarea>
        </div>

        <!-- Category -->
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                <option value="{{ $tag->id }}" {{ old('tag_id') == $tag->id ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Featured Image -->
        <div class="form-group">
            <label for="featured_image">Featured Image</label>
            <input type="file" name="featured_image" id="featured_image" class="form-control">
        </div>

        <!-- Published At -->
        <div class="form-group">
            <label for="published_at">Published At</label>
            <input type="date" name="published_at" id="published_at" class="form-control"
                value="{{ old('published_at') }}">
        </div>

        <!-- Is Published -->
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_published" id="is_published" value="1"
                    {{ old('is_published') ? 'checked' : '' }}>
                Publish Now
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>

</div>

<script>
$('#summernote').summernote({
    tabsize: 2,
    height: 200
});
</script>


@endsection