<header>
    <div class="header-area header-transparent">
        <div class="main-header header-sticky">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-xl-2 col-lg-2 col-md-1">
                        <div class="logo">
                            <a href="index-2.html"><img src="{{asset('public/assets/img/logo/logo.png')}}" alt=""></a>
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-10">
                        <div class="main-menu black-menu menu-bg-white d-none d-lg-block">
                            <!-- <div class="hamburger hamburger--collapse">
                                <div class="hamburger-box">
                                    <div class="hamburger-inner"></div>
                                </div>
                            </div> -->
                            <nav class="hamburger-men">
                                <ul id="navigation" style="text-align:end;">
                                    <li><a href="{{route('home')}}">Home</a></li>
                                    <li><a href="{{route('about')}}">About</a></li>
                                    <li><a href="{{route('services')}}">Services</a></li>
                                    <li><a href="{{route('project')}}">Project</a></li>
                                    <li>
                                        <a href="{{route('blog')}}">Blog</a>
                                        <!-- <ul class="submenu">
                                            <li><a href="{{route('blog')}}">Blog</a></li>
                                            <li><a href="{{route('blog_details')}}">Blog Details</a></li>
                                        </ul> -->
                                    </li>
                                    <li><a href="{{route('contact')}}">contact</a></li>
                                    <li><a href="{{route('admin')}}">Admin Panel</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!-- Mobile Menu -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none" style="top:-40px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>