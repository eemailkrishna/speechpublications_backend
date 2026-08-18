@include('layouts.admin-header')

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

<!--<div class="main-content">-->

<div class="page-wrapper">
    @include('layouts.admin-navbar')
    <div class="page-content container-xxl">

        <!-- start page title -->
        @include('layouts.breadcrumb-card', [
            'title' => 'Order View',
            'icon' => 'eye',
            'parentTitle' => 'Order History',
            'parentUrl' => url('/order-history'),
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
                                        <table class="table table-striped table-hover" id="example-table"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-column-id="name" class="gridjs-th" style="width: 150px;">
                                                        <div class="gridjs-th-content">Image</div>
                                                    </th>
                                                    <th data-column-id="name" class="gridjs-th" style="width: 150px;">
                                                        <div class="gridjs-th-content">Name</div>
                                                    </th>
                                                    <th data-column-id="email" class="gridjs-th" style="width: 250px;">
                                                        <div class="gridjs-th-content">Description</div>
                                                    </th>
                                                    <th data-column-id="position" class="gridjs-th"
                                                        style="width: 250px;">
                                                        <div class="gridjs-th-content">Price</div>
                                                    </th>

                                                     <th data-column-id="position" class="gridjs-th"
                                                        style="width: 250px;">
                                                        <div class="gridjs-th-content">Qty</div>
                                                    </th>

                                                     <th data-column-id="position" class="gridjs-th"
                                                        style="width: 250px;">
                                                        <div class="gridjs-th-content">Total Amount</div>
                                                    </th>

                                                     <th data-column-id="position" class="gridjs-th"
                                                        style="width: 250px;">
                                                        <div class="gridjs-th-content">Order Date</div>
                                                    </th>

                                                    <th data-column-id="company" class="gridjs-th"
                                                        style="width: 250px;">
                                                        <div class="gridjs-th-content">Status</div>
                                                    </th>

                                                   

                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                                @foreach($order->items as $item)

                                                @php
                                                $product = $item->product;
                                                 $images = json_decode($product->image, true) ?? [];
                                                    $imageUrl = !empty($images) && isset($images[0])
                                                        ? Storage::disk('s3')->url('product/'.$images[0])
                                                        : asset('images/no-image.png'); // fallback image

                                                @endphp

                                                <tr>
                                                    {{-- Product Image --}}
    
                                                    <td>
                                                        @if(!empty($images))
                                                        <img src="{{ $imageUrl }}"
                                                            class="img-fluid" style="width:50px;height:50px;" alt="Product Image">
                                                        @endif
                                                    </td>

                                                    {{-- Product Name --}}
                                                    <td>{{ $product->name }}</td>

                                                    {{-- Product Description --}}
                                                    <td>{{ Str::limit(strip_tags($product->description), 50) }}</td>
                                                    {{-- Price --}}
                                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                                    <td>{{ number_format($item->quantity) }}</td>
                                                    <td>₹{{ number_format($item->subtotal, 2) }}</td>
                                                    <td>{{ $item->created_at->format('d M Y') }}</td>





                                                    {{-- Status --}}
                                                    <td>
                                                        <span
                                                            class="badge {{ $order->status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>

                                                    
                                                </tr>

                                                @endforeach
                                               
                                            </tbody>

                                        </table>
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
    $('#example-table').DataTable({
        paging: false,
        info: false,
        dom: 'frtip',
    });
});
</script>
@include('layouts.admin-footer')