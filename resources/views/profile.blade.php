@extends('layouts.frontend.app')

@section('title', 'Profile')



@push('css')

<link href="{{ asset('assets/frontend/css/profile/styles.css') }}" rel="stylesheet">
<link href="{{ asset('assets/frontend/css/profile/responsive.css') }}" rel="stylesheet">

<style>
    .slider{
        height: 400px;
        width: 100%;
        background-image: url('{{ url('images/category-3-400x250.jpg') }}');
        background-size: cover;
    }
    .favourite_posts{
        color: blue;
    }
</style>

@endpush

@section('content')


<div class="slider display-table center-text">
    <h1 class="title display-table-cell"><b>{{ $author->name }}</b></h1>
</div><!-- slider -->

<section class="blog-area section">
    <div class="container">

        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="row">
            @if ($posts->count() > 0)
            @foreach ($posts as $post)
            <div class="col-md-6 col-sm-12">
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

            @else
            <div class="col-md-6 col-sm-12">
                <div class="card h-100">
                    <div class="single-post post-style-1">
                        <div class="blog-info">
                            <h4 class="title">
                                <strong>Sorry, No post found.</strong>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            @endif
                </div>
            </div>

            <div class="col-lg-4 col-md-12 ">

                <div class="single-post info-area ">

                    <div class="about-area">
                        <h4 class="title"><b>ABOUT PROFILE</b></h4>
                        <p>{{ $author->name }}</p><br>
                        <p>{{ $author->about }}</p><br>
                        {{-- <strong>Author Since: {{ $author->created_at->toDateString() }}</strong><br> --}}
                        <strong>Total Posts: {{ $author->posts->count() }}</strong>
                    </div>

                   

                </div><!-- info-area -->

            </div><!-- col-lg-4 col-md-12 -->

        </div><!-- row -->

    </div><!-- container -->
</section><!-- section -->

@endsection

@push('js')



@endpush