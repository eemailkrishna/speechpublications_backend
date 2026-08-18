<div id="layout-wrapper">

    @include('layouts.admin-header')

    <style>
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview {
            max-width: 120px;
            height: auto;
            border: 1px solid #ccc;
            padding: 5px;
            position: relative;
            display: inline-block;
        }

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
                        'title' => 'Create Author',
                        'icon' => 'user',
                        'parentTitle' => 'Authors',
                        'parentUrl' => route('admin-news-author.index'),
                    ])
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin-news-author.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name *</label>
                                            <input type="text" class="form-control" placeholder="Author Full Name" id="full_name" name="full_name" value="{{ old('full_name') }}">
                                            @error('full_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" placeholder="Author Email" id="email" name="email" value="{{ old('email') }}">
                                            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="designation" class="form-label">Designation</label>
                                            <input type="text" class="form-control" placeholder="e.g. Senior Editor" id="designation" name="designation" value="{{ old('designation') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="location" class="form-label">Location</label>
                                            <input type="text" class="form-control" placeholder="e.g. New Delhi, India" id="location" name="location" value="{{ old('location') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" class="form-control" placeholder="Phone Number" id="phone" name="phone" value="{{ old('phone') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="language" class="form-label">Language</label>
                                            <input type="text" class="form-control" placeholder="e.g. English" id="language" name="language" value="{{ old('language') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="specialization" class="form-label">Specialization</label>
                                            <input type="text" class="form-control" placeholder="e.g. Children's Literature" id="specialization" name="specialization" value="{{ old('specialization') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="profile_image" class="form-label">Profile Image</label>
                                            <input type="file" class="form-control" id="profile_image" name="profile_image" onchange="previewImage(event)">
                                            <div id="imagePreviewContainer" class="image-preview-container"></div>
                                            @error('profile_image')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="facebook_url" class="form-label">Facebook URL</label>
                                            <input type="url" class="form-control" placeholder="https://facebook.com/..." id="facebook_url" name="facebook_url" value="{{ old('facebook_url') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="twitter_url" class="form-label">Twitter URL</label>
                                            <input type="url" class="form-control" placeholder="https://x.com/..." id="twitter_url" name="twitter_url" value="{{ old('twitter_url') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                                            <input type="url" class="form-control" placeholder="https://linkedin.com/..." id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="instagram_url" class="form-label">Instagram URL</label>
                                            <input type="url" class="form-control" placeholder="https://instagram.com/..." id="instagram_url" name="instagram_url" value="{{ old('instagram_url') }}">
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

                                    <div class="col-xl-12">
                                        <div class="mb-3">
                                            <label for="bio" class="form-label">Bio</label>
                                            <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Short biography...">{{ old('bio') }}</textarea>
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

    function previewImage(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = '';

        if (file && file.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageElement = document.createElement('div');
                imageElement.classList.add('image-preview');
                imageElement.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                previewContainer.appendChild(imageElement);
            };
            reader.readAsDataURL(file);
        }
    }
</script>

@include('layouts.admin-footer')
