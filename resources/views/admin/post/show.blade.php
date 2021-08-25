@extends('layouts.backend.app')

@section('title', 'Show Post')

@push('css')
    <!-- Sweet Alert Css -->
    <!-- Bootstrap Select Css -->
    <link href="{{ asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />
 
@endpush

@section('content')

        <div class="container-fluid">
            <!-- Vertical Layout | With Floating Label -->
            <a href="{{ route('admin.post.index') }}" class="btn btn-danger m-t-15 m-b-10 waves-effect"">BACK</a>
            @if ($post->is_approved == 0)
            <button type="button" class="btn btn-success pull-right waves-effect" onclick="approvePost({{ $post->id }})">
                <i class="material-icons">done</i>
                <span>Approve</span>
            </button>
            <form id="approval-form-{{ $post->id }}" action="{{ route('admin.post.approve', $post->id) }}" method="POST" style="display: none" >
                @csrf
                @method('PUT')
            </form>
            @else
            <button type="button" class="btn btn-success pull-right disabled">
                <i class="material-icons">done</i>
                <span>Approved</span>
            </button>
            @endif
           
            <div class="row clearfix">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <div class="card">
                        <div class="header">
                            <h2>
                               
                                {{ $post->title }}
                                <small>Posted By <strong><a href="">{{ $post->user->name }}</a></strong>
                                On {{ $post->created_at }}</small>
                            </h2>
                        </div>
                        <div class="body">
                            {!! $post->body !!}
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="card">
                        <div class="header bg-cyan">
                            <h2>
                                Categories
                            </h2>
                            
                        </div>
                        <div class="body">                
                                <div class="form-group form-float">
                                    @foreach ($categories as $category)
                                    <span class="label bg-cyan">{{ $category->name }}</span>
                                    @endforeach 
                                    {{-- $post-> --}}
                                    {{-- @foreach ($post->getcategories as $category)
                                    <span class="label bg-cyan">{{ $category->category->name }}</span>
                                    @endforeach --}}
                                </div>  
                        </div>
                    </div>
                    <div class="card">
                        <div class="header bg-green">
                            <h2>
                                Tags
                            </h2>
                            
                        </div>
                        <div class="body">                
                                <div class="form-group form-float">
                                    @foreach ($tags as $tag)
                                        <span class="label bg-green">{{ $tag->name }}</span>
                                    @endforeach
                                </div>  
                        </div>
                    </div>
                    <div class="card">
                        <div class="header bg-amber">
                            <h2>
                                Feature Image
                            </h2>
                            
                        </div>
                        <div class="body">                
                                <img src="{{ url('assets/backend/images/post/'.$post->image) }}" alt="" class="img-responsive thumbnail">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Vertical Layout | With Floating Label -->
            
        </div>
 



@endsection

@push('js')

<!-- Select Plugin Js -->
<script src="{{ asset('assets/backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
 <!-- TinyMCE -->
 <script src="{{ asset('assets/backend//plugins/tinymce/tinymce.js') }}"></script>
 <!--sweetalert-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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

function approvePost(id){
        const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
})

swalWithBootstrapButtons.fire({
  title: 'Are you sure?',
  text: "You want to approve this post!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, approved it!',
  cancelButtonText: 'No, cancel!',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
    event.preventDefault();
    document.getElementById('approval-form-'+id).submit();
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) {
    swalWithBootstrapButtons.fire(
      'Cancelled',
      'The post remain pending:)',
      'info'
    )
  }
})
    }
 </script>

@endpush