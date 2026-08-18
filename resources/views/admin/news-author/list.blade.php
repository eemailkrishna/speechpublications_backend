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
                    'title' => 'News Authors',
                    'icon' => 'user',
                    'parentUrl' => route('admin-news-author.index'),
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">All Authors</h4>
                        <a href="{{ route('admin-news-author.create') }}" class="btn btn-primary btn-sm">Add Author</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive col-sm-12">
                            <table class="table table-striped table-hover" id="author-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Designation</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($authors as $author)
                                    <tr>
                                        <td>
                                            <img src="{{ $author->profile_image ? $author->profile_image : asset('store/assets/img/user.png') }}" width="50" height="50" style="object-fit:cover; border-radius:50%;">
                                        </td>
                                        <td>{{ $author->full_name }}</td>
                                        <td>{{ $author->email ?? '-' }}</td>
                                        <td>{{ $author->designation ?? '-' }}</td>
                                        <td>{{ $author->location ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $author->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($author->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin-news-author.edit', $author->id) }}" class="btn btn-sm btn-info" style="margin: 0 5px;">Edit</a>
                                            <form id="delete-author-{{ $author->id }}" action="{{ route('admin-news-author.destroy', $author->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" style="margin: 0 5px;" data-delete-form="delete-author-{{ $author->id }}" data-message="Are you sure you want to delete this author?">Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="gridjs-footer">
                            <span class="text-danger">
                                Showing {{ $authors->firstItem() ?? 0 }} to {{ $authors->lastItem() ?? 0 }} of {{ $authors->total() }} entries
                            </span>
                            {{ $authors->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#author-table').DataTable({
            paging: false,
            info: false,
            dom: 'frtip',
        });
    });
</script>

@include('layouts.delete-confirm-modal')

@include('layouts.admin-footer')
