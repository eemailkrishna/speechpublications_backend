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

<div class="page-wrapper">
    @include('layouts.admin-navbar')
    <div class="page-content container-xxl">

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                @include('layouts.breadcrumb-card', [
                    'title' => 'News Categories',
                    'icon' => 'folder',
                    'parentUrl' => route('admin-news-category.index'),
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">All Categories</h4>
                        <a href="{{ route('admin-news-category.create') }}" class="btn btn-primary btn-sm">Add Category</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive col-sm-12">
                            <table class="table table-striped table-hover" id="category-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>News Count</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>{{ $category->news_count ?? $category->news()->count() }}</td>
                                        <td>
                                            <span class="badge {{ $category->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($category->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin-news-category.edit', $category->id) }}" class="btn btn-sm btn-info" style="margin: 0 5px;">Edit</a>
                                            <form id="delete-category-{{ $category->id }}" action="{{ route('admin-news-category.destroy', $category->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" style="margin: 0 5px;" data-delete-form="delete-category-{{ $category->id }}" data-message="Are you sure you want to delete this category?">Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="gridjs-footer">
                            <span class="text-danger">
                                Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} entries
                            </span>
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#category-table').DataTable({
            paging: false,
            info: false,
            dom: 'frtip',
        });
    });
</script>

@include('layouts.delete-confirm-modal')

@include('layouts.admin-footer')
