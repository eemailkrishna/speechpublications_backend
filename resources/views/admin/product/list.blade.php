@include('layouts.admin-header')

<style>
    .toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .3s; border-radius: 24px; }
    .toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
    input:checked + .toggle-slider { background-color: #7c6ff5; }
    input:checked + .toggle-slider:before { transform: translateX(20px); }
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

<!-- Modal for Description -->
<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">Product Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalDescription">
                <div class="row">
                    <div class="col-md-4">
                        <img id="modalImage" src="" alt="Product Image" class="img-fluid" style="max-width: 100%; border-radius: 8px;">
                    </div>
                    <div class="col-md-8">
                        <h6><strong>Product Name:</strong></h6>
                        <p id="modalName"></p>
                        
                        <h6><strong>Price:</strong></h6>
                        <p id="modalPrice"></p>
                        
                        <h6><strong>Status:</strong></h6>
                        <p id="modalStatus"></p>
                        
                        <h6><strong>Description:</strong></h6>
                        <p id="modalDescriptionText"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!--<div class="main-content">-->
    
    <div class="page-wrapper">
        	@include('layouts.admin-navbar')
     <div class="page-content container-xxl">
            
            <!-- start page title -->
            @include('layouts.breadcrumb-card', [
                'title' => 'Product',
                'icon' => 'package',
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
                                                    <th data-column-id="name" class="gridjs-th" style="width: 150px;">
                                                            <div class="gridjs-th-content">Image</div>
                                                        </th>
                                                        <th data-column-id="name" class="gridjs-th" style="width: 350px;">
                                                            <div class="gridjs-th-content">Name</div>
                                                        </th>
                                                        <th data-column-id="email" class="gridjs-th" style="width: 450px;">
                                                            <div class="gridjs-th-content">Description</div>
                                                        </th>
                                                        <th data-column-id="position" class="gridjs-th" style="width: 100px;">
                                                             <div class="gridjs-th-content">Price</div>
                                                        </th>
                                                      
                                                        <th data-column-id="company" class="gridjs-th" style="width: 100px;">
                                                             <div class="gridjs-th-content">Status</div>
                                                        </th>

                                                        <th data-column-id="popular" class="gridjs-th" style="width: 80px;">
                                                             <div class="gridjs-th-content">Popular</div>
                                                        </th>
                                                        
                                                        <th data-column-id="country" class="gridjs-th"style="width: 150px;">
                                                             <div class="gridjs-th-content">Action</div>
                                                        </th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody class="">
                                                    @foreach($products as $product)
                                                    <tr class="gridjs-tr">
                                                     @php
                                                    $images = json_decode($product->image, true) ?? [];
                                                    $imageUrl = !empty($images) && isset($images[0])
                                                        ? Storage::disk('s3')->url('product/'.$images[0])
                                                        : asset('images/no-image.png');
                                                    
                                                    // Truncate name to 10 words
                                                    $nameWords = explode(' ', $product->name);
                                                    $truncatedName = implode(' ', array_slice($nameWords, 0, 10));
                                                    
                                                    // Remove HTML tags and truncate description to 10 words
                                                    $cleanDescription = strip_tags($product->description);
                                                    $words = explode(' ', $cleanDescription);
                                                    $truncated = implode(' ', array_slice($words, 0, 15));
                                                    $showMore = count($words) > 15;
                                                @endphp
                                                    <td class="gridjs-td"><img class="img-fluid" src="{{ $imageUrl }}"></td>

                                                        <td class="gridjs-td" style="white-space: normal;">{{$truncatedName}}</td>
                                                        <td style="white-space: normal;" class="gridjs-td">
                                                            {{ $truncated }}
                                                        </td>
                                                        <td class="gridjs-td">{{$product->price}}</td>
                                                    
                                                       <td class="gridjs-td">
                                                            <span class="badge {{ $product->status == 'launched' ? 'bg-success' : 'bg-danger' }}">
                                                                {{ ucfirst($product->status) }}
                                                            </span>
                                                        </td>

                                                        <td class="gridjs-td">
                                                            <form action="{{ url('/product/' . $product->id . '/toggle-popular') }}" method="POST">
                                                                @csrf
                                                                <label class="toggle-switch" style="cursor:pointer;">
                                                                    <input type="checkbox" {{ $product->is_popular ? 'checked' : '' }} onchange="this.form.submit()">
                                                                    <span class="toggle-slider"></span>
                                                                </label>
                                                            </form>
                                                        </td>

                                                        <td class="gridjs-td">
                                                        @if($showMore)
                                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#descriptionModal" 
                                                                    data-description="{{ strip_tags($product->description) }}"
                                                                    data-name="{{ $product->name }}"
                                                                    data-price="{{ $product->price }}"
                                                                    data-status="{{ ucfirst($product->status) }}"
                                                                    data-image="{{ $imageUrl }}"
                                                                    style="margin: 0 5px;">
                                                                    View
                                                                </button>
                                                            @endif    
                                                        <a href="{{ url('/product-edit/' . $product->id) }}" class="btn btn-sm btn-info" style="margin: 0 5px;">Edit</a>
                                                            
                                                            <button type="button" class="btn btn-sm btn-danger" style="margin: 0 5px;" data-delete-url="{{ url('/product-delete/' . $product->id) }}" data-message="Are you sure you want to delete this product?">Delete</button>
                                                        </td>
                                                       
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="gridjs-footer ">
                                            <span class="text-danger pl-5">
                                                Showing {{ @$products->firstItem() }} to {{ @$products->lastItem() }} of {{ @$products->total() }} entries
                                            </span>
                                            {{@$products->links()}}
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
    
    <script>
        $(document).ready(function() {
            // Handle modal show event
            $('#descriptionModal').on('show.bs.modal', function (e) {
                var button = $(e.relatedTarget);
                var description = button.data('description');
                var name = button.data('name');
                var price = button.data('price');
                var status = button.data('status');
                var image = button.data('image');
                
                // Populate modal fields
                document.getElementById('modalDescriptionText').textContent = description;
                document.getElementById('modalName').textContent = name;
                document.getElementById('modalPrice').textContent = price;
                
                // Set status with badge styling
                var statusBadge = status === 'Launched' 
                    ? '<span class="badge bg-success">' + status + '</span>' 
                    : '<span class="badge bg-danger">' + status + '</span>';
                document.getElementById('modalStatus').innerHTML = statusBadge;
                
                // Set image
                document.getElementById('modalImage').src = image;
            });

            $('#example-table').DataTable({
                paging:false,
                info:false,
                dom: 'frtip',
            });
        });
    </script>
    @include('layouts.delete-confirm-modal')
    @include('layouts.admin-footer')