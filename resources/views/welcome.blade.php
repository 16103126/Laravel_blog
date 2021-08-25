@extends('layouts.frontend.app')

@section('title', 'Home')

@push('css')

<link href="{{ asset('assets/frontend/css/home/styles.css') }}" rel="stylesheet">

<link href="{{ asset('assets/frontend/css/home/responsive.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    .favourite_posts{
        color: blue;
    }
</style>

@endpush

@section('content')

<div class="main-slider">
    <div class="swiper-container position-static" data-slide-effect="slide" data-autoheight="false"
        data-swiper-speed="500" data-swiper-autoplay="10000" data-swiper-margin="0" data-swiper-slides-per-view="4"
        data-swiper-breakpoints="true" data-swiper-loop="true" >
        <div class="swiper-wrapper">

            @foreach ($categories as $category)
            <div class="swiper-slide">
                <a class="slider-category" href="{{ route('category.post', $category->slug) }}">
                    <div class="blog-image"><img src="{{ url('assets/backend/images/slider/' .$category->image) }}" alt="{{ $category->name }}"></div>

                    <div class="category">
                        <div class="display-table center-text">
                            <div class="display-table-cell">
                                <h3><b>{{ $category->name }}</b></h3>
                            </div>
                        </div>
                    </div>

                </a>
            </div><!-- swiper-slide -->
            @endforeach

        </div><!-- swiper-wrapper -->

    </div><!-- swiper-container -->

</div><!-- slider -->

<section class="blog-area section">
    <div class="container">

        <div class="row">
            @foreach ($posts as $post)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="single-post post-style-1">

                        <div class="blog-image"><img src="{{ url('assets/backend/images/post/'.$post->image) }}" alt="{{ $post->title }}"></div>

                        {{-- <a class="avatar" href="{{ route('post.details', $post->slug) }}"><img src="{{ url('assets/backend/images/profile/'.$post->user->image) }}" alt="{{ $post->user->name }}"></a> --}}

                        <a class="avatar" href="{{ route('author.profile', $post->user->username) }}"><img src="{{ url('assets/backend/images/profile/'.$post->user->image) }}" alt="{{ $post->user->name }}"></a>

                        <div class="blog-info">

                            <h4 class="title"><a href="{{ route('post.details', $post->slug) }}"><b>{{ $post->title }}</b></a></h4>

                            <ul class="post-footer">

                                @guest
                                    <li>
                                        <a href="javascript:void(0);" onclick="toastr:info('To add favourite list. You need to login first.', 'info',{
                                        closeButton: true,
                                        progressBar:true,
                                        })">
                                        <i class="material-icons">favorite</i>{{ $post->favourite_to_users->count() }}</a>
                                    </li>
                                @else
                                <a href="javascript:void(0);" onclick="document.getElementById('favourite-form-{{ $post->id }}').submit();" class="{{ !Auth::user()->favourite_posts->where('pivot.post_id', $post->id)->count() == 0 ? 'favourite_posts' : '' }}"> <i class="ion-heart"></i>{{ $post->favourite_to_users->count() }}</a>
                                <form id="favourite-form-{{ $post->id }}" method="POST" action="{{ route('post.favourite', $post->id) }}" style="display: none;">
                                    @csrf
                                </form>
                                @endguest

                                <li><a href="#"><i class="ion-chatbubble"></i>{{$post->comments->count()}}</a></li>
                                <li><a href="#"><i class="ion-eye"></i>{{ $post->view_count }}</a></li>
                            </ul>

                        </div><!-- blog-info -->
                    </div><!-- single-post -->
                </div><!-- card -->
            </div><!-- col-lg-4 col-md-6 -->
            @endforeach

        </div><!-- row -->

    </div><!-- container -->
</section><!-- section -->

@endsection

@push('js')

<script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>

@endpush