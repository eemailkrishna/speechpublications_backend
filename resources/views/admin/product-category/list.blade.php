@include('layouts.admin-header')

<!-- jQuery CDN (add this BEFORE toastr.js) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Toastr CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />




<!--<div class="main-content">-->
    <div class="page-wrapper">
        	@include('layouts.admin-navbar')
    <div class="page-content container-xxl">
        <!--<div class="container-fluid">-->
            <!-- Page Title -->
              @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            @include('layouts.breadcrumb-card', [
                'title' => 'Product Categories',
                'icon' => 'folder',
                'parentUrl' => url('/product-category-list'),
            ])

            <!-- Table -->
            <div class="row mt-4">
                <div class="col-lg-12">
                   
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">Product Categories List</h4>
                            <a class="btn btn-primary btn-sm" href="{{url('/product-category-create')}}">Create</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive col-sm-12">
                                <table class="table table-striped table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="sortable" id="sortable-table">
                                        @foreach($categories as $product)
                                        <tr data-id="{{ $product->id }}">
                                            <td class="text-secondary">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ $product->name }}</td>
                                            <td><img src="{{$product->image}}" width="60" style="border-radius:8px; object-fit:cover;"></td>
                                            <td>
                                                <a href="{{ url('/product-category-edit/' . $product->id) }}" class="btn btn-sm btn-info">Edit</a>
                                                <button type="button" class="btn btn-sm btn-danger" data-delete-url="{{ url('/product-category-delete/' . $product->id) }}" data-message="Are you sure you want to delete this category?">Delete</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            <!--    </div>-->
            <!--</div>-->
        </div>
        </div>
    </div>

    <!-- Include SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const el = document.getElementById('sortable-table');
        const sortable = new Sortable(el, {
            animation: 150,
            onEnd: function () {
                let order = [];
                document.querySelectorAll('#sortable-table tr').forEach((row, index) => {
                    order.push({
                        id: row.dataset.id,
                        position: index + 1
                    });
                });

                fetch('{{ url("/update-category-order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: order })
                }).then(res => res.json())
                  .then(data => {
                    toastr.success(data.message);
                    // alert(data.message);
                });
            }
        });
    </script>

    <!-- Delete Confirmation Modal -->
    @include('layouts.delete-confirm-modal')

</div>

<style>
    .sortable-chosen {
        background-color: #f0f8ff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transform: scale(1.02);
        z-index: 1000;
    }
    .sortable-ghost {
        opacity: 0.4;
    }
    #sortable-table tr {
        cursor: grab;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@include('layouts.admin-footer')
