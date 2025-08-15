@extends('layouts.app')
@section('content')


<div class="slider-area2">
    <div class="slider-height2 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="hero-cap hero-cap2 pt-70 text-center">
                        @if(isset($search))
                            <h2>Search Results for: "{{ $search }}"</h2>
                        @else
                            <h2>{{ isset($category) ? 'Category: ' . $category->name : 'All Blogs' }}</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="blog_area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-9 mb-5 mb-lg-0" ">
                <div class="row blog_left_sidebar" id="blog-container">
                    @include('blog.partials.blog_list', ['blogs' => $blogs])
                </div>
                <!-- <nav class="blog-pagination justify-content-center d-flex">
                </nav> -->
                <div class="text-center mt-4">
                    <button id="load-more"
                            class="btn btn-primary"
                            data-offset="{{ $blogs->count() }}"
                            data-limit="{{ $limit ?? 5 }}">
                        Load More
                    </button>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="blog_right_sidebar">
                <aside class="single_sidebar_widget search_widget">
                    <form action="{{ route('blog.search') }}" method="GET">
                        <div class="form-group my-sm-0">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Search Keyword"
                                    value="{{ request('keyword') }}"
                                    onfocus="this.placeholder = ''"
                                    onblur="this.placeholder = 'Search Keyword'">
                                <div class="input-group-append">
                                    <button class="btns" type="submit"><i class="ti-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <!-- <button class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn"
                            type="submit">Search</button> -->
                    </form>
                </aside>

                    <aside class="single_sidebar_widget post_category_widget">
                        <h4 class="widget_title" style="color: #2d2d2d;">Category</h4>
                        <ul class="list cat-list">
                            <!-- @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('blog.category', $category->slug) }}" class="d-flex justify-content-between">
                                        <p>{{ $category->name }}</p>
                                        <p>({{ $category->blogs_count }})</p>
                                    </a>
                                </li>
                            @endforeach                           -->
                            @foreach($categories as $category)
                                <li>
                                    <a href="javascript:void(0);" 
                                    class="category-filter d-flex justify-content-between" 
                                    data-slug="{{ $category->slug }}">
                                        <p>{{ $category->name }}</p>
                                        <p>({{ $category->blogs_count }})</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                   
                    <aside class="single_sidebar_widget tag_cloud_widget">
                        <h4 class="widget_title" style="color: #2d2d2d;">Tag</h4>
                        <ul class="list">
                            @foreach($tags as $tag)  
                                <li>
                                    <a href="#">{{ $tag->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).on('click', '.category-filter', function(e) {
        e.preventDefault();
        let slug = $(this).data('slug');

        $.ajax({
            url: "{{ url('/blog/category/ajax') }}/" + slug,
            type: 'GET',
            success: function(response) {
                $('#blog-container').html(response.html);
            },
            error: function() {
                alert('Failed to load blogs for this category.');
            }
        });
    });
</script>

<!-- <script>
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');

        $.ajax({
            url: url,
            success: function(data) {
                $('#blog-list').html(data);
                $('.blog-pagination').html(newContent.find('.blog-pagination').html());
                window.history.pushState("", "", url); // Optional: updates browser URL
            },
            error: function(xhr) {
                alert("Something went wrong!");
            }
        });
    });
</script> -->

<script>
  $('#load-more').on('click', function () {
    let btn    = $(this);
    let offset = btn.data('offset');
    let limit  = btn.data('limit');

    btn.prop('disabled', true).text('Loading…');

    $.ajax({
      url: "{{ route('blog') }}",
      method: 'GET',
      data: { offset: offset, limit: limit },
      success: function (html) {
        if (html.trim() === '') {
          btn.hide(); // no more posts
        } else {
          $('#blog-container').append(html);
          btn.data('offset', offset + limit);
          btn.prop('disabled', false).text('Load More');
        }
      },
      error: function () {
        alert('Failed to load more blogs.');
        btn.prop('disabled', false).text('Load More');
      }
    });
  });
</script>

@endsection
