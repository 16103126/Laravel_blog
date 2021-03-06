@extends('layouts.backend.app')

@section('title', 'Create Tag')

@push('css')
    <!-- Sweet Alert Css -->
 
@endpush

@section('content')

        <div class="container-fluid">
            <!-- Vertical Layout | With Floating Label -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Add New Tag
                            </h2>
                            
                        </div>
                        <div class="body">
                            <form method="post" action="{{ route('admin.tag.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="name" id="name" class="form-control">
                                        <label class="form-label">Tag Name</label>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <label for="tag_image">Tag Image</label>
                                    <div class="row">
                                    <div class="col-md-4">
                                      <input type="file" name="images[]" id="tag_image" class="form-control" multiple>
                                    </div>
                                   
                                          </div>
                                </div>

                                <a href="{{ route('admin.tag.index') }}" class="btn btn-danger m-t-15 waves-effect"">BACK</a>
                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Vertical Layout | With Floating Label -->
            
        </div>
 



@endsection

@push('js')
    
@endpush