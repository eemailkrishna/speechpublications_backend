<!-- partial -->
@include('layouts.admin-header')

<div class="page-wrapper">


    @include('layouts.admin-navbar')
    <div class="page-content container-xxl">

        @include('layouts.breadcrumb-card', [
            'title' => 'Dashboard',
            'icon' => 'layout-dashboard',
            'homeUrl' => url('/admin-dashboard'),
        ])

        @if(Auth::user()->hasRole('admin'))

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i data-lucide="users" width="28" height="28"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-secondary mb-1 fs-13px">All Users</h6>
                            <h3 class="mb-0 fw-bold">{{$allUser}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-success-subtle text-success">
                            <i data-lucide="shopping-cart" width="28" height="28"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-secondary mb-1 fs-13px">Today Orders</h6>
                            <h3 class="mb-0 fw-bold">{{$todayBookings}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-warning-subtle text-warning">
                            <i data-lucide="calendar" width="28" height="28"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-secondary mb-1 fs-13px">Monthly Orders</h6>
                            <h3 class="mb-0 fw-bold">{{$currentMonthBookings}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- row -->

        @endif




        <div class="row">

            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">@if(Auth::user()->hasRole('admin')) New Users @else New Orders
                                @endif</h6>
                            <div class="dropdown mb-2">
                                <a type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="icon-lg text-secondary pb-3px" data-lucide="more-horizontal"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
                                    <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                            data-lucide="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                                    <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                            data-lucide="edit-2" class="icon-sm me-2"></i> <span
                                            class="">Edit</span></a>
                                    <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                            data-lucide="trash" class="icon-sm me-2"></i> <span
                                            class="">Delete</span></a>
                                    <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                            data-lucide="printer" class="icon-sm me-2"></i> <span
                                            class="">Print</span></a>
                                    <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                            data-lucide="download" class="icon-sm me-2"></i> <span
                                            class="">Download</span></a>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->hasRole('admin'))
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="pt-0">#</th>
                                        <th class="pt-0">Name</th>
                                        <th class="pt-0">Username</th>
                                        <th class="pt-0">Email</th>
                                        <th class="pt-0">Mobile</th>
                                        <th class="pt-0">Registered At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestUsers as $user)

                                    <tr>
                                        <td>
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary fw-bold"
                                                style="width:36px;height:36px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </td>
                                        <td class="fw-semibold">{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>


                                    </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        @endif


                        @if(Auth::user()->hasRole('user'))
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="pt-0">Photo</th>
                                        <th class="pt-0">Product Name</th>
                                        <th class="pt-0">Description</th>
                                        <th class="pt-0">Amount</th>
                                        <th class="pt-0">Status</th>
                                        <th class="pt-0">View All</th>
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
                                            <img src="{{ $imageUrl }}" class="img-fluid rounded"
                                                style="width:50px;height:50px;object-fit:cover;">
                                            @endif
                                        </td>

                                        {{-- Product Name --}}
                                        <td class="fw-semibold">{{ @$product->name }}</td>

                                        {{-- Product Description --}}
                                        <td style="white-space: normal;">{{ Str::limit(strip_tags(@$product->description), 50) }}</td>

                                        {{-- Price --}}
                                        <td>₹{{ number_format(@$item->price, 2) }}</td>

                                        {{-- Status --}}
                                        <td>
                                            <span
                                                class="badge {{ $order->status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                                                {{ ucfirst(@$order->status) }}
                                            </span>
                                        </td>

                                        {{-- Action --}}
                                        <td>
                                            <a href="{{ url('/order-view/'.$order->id) }}" class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>

                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> <!-- row -->

        @include('layouts.admin-footer')