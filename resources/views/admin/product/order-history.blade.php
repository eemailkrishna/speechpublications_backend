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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!--<div class="main-content">-->

<div class="page-wrapper">
    @include('layouts.admin-navbar')
    <div class="page-content container-xxl">

        <!-- start page title -->
        @include('layouts.breadcrumb-card', [
            'title' => 'Order History',
            'icon' => 'clipboard-list',
            'parentTitle' => 'Product',
            'parentUrl' => url('/product-list'),
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

                                                     <th data-column-id="email" class="gridjs-th" style="width: 250px;">
                                                        <div class="gridjs-th-content">Qty</div>
                                                    </th>
                                                     <th data-column-id="email" class="gridjs-th" style="width: 250px;">
                                                        <div class="gridjs-th-content">Total Amount</div>
                                                    </th>
                                                      <th data-column-id="email" class="gridjs-th" style="width: 250px;">
                                                        <div class="gridjs-th-content">Order Date</div>
                                                    </th>
                                                    <th data-column-id="action" class="gridjs-th" style="width: 200px;">
                                                        <div class="gridjs-th-content">Action</div>
                                                    </th>
                                                    <th data-column-id="company" class="gridjs-th"
                                                        style="width: 250px;">
                                                        <div class="gridjs-th-content">Status</div>
                                                    </th>

                                                    <!-- <th data-column-id="country" class="gridjs-th"
                                                        style="width: 150px;">
                                                        <div class="gridjs-th-content">Action</div>
                                                    </th> -->

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                @foreach($order->items as $item)

                                                @php
                                                $product = $item->product;
                                                $images = json_decode(@$product->image, true) ?? [];
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
                                                    <td>{{ @$product->name }}</td>

                                                    {{-- Product Description --}}
                                                    <td>{{ Str::limit(strip_tags(@$product->description), 50) }}</td>

                                                    {{-- Price --}}
                                                    <td>₹{{ number_format(@$item->price, 2) }}</td>

                                                    {{-- Quantity --}}
                                                    <td>{{ @$item->quantity }}</td>

                                                    {{-- Total Amount --}}
                                                    <td>₹{{ number_format(@$item->subtotal, 2) }}</td>

                                                    {{-- Status --}}
                                                    <td>{{ @$item->created_at->format('d M Y') }}</td>

                                                    <td>
                                                        @if(@$product->is_ebook == 1 && @$order->razorpay_payment_id!=null)
                                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#pdfModal" onclick="viewPdf('{{ route('view.ebook', ['orderId' => @$order->id, 'itemId' => @$item->id]) }}', '{{ @$product->name }}')">
                                                                <i class="mdi mdi-file-pdf"></i> View PDF
                                                            </button>
                                                        @elseif(@$order->razorpay_payment_id!=null)
                                                            <span class=" badge badge-success text-bg-success ">Paid</span>
                                                        @else
                                                            <span class="badge badge-danger text-bg-danger">Unpaid</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                       
                                                       @if(Auth::user()->hasRole('admin'))
                                                            <select class="form-select status-dropdown badge  status-badge " data-status="{{ $order->status }}" data-order-id="{{ $order->id }}" style="width: 130px;">
                                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        
                                                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                            </select>
                                                        @else
                                                            <span class="badge status-badge {{ @$order->status == 'confirmed' ? 'text-bg-success' : 'text-bg-danger' }}" data-status="{{ @$order->status }}">
                                                                {{ ucfirst(@$order->status) }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    {{-- Action --}}
                                                    <!-- <td>
                                                        <a href="{{ url('/order-view/'.$order->id) }}">View</a>
                                                    </td> -->
                                                </tr>

                                                @endforeach
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

<style>
    .status-badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 500;
        font-size: 13px;
    }
    .status-badge[data-status="pending"] {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-badge[data-status="processing"] {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    .status-badge[data-status="shipped"] {
        background-color: #cfe2ff;
        color: #084298;
    }
    .status-badge[data-status="delivered"] {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    .status-badge[data-status="cancelled"] {
        background-color: #f8d7da;
        color: #842029;
    }
</style>

<!-- PDF Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">View eBook</h5>
                <div class="btn-group me-2" role="group" aria-label="PDF actions">
                        <button type="button" class="btn btn-sm btn-secondary" id="pdfFullscreenBtn" title="Fullscreen">Fullscreen</button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 80vh; padding: 0;">
                <div id="pdfContainerAdmin" style="width:100%;height:100%;">
                        <iframe id="pdfIframe" src="" width="100%" height="100%" style="border: none;" oncontextmenu="return false;"></iframe>
                </div>
            </div>
    </div>
  </div>
</div>

<script>
function viewPdf(url, title) {
    document.getElementById('pdfModalLabel').innerText = title;
    // Add toolbar=0 to prevent download button in default viewer
    document.getElementById('pdfIframe').src = url + '#toolbar=0&navpanes=0&scrollbar=0';
}

$(document).ready(function() {
    $('#example-table').DataTable({
        paging: false,
        info: false,
        dom: 'frtip',
    });

    // AJAX Status Update
    $(document).on('change', '.status-dropdown', function() {
        var orderId = $(this).data('order-id');
        var status = $(this).val();
        var $select = $(this);

        $.ajax({
            url: '{{ route("order.update-status") }}',
            type: 'POST',
            data: {
                order_id: orderId,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success(response.message || 'Status updated successfully!', 'Success');
                $select.css('border', '2px solid green');
                setTimeout(function() {
                    $select.css('border', '');
                    location.reload();
                }, 1000);
                

            },
            error: function(xhr) {
                // Show error message
                var errorMsg = xhr.responseJSON?.message || 'Failed to update status';
                toastr.error(errorMsg, 'Error');
                // Revert the select to previous value
                location.reload();
            }
        });
    });
});
</script>
<script>
// Fullscreen handler for admin PDF modal
document.addEventListener('DOMContentLoaded', function() {
    var pdfFullscreenBtn = document.getElementById('pdfFullscreenBtn');
    var pdfContainer = document.getElementById('pdfContainerAdmin');
    var pdfIframe = document.getElementById('pdfIframe');

    if (pdfFullscreenBtn) {
        pdfFullscreenBtn.addEventListener('click', function() {
            var el = pdfContainer || pdfIframe;
            if (!el) return;

            if (!document.fullscreenElement) {
                if (el.requestFullscreen) el.requestFullscreen();
                else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
                else if (el.msRequestFullscreen) el.msRequestFullscreen();
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
                else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
                else if (document.msExitFullscreen) document.msExitFullscreen();
            }
        });
    }
});
</script>
@include('layouts.admin-footer')