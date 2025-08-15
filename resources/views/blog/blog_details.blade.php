@extends('layouts.app')
@section('content')


<div class="slider-area2">
   <div class="slider-height2 d-flex align-items-center">
         <div class="container">
            <div class="row">
               <div class="col-xl-12">
                     <div class="hero-cap hero-cap2 pt-70 text-center">
                        <h2>Blog Details</h2>
                     </div>
               </div>
            </div>
         </div>
   </div>
</div>

{{($blog)}}

<section class="blog_area single-post-area section-padding">
   <div class="container">
      <div class="row">
         <div class="col-lg-8 posts-list">
            <div class="single-post">
               <div class="feature-img">
                  <img class="img-fluid" src="{{ asset('storage/app/public/images/' . $blog->featured_image) }}" alt="">
               </div>
               <div class="blog_details">
                  <h2 style="color: #2d2d2d;">{{$blog->title}}
                  </h2>
                  <ul class="blog-info-link mt-3 mb-4">
                     @if($blog->category)
                     <li><a href="#"><i class="fa fa-user"></i> {{$blog->category->name}}</a></li>
                     @endif
                     @if($blog->tags->count() > 0)
                     <li><a href="#"><i class="fa fa-tags"></i> {{$blog->tags->pluck('name')->implode(', ')}}</a></li>
                     @endif
                     @if($blog->published_at)
                     <li><a href="#"><i class="fa fa-calendar"></i> {{$blog->created_at->format('d M Y')}}</a></li>
                     @endif
                     <li><a href="#"><i class="fa fa-comments"></i> 03 Comments</a></li>
                  </ul>
                  {!! $blog->content ?? "" !!}
                  <div class="quote-wrapper">
                     <div class="quotes">
                        MCSE boot camps have its supporters and its detractors. 
                     </div>
                  </div>
               </div>
            </div>
            <div class="navigation-top">
               <div class="d-sm-flex justify-content-between text-center">
                  <p class="like-info"><span class="align-middle"><i class="fa fa-heart"></i></span> Lily and 4
                     people like this</p>
                  <ul class="social-icons">
                     <li><a href="https://www.facebook.com/sai4ull"><i class="fab fa-facebook-f"></i></a></li>
                     <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                     <li><a href="#"><i class="fab fa-dribbble"></i></a></li>
                     <li><a href="#"><i class="fab fa-behance"></i></a></li>
                  </ul>
               </div>
            </div>
            <div class="comments-area">
               <h4>05 Comments</h4>
               <div class="comment-list">
                  <div class="single-comment justify-content-between d-flex">
                     <div class="user justify-content-between d-flex">
                        <div class="thumb">
                           <img src="assets/img/blog/comment_1.png" alt="">
                        </div>
                        <div class="desc">
                           <p class="comment">
                              Multiply sea night grass fourth day sea lesser rule open subdue female fill which them
                              Blessed, give fill lesser bearing multiply sea night grass fourth day sea lesser
                           </p>
                           <div class="d-flex justify-content-between">
                              <div class="d-flex align-items-center">
                                 <h5>
                                    <a href="#">Emilly Blunt</a>
                                 </h5>
                                 <p class="date">December 4, 2017 at 3:12 pm </p>
                              </div>
                              <div class="reply-btn">
                                 <a href="#" class="btn-reply text-uppercase">reply</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="comment-list">
                  <div class="single-comment justify-content-between d-flex">
                     <div class="user justify-content-between d-flex">
                        <div class="thumb">
                           <img src="assets/img/blog/comment_2.png" alt="">
                        </div>
                        <div class="desc">
                           <p class="comment">
                              Multiply sea night grass fourth day sea lesser rule open subdue female fill which them
                              Blessed, give fill lesser bearing multiply sea night grass fourth day sea lesser
                           </p>
                           <div class="d-flex justify-content-between">
                              <div class="d-flex align-items-center">
                                 <h5>
                                    <a href="#">Emilly Blunt</a>
                                 </h5>
                                 <p class="date">December 4, 2017 at 3:12 pm </p>
                              </div>
                              <div class="reply-btn">
                                 <a href="#" class="btn-reply text-uppercase">reply</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="comment-list">
                  <div class="single-comment justify-content-between d-flex">
                     <div class="user justify-content-between d-flex">
                        <div class="thumb">
                           <img src="assets/img/blog/comment_3.png" alt="">
                        </div>
                        <div class="desc">
                           <p class="comment">
                              Multiply sea night grass fourth day sea lesser rule open subdue female fill which them
                              Blessed, give fill lesser bearing multiply sea night grass fourth day sea lesser
                           </p>
                           <div class="d-flex justify-content-between">
                              <div class="d-flex align-items-center">
                                 <h5>
                                    <a href="#">Emilly Blunt</a>
                                 </h5>
                                 <p class="date">December 4, 2017 at 3:12 pm </p>
                              </div>
                              <div class="reply-btn">
                                 <a href="#" class="btn-reply text-uppercase">reply</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="comment-form">
               <h4>Leave a Reply</h4>
               <form class="form-contact comment_form" action="#" id="commentForm">
                  <div class="row">
                     <div class="col-12">
                        <div class="form-group">
                           <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9"
                              placeholder="Write Comment"></textarea>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <input class="form-control" name="name" id="name" type="text" placeholder="Name">
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <input class="form-control" name="email" id="email" type="email" placeholder="Email">
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group">
                           <input class="form-control" name="website" id="website" type="text" placeholder="Website">
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <button type="submit" class="button button-contactForm btn_1 boxed-btn">Post Comment</button>
                  </div>
               </form>
            </div>
         </div>
         <div class="col-lg-4">
            <div class="blog_right_sidebar">
               <!-- <aside class="single_sidebar_widget search_widget">
                  <form action="#">
                     <div class="form-group">
                        <div class="input-group mb-3">
                           <input type="text" class="form-control" placeholder='Search Keyword'
                              onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search Keyword'">
                           <div class="input-group-append">
                              <button class="btns" type="button"><i class="ti-search"></i></button>
                           </div>
                        </div>
                     </div>
                     <button class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn"
                        type="submit">Search</button>
                  </form>
               </aside> -->
               <aside class="single_sidebar_widget instagram_feeds">
                  <h4 class="widget_title" style="color: #2d2d2d;">Ads</h4>
                  
               </aside>
               <aside class="single_sidebar_widget post_category_widget">
                  <h4 class="widget_title" style="color: #2d2d2d;">Category</h4>
                  <ul class="list cat-list">
                     <li>
                        <a href="#" class="d-flex">
                           <p>Resaurant food</p>
                           <p>(37)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Travel news</p>
                           <p>(10)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Modern technology</p>
                           <p>(03)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Product</p>
                           <p>(11)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Inspiration</p>
                           <p>(21)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Health Care</p>
                           <p>(21)</p>
                        </a>
                     </li>
                  </ul>
               </aside>
               @if($relatedBlogs->count() > 0)
               <aside class="single_sidebar_widget popular_post_widget">
                  <h3 class="widget_title" style="color: #2d2d2d;">Recent Post</h3>
                  @foreach($relatedBlogs as $relatedBlog)
                  <div class="media post_item">
                     <img src="{{asset('public/assets/img/post/post_1.png')}}" alt="post">
                     <div class="media-body">
                        <a href="#">
                           <h3 style="color: #2d2d2d;">From life was you fish...</h3>
                        </a>
                        <p>January 12, 2019</p>
                     </div>
                  </div>
                  @endforeach
               </aside>
               @endif
               <aside class="single_sidebar_widget tag_cloud_widget">
                  <h4 class="widget_title" style="color: #2d2d2d;">Tag</h4>
                  <ul class="list">
                     <li>
                        <a href="#">project</a>
                     </li>
                     <li>
                        <a href="#">love</a>
                     </li>
                     <li>
                        <a href="#">technology</a>
                     </li>
                     <li>
                        <a href="#">travel</a>
                     </li>
                     <li>
                        <a href="#">restaurant</a>
                     </li>
                     <li>
                        <a href="#">life style</a>
                     </li>
                     <li>
                        <a href="#">design</a>
                     </li>
                     <li>
                        <a href="#">illustration</a>
                     </li>
                  </ul>
               </aside>
            </div>
         </div>
      </div>
   </div>
</section>

@endsection