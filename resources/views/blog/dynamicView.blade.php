@section('page_title', 'How to Teach Online: A Complete Guide for Beginners')
@section('page_desc', 'Learn how to teach online with this complete guide for beginners. Discover tips, tools, and steps to start your online teaching journey on SchoolBuddy, the trusted platform to connect with students, create your profile, and find tutoring jobs near you.')
@section('page_img', 'https://www.schoolbuddy.io/frontend/assets/img/Female Teacher Demonstrating How to Teach Online.webp')
@section('page_secure_img', 'https://www.schoolbuddy.io/frontend/assets/img/Female Teacher Demonstrating How to Teach Online.webp')
@section('blog_content')
<style>
    html {
        scroll-behavior: smooth; 
        scroll-padding-top: 80px;
    }
</style>
<div>
    <div class="row blog-detail" style="row-gap: 30px;">
        <div class="col-lg-3 position-relative">
            <div class="table-of-contents position-sticky" style="top:80px;">
                <h5>Table of Contents</h5>
                <ul>
                    <li><strong>How to Teach Online</strong></li>
                    <li><strong>Why Teach Online?</strong>
                    <ul>
                        <li>Decide What to Teach</li>
                        <li>Choose the Right Online Platform</li>
                        <li>Create an Engaging Teacher Profile</li>
                        <li>Prepare Your Teaching Tools</li>
                        <li>Start Teaching</li>
                    </ul>
                    </li>
                    <li><strong>Tips for Successful Online Teaching</strong></li>
                    <li><strong>Frequently Asked Questions</strong></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="blog-detail-section">
                <div>
                    <p class="blog-time">Updated Nov 28, 2024 |  2 min read</p>
                </div>
                {!! $blog->content !! ?? "" }
            </div>
        </div>
        <div class="col-lg-3 position-relative">
            <div class="position-sticky" style="top:80px;">
                <div class="top-posts">
                    <h5>Top Posts</h5>
                    <ol>
                        <li>How to Teach Online</li>
                        <li>Online Tutoring Jobs for Beginners</li>
                    </ol>
                </div>
                <div class="share-section">
                    <h6>Share On</h6>
                    <div class="share-icons">
                        <a href="#" class="share-facebook" rel="me" title="Facebook" target="_blank"><i class="fab fa-facebook-f" style="color: #3b5998;"></i></a>
                        <a href="#" class="share-twitter" target="_blank"><i class="fab fa-twitter" style="color: #1da1f2;"></i></a>
                        <a href="#" class="share-linkedin" target="_blank"><i class="fab fa-linkedin-in" style="color: #0077b5;"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-5 rounded shadow-sm start-teaching-section" style="background-color:#5F4BE0;">
            <div class="d-flex align-items-center justify-content-between p-5" style="width:90%;margin:auto; background-image: url('../frontend/assets/img/Mask group.webp'); background-size: auto;background-position:right;background-repeat:no-repeat;">
                <div>
                    <div>
                        <h4>
                            Start Teaching Online <span style="color: #f39c12;">Today!</span>
                        </h4>
                    </div>
                    <div class="teaching-section-btn-group">
                        <a href="#" class="theme-btn me-3">Sign Up</a>
                        <a href="#" class="theme-btn">Search Jobs</a>
                    </div>
                </div>    
            </div>
        </div>
    </div>
  

