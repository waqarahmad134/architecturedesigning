<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- <meta http-equiv="Permissions-Policy" content="fullscreen=(self)" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
    <meta name="theme-color" content="#ffffff">
    <title>@yield('page_title' , 'Architecture Designing | Free Download') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="@yield('page_desc', 'Watch and download the latest and classic Indian movies in HD quality on videosroom.com. New releases all available for online streaming and download.')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())" />

    <!-- <link rel="icon" href="{{ asset('public/public/images/favicon.ico') }}" type="image/x-icon" />
    <link rel="icon" href="{{ asset('public/public/images/favicon-16x16.png') }}" type="image/png" sizes="16x16" />
    <link rel="icon" href="{{ asset('public/public/images/favicon-32x32.png') }}" type="image/png" sizes="32x32" />
    <link rel="icon" href="{{ asset('public/public/images/favicon-48x48.png') }}" type="image/png" sizes="48x48" />
    <link rel="icon" href="{{ asset('public/public/images/favicon-64x64.png') }}" type="image/png" sizes="64x64" />
    <link rel="shortcut icon" href="{{ asset('public/public/images/favicon.ico') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('public/public/images/apple-touch-icon.png') }}" sizes="180x180" /> -->

    <meta name="author" content="videosroom" />
    <meta name="application-name" content="videosroom" />
    <meta name="google-site-verification" content="9ymmNlx5EDLQBPyn2hi1cYHFHV41ubr_QBHe-T-jydw" />
    <!-- <meta name="keywords" content="Movies, Films, Cinema, Hollywood, Bollywood, Action Movies, Comedy Movies, Drama Movies, Thriller Movies, Romance Movies, Sci-Fi Movies, Fantasy Movies, Family Movies, Animated Movies, Documentary Movies" /> -->
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="videosroom" />
    <meta property="og:title" content="videosroom - Discover High-Quality Movies" />
    <meta property="og:url" content="https://videosroom.com" />
    <meta property="og:description" content="Explore our collection of high-quality movies across genres." />
    <!-- <meta property="og:image" content="https://videosroom.com/logo.png" /> -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://videosroom.com" />
    <meta property="twitter:title" content="Your Movie Site - Discover High-Quality Movies" />
    <meta property="twitter:description" content="Explore our collection of high-quality movies across genres." />
    <!-- <meta property="twitter:image" content="https://videosroom.com/logo.png" /> -->
    
    <!-- <script src="schema.js" defer></script> -->


    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/hamburgers.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/gijgo.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/animated-headline.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/magnific-popup.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/fontawesome-all.min.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/themify-icons.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/slick.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/nice-select.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/css/style.css') }}">

    

    
    
    <!-- <link href="{{ asset('public/app.css') }}?v={{ time() }}" rel="stylesheet"> -->


    <style>
        .btn-theme{
                all:unset;
                background: #ff1313;
                font-family: "Rajdhani",sans-serif;
                text-transform: uppercase;
                padding: 8px 15px;
                color: #fff;
                cursor: pointer;
                display: inline-block;
                font-size: 15px;
                font-weight: 400;
                border-radius: 0px;
                cursor: pointer;
            }
    </style>
</head>

<body class="max-w-screen-2xl m-auto">
    @include('layouts.preloader')
    @include('layouts.header')
    <main>
        @yield('content')
    </main>

    @include('layouts.footer')
    @include('layouts.scroll-to-top-button')
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        @if(Session::has('message'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.success("{{ session('message') }}");
        @endif

        @if(Session::has('error'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.error("{{ session('error') }}");
        @endif

        @if(Session::has('info'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.info("{{ session('info') }}");
        @endif

        @if(Session::has('warning'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.warning("{{ session('warning') }}");
        @endif
    </script>

    <!-- JS here -->
    <script src="{{ asset('public/assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="{{ asset('public/assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
    <!-- Jquery Mobile Menu -->
    <script src="{{ asset('public/assets/js/jquery.slicknav.min.js') }}"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="{{ asset('public/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/slick.min.js') }}"></script>
    <!-- One Page, Animated-HeadLin -->
    <script src="{{ asset('public/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/animated.headline.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.magnific-popup.js') }}"></script>

    <!-- Date Picker -->
    <!-- <script src="{{ asset('public/assets/js/gijgo.min.js') }}"></script> -->
    <!-- Nice-select, sticky -->
    <!-- <script src="{{ asset('public/assets/js/jquery.nice-select.min.js') }}"></script> -->
    <!-- <script src="{{ asset('public/assets/js/jquery.sticky.js') }}"></script> -->
    
    <!-- counter , waypoint,Hover Direction -->
    <!-- <script src="{{ asset('public/assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/hover-direction-snake.min.js') }}"></script> -->

    <!-- contact js -->
    <!-- <script src="{{ asset('public/assets/js/contact.js') }}"></script> -->
    <!-- <script src="{{ asset('public/assets/js/jquery.form.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.ajaxchimp.min.js') }}"></script> -->
    
    <!-- Jquery Plugins, main Jquery -->	
    <script src="{{ asset('public/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('public/assets/js/main.js') }}"></script>
    
    <!-- <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"9511b46f4e50fd31","version":"2025.6.2","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"cd0b4b3a733644fc843ef0b185f98241","b":1}' crossorigin="anonymous"></script> -->
</body>

</html>