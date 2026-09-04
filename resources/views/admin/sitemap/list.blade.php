<link href="{{url('public/admin/assets/css/app.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{url('public/admin/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

<div id="layout-wrapper">
    @include('layouts.admin-header')

    <div class="page-wrapper">
        @include('layouts.admin-navbar')
        <div class="page-content container-xxl">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @include('layouts.breadcrumb-card', [
                'title' => 'Sitemap Manager',
                'icon' => 'map',
                'parentTitle' => 'Dashboard',
                'parentUrl' => url('/dashboard'),
            ])

            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add Custom URL</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sitemap.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">URL *</label>
                                    <input type="url" class="form-control" name="url" placeholder="https://example.com/page" required value="{{ old('url') }}">
                                    @error('url')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Priority</label>
                                        <select class="form-control" name="priority">
                                            <option value="1.0" {{ old('priority') == '1.0' ? 'selected' : '' }}>1.0 (Highest)</option>
                                            <option value="0.9" {{ old('priority') == '0.9' ? 'selected' : '' }}>0.9</option>
                                            <option value="0.8" {{ old('priority') == '0.8' ? 'selected' : '' }}>0.8</option>
                                            <option value="0.7" {{ old('priority') == '0.7' ? 'selected' : '' }}>0.7</option>
                                            <option value="0.6" {{ old('priority') == '0.6' ? 'selected' : '' }}>0.6</option>
                                            <option value="0.5" {{ old('priority', '0.5') == '0.5' ? 'selected' : '' }}>0.5 (Default)</option>
                                            <option value="0.4" {{ old('priority') == '0.4' ? 'selected' : '' }}>0.4</option>
                                            <option value="0.3" {{ old('priority') == '0.3' ? 'selected' : '' }}>0.3</option>
                                            <option value="0.2" {{ old('priority') == '0.2' ? 'selected' : '' }}>0.2</option>
                                            <option value="0.1" {{ old('priority') == '0.1' ? 'selected' : '' }}>0.1 (Lowest)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Change Frequency</label>
                                        <select class="form-control" name="changefreq">
                                            <option value="daily" {{ old('changefreq') == 'daily' ? 'selected' : '' }}>Daily</option>
                                            <option value="weekly" {{ old('changefreq') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                            <option value="monthly" {{ old('changefreq', 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="yearly" {{ old('changefreq') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                            <option value="hourly" {{ old('changefreq') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                            <option value="always" {{ old('changefreq') == 'always' ? 'selected' : '' }}>Always</option>
                                            <option value="never" {{ old('changefreq') == 'never' ? 'selected' : '' }}>Never</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Add URL</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Sitemap Info</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Static pages:</strong> Home, Store, News, About, Contact (always included)</p>
                            <p class="mb-2"><strong>Products:</strong> Controlled by "Include in Sitemap" toggle on product edit page</p>
                            <p class="mb-2"><strong>News:</strong> Controlled by "Include in Sitemap" toggle on news edit page</p>
                            <p class="mb-0"><strong>Custom URLs:</strong> Added via the form above</p>
                            <hr>
                            <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-sm btn-outline-primary">View sitemap.xml</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Custom URLs ({{ $sitemaps->total() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>URL</th>
                                            <th>Priority</th>
                                            <th>Frequency</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sitemaps as $index => $item)
                                        <tr>
                                            <td>{{ $sitemaps->firstItem() + $index }}</td>
                                            <td style="max-width: 300px; word-break: break-all; font-size: 13px;">
                                                <a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a>
                                            </td>
                                            <td>{{ $item->priority }}</td>
                                            <td>{{ ucfirst($item->changefreq) }}</td>
                                            <td>
                                                <form action="{{ route('sitemap.toggle', $item->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('sitemap.delete', $item->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No custom URLs added yet.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $sitemaps->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{url('public/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('public/admin/assets/js/app.js')}}"></script>
@include('layouts.admin-footer')
