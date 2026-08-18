<link href="{{url('public/admin/assets/css/app.min.css')}}" rel="stylesheet" type="text/css">
<!-- custom Css-->
<link href="{{url('public/admin/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css">
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

<div id="layout-wrapper">
    
    
    @include('layouts.admin-header')

    
    
    <!--<div class="main-content">-->
        
        <div class="page-wrapper">
            	@include('layouts.admin-navbar')
            <div class="container-fluid page-content container-xxl">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <!-- start page title -->
                @include('layouts.breadcrumb-card', [
                    'title' => 'Update Category',
                    'icon' => 'folder',
                    'parentTitle' => 'Product Categories',
                    'parentUrl' => url('/product-category-list'),
                ])
                <!-- end page title -->
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0"></h4>
                            </div><!-- end card header -->
                            
                            <div class="card-body">
                                <form action="{{ route('product-category.update', $category->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="border mt-3 border-dashed"></div>
                                    
                                    <div class="mt-4">
                                        
                                        <div class="row">
                                        <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Name</label>
                                                
                                                    <input type="text" value="{{$category->name}}" class="form-control" id="name" name="name" >
                                                </div>
                                                @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                
                                            </div><!-- end col -->
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Image</label>
                                                    <div class="mb-2">
                                                        <img id="current-image-preview" src="{{ asset('public/images/product-category/'.$category->image) }}" alt="Current Image" style="max-width: 200px; height: auto;">
                                                    </div>
                                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                                    <input type="hidden" class="form-control" value="{{$category->image}}" id="exit_image" name="exit_image" accept="image/*">
                                                </div>
                                                @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                
                                            </div><!-- end col -->
                                            
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div><!-- end row -->
                                        
                                    </div>
                                </form><!-- end form -->
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div> <!-- container-fluid -->
        <!--</div>-->
        <!-- End Page-content -->
        
      
    </div>
    
    <script src="{{url('public/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{url('public/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{url('public/admin/assets/js/plugins.js')}}"></script>
    
    <!-- ckeditor -->
    <!-- <script src="{{url('public/admin/assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script> -->
    
    <!-- init js -->
    <!-- <script src="{{url('public/admin/assets/js/pages/form-editor.init.js')}}"></script> -->
    
    <script src="{{url('public/admin/assets/js/app.js')}}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image');
            const newImagePreview = document.getElementById('current-image-preview'); // Change the selector to replace current image
            
            imageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // Show new image preview in place of current image
                        newImagePreview.src = e.target.result;
                        newImagePreview.style.display = 'block';
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    
    @include('layouts.admin-footer')