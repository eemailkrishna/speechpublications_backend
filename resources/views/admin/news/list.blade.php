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
                    'title' => 'News',
                    'icon' => 'newspaper',
                    'parentUrl' => route('admin-news.index'),
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">All News</h4>
                        <a href="{{ route('admin-news.create') }}" class="btn btn-primary btn-sm">Add News</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive col-sm-12">
                            <table class="table table-striped table-hover align-middle mb-0" id="news-table" cellspacing="0" width="100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="gridjs-th"><div class="gridjs-th-content">#</div></th>
                                        <th class="gridjs-th"><div class="gridjs-th-content">Image</div></th>
                                        <th class="gridjs-th"><div class="gridjs-th-content">Title</div></th>
                                        <th class="gridjs-th"><div class="gridjs-th-content">Category</div></th>
                                        <th class="gridjs-th"><div class="gridjs-th-content">Author</div></th>
                                        <th class="gridjs-th"><div class="gridjs-th-content">Publish Date</div></th>
                                        <th class="gridjs-th"><div class="gridjs-th-content">Views</div></th>
                                        <th class="gridjs-th"><div class="gridjs-th-content">Featured</div></th>
                                        <th class="gridjs-th"><div class="gridjs-th-content">Status</div></th>
                                        <th class="gridjs-th"><div class="gridjs-th-content">Action</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($news as $item)
                                    <tr>
                                        <td class="text-secondary">{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $item->featured_image ? $item->featured_image : asset('store/assets/img/news/post-1.jpg') }}" width="64" height="44" style="object-fit:cover; border-radius:8px;">
                                        </td>
                                        <td class="fw-semibold" style="white-space: normal; min-width:200px;">{{ $item->title }}</td>
                                        <td><span class="badge bg-primary-subtle text-primary fw-normal">{{ $item->category->name ?? '-' }}</span></td>
                                        <td>{{ $item->author->full_name ?? '-' }}</td>
                                        <td class="text-secondary">{{ $item->publish_date ? $item->publish_date->format('d M Y') : '-' }}</td>
                                        <td><i data-lucide="eye" class="icon-sm me-1 text-secondary"></i>{{ $item->view_count }}</td>
                                        <td>
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input highlight-toggle" type="checkbox" id="highlight-{{ $item->id }}" data-id="{{ $item->id }}" {{ $item->is_highlight ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->status == 'published' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin-news.edit', $item->id) }}" class="btn btn-sm btn-info"><i data-lucide="pencil" class="icon-sm"></i> Edit</a>
                                            <form id="delete-news-{{ $item->id }}" action="{{ route('admin-news.destroy', $item->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-delete-form="delete-news-{{ $item->id }}" data-message="Are you sure you want to delete this news?"><i data-lucide="trash" class="icon-sm"></i> Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="gridjs-footer d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3">
                            <span class="text-secondary small">
                                Showing {{ $news->firstItem() ?? 0 }} to {{ $news->lastItem() ?? 0 }} of {{ $news->total() }} entries
                            </span>
                            <div>
                                {{ $news->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#news-table').DataTable({
            paging: false,
            info: false,
            dom: 'frtip',
        });

        $(document).on('change', '.highlight-toggle', function() {
            var input = $(this);
            var id = input.data('id');
            var url = '{{ url('/admin-news/toggle-highlight') }}' + '/' + id;

            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                beforeSend: function() {
                    input.prop('disabled', true);
                },
                success: function(resp) {
                    if (!resp.success) {
                        alert('Failed to update: ' + (resp.message || 'unknown'));
                        input.prop('checked', !input.prop('checked'));
                    }
                },
                error: function(xhr) {
                    var msg = 'Failed to update';
                    try {
                        var body = JSON.parse(xhr.responseText || '{}');
                        msg = body.message || msg;
                    } catch (e) {}
                    alert(msg + ' (status ' + xhr.status + ')');
                    input.prop('checked', !input.prop('checked'));
                },
                complete: function() {
                    input.prop('disabled', false);
                }
            });
        });
    });
</script>

@include('layouts.delete-confirm-modal')

@include('layouts.admin-footer')
