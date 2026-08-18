@include('layouts.admin-header')
<div class="main-content">
    
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- start page title -->
            @include('layouts.breadcrumb-card', [
                'title' => 'Banner',
                'icon' => 'image',
            ])
            <!-- end page title -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="table-search">
                                <div role="complementary" class="gridjs gridjs-container" style="width: 100%;">
                                    <div class="gridjs-wrapper" style="height: auto;">
                                        <div class="table-responsive col-sm-12">
                                            <table class="table table-striped table-hover" id="example-table" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th data-column-id="country" class="gridjs-th" style="width: 150px;">
                                                            <div class="gridjs-th-content">Image</div>
                                                        </th>
                                                        <th data-column-id="country" class="gridjs-th" style="width: 150px;">
                                                            <div class="gridjs-th-content">Action</div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    @foreach($banners as $banner)
                                                    <tr class="gridjs-tr">
                                                        <td data-column-id="country" class="gridjs-td">
                                                            <img src="{{ asset('public/images/banner/'.$banner->image) }}" alt="Banner Image" style="max-width: 100px; height: auto;">
                                                        </td>
                                                        
                                                        <td data-column-id="country" class="gridjs-td">
                                                            <a href="{{ url('/banner-edit/' . $banner->id) }}">Edit</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="gridjs-footer ">
                                            <span class="text-danger pl-5">
                                                Showing {{ @$banners->firstItem() }} to {{ @$banners->lastItem() }} of {{ @$banners->total() }} entries
                                            </span>
                                            {{@$banners->links()}}
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                
                <!-- end row -->
                
                
                <!-- end row -->
                
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        
        
    </div>
    @include('layouts.admin-footer')