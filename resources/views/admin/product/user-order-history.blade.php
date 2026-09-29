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
                'title' => 'User Order History',
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
                            <table class="table table-striped table-hover mb-0" id="example-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="gridjs-th" style="width: 60px;"><div class="gridjs-th-content">#</div></th>
                                        <th class="gridjs-th" style="width: 150px;"><div class="gridjs-th-content">Name</div></th>
                                        <th class="gridjs-th" style="width: 150px;"><div class="gridjs-th-content">Username</div></th>
                                        <th class="gridjs-th" style="width: 200px;"><div class="gridjs-th-content">Email</div></th>
                                        <th class="gridjs-th" style="width: 150px;"><div class="gridjs-th-content">Registered Mobile</div></th>
                                        <th class="gridjs-th" style="width: 150px;"><div class="gridjs-th-content">Delivery Mobile</div></th>
                                        <th class="gridjs-th" style="width: 280px;"><div class="gridjs-th-content">Delivery Address</div></th>
                                        <th class="gridjs-th" style="width: 80px;"><div class="gridjs-th-content">Orders</div></th>
                                        <th class="gridjs-th" style="width: 150px;"><div class="gridjs-th-content">Registered At</div></th>
                                        <th class="gridjs-th" style="width: 130px;"><div class="gridjs-th-content">View Orders</div></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    @php $latestOrder = $user->orders->first(); @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        {{-- Product Image --}}
                                        <!-- <td>
                                            @if(!empty($images))
                                            <img src="{{ url('public/images/product/'.$images[0]) }}" class="img-fluid"
                                                style="width:80px">
                                            @endif
                                        </td> -->

                                        {{-- Product Name --}}
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number ?? '-' }}</td>
                                        <td>
                                            @if($latestOrder && $latestOrder->phone)
                                                <a href="tel:{{ $latestOrder->phone }}">{{ $latestOrder->phone }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td style="white-space: normal; min-width: 250px;">
                                            @if($latestOrder)
                                                <strong>{{ trim(($latestOrder->first_name ?? '').' '.($latestOrder->last_name ?? '')) }}</strong><br>
                                                @if($latestOrder->company){{ $latestOrder->company }}<br>@endif
                                                {{ $latestOrder->address ?? '-' }}<br>
                                                {{ $latestOrder->city ?? '' }}@if($latestOrder->city && $latestOrder->country), @endif{{ $latestOrder->country ?? '' }}<br>
                                                @if($latestOrder->email)<small>Email: {{ $latestOrder->email }}</small><br>@endif
                                                @if($latestOrder->order_notes)<small class="text-muted">Note: {{ $latestOrder->order_notes }}</small>@endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td><span class="badge bg-info">{{ $user->orders_count }}</span></td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            <a href="{{ route('order.history', ['order' => $user->id]) }}"
                                                class="btn btn-primary btn-sm">View Orders</a>
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
                paging:false,
                info:false,
                dom: 'frtip',
            });
        });
    </script>
    @include('layouts.admin-footer')