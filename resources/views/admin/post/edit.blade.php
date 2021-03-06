@extends('layouts.backend.app')

@section('title', 'Edit Post')

@push('css')
    <!-- Sweet Alert Css -->
    <!-- Bootstrap Select Css -->
    <link href="{{ asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />
 
@endpush

@section('content')

        <div class="container-fluid">
            <!-- Vertical Layout | With Floating Label -->
            <form method="post" action="{{ route('admin.post.update', $post->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            <div class="row clearfix">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Edit Post
                            </h2>
                            
                        </div>
                        <div class="body">
                            
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label class="title">Post Title</label>
                                        <input type="text" name="title" id="title" class="form-control" value="{{ $post->title }}">  
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <img src="{{ url('assets/backend/images/post/' . $post->image) }}" width="50px;">
                                    <input type="file" name="image" value="{{ $post->image }}">
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="publish" name="status" class="filled-in" value="1" {{ $post->status == true ? 'checked' : '' }}>
                                    <label for="publish">publish</label>

                                </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Category and Tag
                            </h2>
                            
                        </div>
                        <div class="body">
                            
                                <div class="form-group form-float">
                                    <div class="form-line {{ $errors->has('categories') ? 'focused error' : '' }}">
                                        <label for="category">Select Category</label>
                                        <select name="categories[]" id="category" class="form-control show-tick" data-live-search="true" multiple>
                                            @foreach ($categories as $category)
                                                <option 
                                                @foreach ($post->categories as $postCategory)
                                                {{ $postCategory->id == $category->id ? 'selected' : ''}}
                                                @endforeach
                                                value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line {{ $errors->has('tags') ? 'focused error' : '' }}">
                                        <label for="tag">Select Tag</label>
                                        <select name="tags[]" id="tag" class="form-control show-tick" data-live-search="true" multiple>
                                            @foreach ($tags as $tag)
                                                <option 
                                                @foreach ($post->tags as $postTag)
                                                {{ $postTag->id == $tag->id ? 'selected' : ''}}
                                                @endforeach
                                                value="{{ $tag->id }}">{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <a href="{{ route('admin.post.index') }}" class="btn btn-danger m-t-15 waves-effect"">BACK</a>
                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                BODY
                            </h2>
                            
                        </div>
                        <div class="body">
            
                            <textarea name="body" id="tinymce">{{ $post->body }} </textarea>
                                
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <!-- Vertical Layout | With Floating Label -->
            
        </div>
 



@endsection

@push('js')

<!-- Select Plugin Js -->
<script src="{{ asset('assets/backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
 <!-- TinyMCE -->
 <script src="{{ asset('assets/backend//plugins/tinymce/tinymce.js') }}"></script>
 <script>
     $(function () {
    //TinyMCE
    tinymce.init({
        selector: "textarea#tinymce",
        theme: "modern",
        height: 300,
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true
    });
    tinymce.suffix = ".min";
    tinyMCE.baseURL = '{{ asset('assets/backend/plugins/tinymce') }}';
});
 </script>
    
@endpush