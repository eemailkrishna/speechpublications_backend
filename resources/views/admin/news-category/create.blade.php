<div id="layout-wrapper">

    @include('layouts.admin-header')

    <style>
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #28a745;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>

    <div class="page-wrapper">
        @include('layouts.admin-navbar')
        <div class="page-content container-xxl">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    @include('layouts.breadcrumb-card', [
                        'title' => 'Create Category',
                        'icon' => 'folder',
                        'parentTitle' => 'Categories',
                        'parentUrl' => route('admin-news-category.index'),
                    ])
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin-news-category.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name *</label>
                                            <input type="text" class="form-control" placeholder="Category Name" id="name" name="name" value="{{ old('name') }}">
                                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="slug" class="form-label">Slug</label>
                                            <input type="text" class="form-control" placeholder="Auto-generated from name" id="slug" name="slug" value="{{ old('slug') }}">
                                            @error('slug')<div class="text-danger">{{ $message }}</div>@enderror
                                            <small class="text-muted">Leave blank to auto-generate from name.</small>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <div class="toggle-label">
                                                <label class="toggle-switch">
                                                    <input type="checkbox" id="status" name="status" value="1" checked>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <span id="status-text">Active</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{url('public/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('public/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{url('public/admin/assets/js/plugins.js')}}"></script>
<script src="{{url('public/admin/assets/js/app.js')}}"></script>

<script>
    const statusToggle = document.getElementById('status');
    const statusText = document.getElementById('status-text');

    statusToggle.addEventListener('change', function() {
        statusText.textContent = this.checked ? 'Active' : 'Inactive';
    });
</script>

@include('layouts.admin-footer')
