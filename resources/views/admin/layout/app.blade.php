@extends('layouts.app')
@section('content')

<style>
    .archi-vertical-pills-container {
        display: flex;
        border: 1px solid #e0e0e0;
        padding: 20px;
        border-radius: 6px;
        font-family: Arial, sans-serif;
    }

    .archi-vertical-nav {
        display: flex;
        flex-direction: column;
        margin-right: 20px;
    }

    .archi-nav-item {
        padding: 10px 20px;
        border: none;
        background-color: transparent;
        color: #007bff;
        text-align: left;
        cursor: pointer;
        border-radius: 4px;
        margin-bottom: 5px;
        transition: background 0.3s;
    }

    .archi-nav-item:hover {
        background-color: #e6f0ff;
    }

    .archi-nav-item.archi-active {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }


</style>

<div class="slider-area2">
    <div class="slider-height2 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="hero-cap hero-cap2 pt-70 text-center">
                        <h2>Admin Home</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="about-area" style="padding:20px 0px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-12">
                <div class="archi-vertical-nav">
                    <a class="archi-nav-item archi-active" href="{{ route('admin') }}">Home</a>
                    <a class="archi-nav-item" href="{{ route('admin.projects') }}">Projects</a>
                    <a class="archi-nav-item" href="{{ route('admin.blog.index') }}">Blogs</a>
            </div>
            </div>
            <div class="col-lg-8 col-12">
                <div class="archi-tab-content">
                    @yield('admin_content')
                </div>
        </div>
    </div>
</section>



@endsection

