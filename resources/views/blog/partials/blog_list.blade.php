@foreach($blogs as $blog)
    <div class="col-lg-4">
        <article class="blog_item">
            <div class="blog_item_img">
                <img class="card-img rounded-0" src="{{ asset('storage/app/public/' . $blog->featured_image) }}" alt="{{ $blog->title }}">
                <!-- <a href="#" class="blog_item_date">
                    <h3>{{ \Carbon\Carbon::parse($blog->published_at)->format('d') }}</h3>
                    <span>{{ \Carbon\Carbon::parse($blog->published_at)->format('M') }}</span>
                </a> -->
            </div>
            <div class="blog_details">
                <a class="d-inline-block" href="{{ route('blog.details', $blog->slug) }}">
                    <h2 class="blog-head" style="color: #2d2d2d;">{{ $blog->title }}</h2>
                </a>
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 150) }}</p>
                <ul class="blog-info-link">
                    <li>
                        <a href="#"><i class="fa fa-user"></i> 
                            {{ $blog->category->name ?? 'Uncategorized' }}
                        </a>
                    </li>
                    <!-- <li>
                        <a href="#"><i class="fa fa-comments"></i> 0 Comments</a>
                    </li> -->
                </ul>
            </div>
        </article>
    </div>
@endforeach
