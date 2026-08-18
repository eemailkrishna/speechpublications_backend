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
                    'title' => 'Create Product Category',
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
                                <form action="{{ route('product-category.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                   
                                    
                                    <div class="border mt-3 border-dashed"></div>
                                    
                                    <div class="mt-4">
                                        
                                        <div class="row">
                                        <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="cleave-ccard" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" placeholder="name">
                                                </div>
                                                @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="cleave-ccard" class="form-label">Image</label>
                                                    <input type="file" class="form-control"type="file" id="image" name="image" placeholder="Image">
                                                </div>
                                                @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                
                                            </div>
                                            
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
            </div> <!-- container-fluid 
        </div>
        <!-- End Page-content -->
        
        
        
    </div>
    
    @include('layouts.admin-footer')
    