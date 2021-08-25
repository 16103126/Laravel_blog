@extends('layouts.frontend.app')

@section('title', 'Single Post')
{{ $post->title }}

@push('css')

<link href="{{ asset('assets/frontend/css/single-post/styles.css') }}" rel="stylesheet">
<link href="{{ asset('assets/frontend/css/single-post/responsive.css') }}" rel="stylesheet">
<style>
    /* .header-bg{
     
        background-image: url('{{ url('assets/backend/images/post/'.$post->image) }}');
        background-size: cover;
    } */
    .slider{
        height: 400px;
        width: 100%;
        background-image: url('{{ url('assets/backend/images/post/'.$post->image) }}');
        background-size: cover;
    }
    .favourite_posts{
        color: blue;
    }
</style>

@endpush

@section('content')

<div class="slider header-bg">
    <div class="display-table  center-text">
        <h1 class="title display-table-cell"><b>DESIGN</b></h1>
    </div>
</div><!-- slider -->

<section class="post-area section">
    <div class="container">

        <div class="row">

            <div class="col-lg-8 col-md-12 no-right-padding">

                <div class="main-post">

                    <div class="blog-post-inner">

                        <div class="post-info">

                            <div class="left-area">
                                <a class="avatar" href="#"><img src="{{ url('assets/backend/images/profile/'.$post->user->image) }}" alt="Profile Image"></a>
                            </div>

                            <div class="middle-area">
                                <a class="name" href="#"><b>{{ $post->user->name }}</b></a>
                                <h6 class="date">{{ $post->created_at->diffForHumans() }}</h6>
                            </div>

                        </div><!-- post-info -->

                        <h3 class="title"><a href="#"><b>{{ $post->title }}</b></a></h3>

                        <div class="para">
                            {!! html_entity_decode($post->body) !!}
                        </div>
                        <h4 class="title"><b>TAG</b></h4>

                        <ul class="tags">
                            @foreach ($post->tags as $tag)
                            <li><a href="{{ route('tag.post', $tag->slug) }}">{{ $tag->name }}</a></li>
                            @endforeach
                        </ul>
                    </div><!-- blog-post-inner -->

                    <div class="post-icons-area">
                        <ul class="post-icons">
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

                                
                                <li><a href="#"><i class="ion-chatbubble"></i>6</a></li>
                                <li><a href="#"><i class="ion-eye"></i>{{ $post->view_count }}</a></li>
                        </ul>

                        <ul class="icons">
                            <li>SHARE : </li>
                            <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                            <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                            <li><a href="#"><i class="ion-social-pinterest"></i></a></li>
                        </ul>
                    </div>


                </div><!-- main-post -->
            </div><!-- col-lg-8 col-md-12 -->

            <div class="col-lg-4 col-md-12 no-left-padding">

                <div class="single-post info-area">

                    <div class="sidebar-area about-area">
                        <h4 class="title"><b>ABOUT AUTHOR</b></h4>
                        <p>{{ $post->user->about }}</p>
                    </div>

                    <div class="tag-area">

                        <h4 class="title"><b>CATEGORY</b></h4>
                        <ul>
                           @foreach ($post->categories as $category)
                           <li><a href="{{ route('category.post', $category->slug) }}">{{ $category->name }}</a></li>
                           @endforeach
                        </ul>

                    </div><!-- subscribe-area -->

                </div><!-- info-area -->

            </div><!-- col-lg-4 col-md-12 -->

        </div><!-- row -->

    </div><!-- container -->
</section><!-- post-area -->


<section class="recomended-area section">
    <div class="container">
        <div class="row">

            @foreach ($randomposts as $randompost)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="single-post post-style-1">

                        <div class="blog-image"><img src="{{ url('assets/backend/images/post/'.$randompost->image) }}" alt="{{ $randompost->title }}"></div>

                        <a class="avatar" href="{{ route('post.details', $randompost->slug) }}"><img src="{{ url('assets/backend/images/profile/'.$randompost->user->image) }}" alt="{{ $randompost->user->name }}"></a>

                        <div class="blog-info">

                            <h4 class="title"><a href="{{ route('post.details', $randompost->slug) }}"><b>{{ $randompost->title }}</b></a></h4>

                            <ul class="post-footer">

                                @guest
                                    <li>
                                        <a href="javascript:void(0);" onclick="toastr:info('To add favourite list. You need to login first.', 'info',{
                                        closeButton: true,
                                        progressBar:true,
                                        })">
                                        <i class="material-icons">favorite</i>{{ $randompost->favourite_to_users->count() }}</a>
                                    </li>
                                @else
                                <a href="javascript:void(0);" onclick="document.getElementById('favourite-form-{{ $randompost->id }}').submit();" class="{{ !Auth::user()->favourite_posts->where('pivot.post_id', $randompost->id)->count() == 0 ? 'favourite_posts' : '' }}"> <i class="ion-heart"></i>{{ $randompost->favourite_to_users->count() }}</a>
                                <form id="favourite-form-{{ $randompost->id }}" method="POST" action="{{ route('post.favourite', $randompost->id) }}" style="display: none;">
                                    @csrf
                                </form>
                                @endguest

                                
                                <li><a href="#"><i class="ion-chatbubble"></i>{{ $randompost->comments->count() }}</a></li>
                                <li><a href="#"><i class="ion-eye"></i>{{ $randompost->view_count }}</a></li>
                            </ul>

                        </div><!-- blog-info -->
                    </div><!-- single-post -->
                </div><!-- card -->
            </div><!-- col-lg-4 col-md-6 -->
            @endforeach

        </div><!-- row -->

    </div><!-- container -->
</section>

<section class="comment-section">
    <div class="container">
        <h4><b>POST COMMENT</b></h4>
        <div class="row">

            <div class="col-lg-8 col-md-12">
                <div class="comment-form">
                    @guest
                    <p> For post a new comment. You need to login first. <a href="{{route ('login')}}">Login</a> </p>
                    @else
                    <form method="post" action="{{ route('comment.store', $post->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea name="comment" rows="2" class="text-area-messge form-control"
                                    placeholder="Enter your comment" aria-required="true" aria-invalid="false"></textarea >
                            </div><!-- col-sm-12 -->
                            <div class="col-sm-12">
                                <button class="submit-btn" type="submit" id="form-submit"><b>POST COMMENT</b></button>
                            </div><!-- col-sm-12 -->
                        </div><!-- row -->
                    </form>
                    @endguest
                </div><!-- comment-form -->
            </div>


                <h4><b>COMMENTS ({{ $post->comments->count() }})</b></h4>
                
                @if ($post->comments->count()>0)
                @foreach($post->comments as $comment)
                <div class="col-lg-8 col-md-12">
                <div class="commnets-area">
                    <div class="comment">

                        <div class="post-info">

                            <div class="left-area">
                                <a class="avatar" href="#"><img src="{{ url('assets/backend/images/profile/'.$comment->user->image) }}" alt="Profile Image"></a>
                            </div>

                            <div class="middle-area">
                                <a class="name" href="#"><b>{{ $comment->user->name }}</b></a>
                                <h6 class="date">{{$comment->created_at->diffForHumans()}}</h6>
                                <p>{{ $comment->comment }}</p> 
                            </div>
                            
                        </div><!-- post-info -->

                    </div>
                </div><!-- commnets-area -->
                </div>
                @endforeach
                @else
                <p>No Comment yet. Be the first</p>
                @endif            
            </div><!-- col-lg-8 col-md-12 -->
            
        </div>      

        </div><!-- row -->

    </div><!-- container -->
</section>



@endsection

@push('js')



@endpush