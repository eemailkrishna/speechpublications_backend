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
                        'title' => 'Create News',
                        'icon' => 'newspaper',
                        'parentTitle' => 'News',
                        'parentUrl' => route('admin-news.index'),
                    ])
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin-news.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title *</label>
                                            <input type="text" class="form-control" placeholder="News Title" id="title" name="title" value="{{ old('title') }}">
                                            @error('title')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-control" name="category_id" id="category_id">
                                                <option value="">-- Select Category --</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="author_id" class="form-label">Author</label>
                                            <select class="form-control" name="author_id" id="author_id">
                                                <option value="">-- Select Author --</option>
                                                @foreach($authors as $author)
                                                <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>{{ $author->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="publish_date" class="form-label">Publish Date</label>
                                            <input type="datetime-local" class="form-control" id="publish_date" name="publish_date" value="{{ old('publish_date') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="excerpt" class="form-label">Excerpt (Optional)</label>
                                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Short summary...">{{ old('excerpt') }}</textarea>
                                            <small class="text-muted">Leave blank to auto-generate from description.</small>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="featured_image" class="form-label">Featured Image</label>
                                            <input type="file" class="form-control" id="featured_image" name="featured_image" onchange="previewImage(event)">
                                            <div id="imagePreviewContainer" class="image-preview-container"></div>
                                            @error('featured_image')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label">Featured News</label>
                                            <div class="toggle-label">
                                                <label class="toggle-switch">
                                                    <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <span id="featured-status">{{ old('featured') ? 'Yes' : 'No' }}</span>
                                            </div>
                                            <small class="text-muted">Only one news can be featured at a time.</small>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">Meta Title (Optional)</label>
                                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">Meta Description (Optional)</label>
                                            <input type="text" class="form-control" id="meta_description" name="meta_description" value="{{ old('meta_description') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0">Description *</h4>
                                            </div>
                                            <div class="card-body">
                                                <textarea id="description" name="description" class="ckeditor-classic">{{ old('description') }}</textarea>
                                                @error('description')<div class="text-danger">{{ $message }}</div>@enderror
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
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.css">
<script type="importmap">
{
    "imports": {
        "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.js",
        "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.2/"
    }
}
</script>
<script src="{{url('public/admin/assets/js/app.js')}}"></script>

<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Paragraph,
        Heading,
        List,
        Link,
        BlockQuote,
        Indent,
        CodeBlock,
        Undo,
        Image,
        ImageBlock,
        ImageInline,
        ImageCaption,
        ImageStyle,
        ImageToolbar,
        ImageUpload,
        ImageInsert,
        ImageResize,
        Table,
        TableToolbar,
        MediaEmbed
    } from 'ckeditor5';

    class NewsUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file.then(file => new Promise((resolve, reject) => {
                const data = new FormData();
                data.append('upload', file);
                data.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('admin-news.upload-image') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: data,
                })
                .then(response => response.json())
                .then(json => {
                    if (json.url) {
                        resolve({ default: json.url });
                    } else {
                        reject(json.message || 'Upload failed');
                    }
                })
                .catch(error => reject(error));
            }));
        }

        abort() {}
    }

    function NewsUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => new NewsUploadAdapter(loader);
    }

    ClassicEditor
        .create(document.querySelector('#description'), {
            plugins: [
                Essentials, Bold, Italic, Paragraph, Heading, List, Link,
                BlockQuote, Indent, CodeBlock, Undo,
                Image, ImageBlock, ImageInline, ImageCaption, ImageStyle,
                ImageToolbar, ImageUpload, ImageInsert, ImageResize,
                Table, TableToolbar, MediaEmbed,
                NewsUploadAdapterPlugin
            ],
            toolbar: [
                'undo', 'redo', '|',
                'heading', '|',
                'bold', 'italic', '|',
                'link', '|',
                'bulletedList', 'numberedList', '|',
                'blockQuote', 'codeBlock', 'insertImage', 'insertTable', 'mediaEmbed', '|',
                'outdent', 'indent'
            ],
            image: {
                toolbar: [
                    'resizeImage',
                    '|',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side',
                    '|',
                    'toggleImageCaption',
                    'imageTextAlternative'
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script>
    const featuredToggle = document.getElementById('featured');
    const featuredStatus = document.getElementById('featured-status');

    featuredToggle.addEventListener('change', function() {
        featuredStatus.textContent = this.checked ? 'Yes' : 'No';
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
                imageElement.innerHTML = `<img style="width:100px" src="${e.target.result}" alt="Preview">`;
                previewContainer.appendChild(imageElement);
            };
            reader.readAsDataURL(file);
        }
    }
</script>

@include('layouts.admin-footer')
