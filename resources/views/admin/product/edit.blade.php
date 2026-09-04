<link href="{{url('public/admin/assets/css/app.min.css')}}" rel="stylesheet" type="text/css">
<!-- custom Css-->
<link href="{{url('public/admin/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css">
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Custom styles for image preview */
    .image-preview-container {
        display: flex;
        flex-wrap: wrap; /* Allow previews to wrap to the next line */
        gap: 10px; /* Space between images */
        margin-top: 10px;
    }
    
    .image-preview {
        max-width: 100px; /* Limit preview size */
        height: auto;
        border: 1px solid #ccc;
        padding: 5px;
        position: relative; /* Relative positioning for icon overlay */
        display: inline-block;
    }
    
    .remove-icon {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: #ff0000;
        color: #fff;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 14px;
        cursor: pointer;
    }
    
    .image-preview img {
        width: 100%; /* Ensure images fill the preview box */
        height: auto;
    }
</style>
<div id="layout-wrapper">
    
    @include('layouts.admin-header')

    
    <!--<div class="main-content">-->
        <div class="page-wrapper">
            @include('layouts.admin-navbar')
            <div class="page-content container-xxl">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <!-- start page title -->
                @include('layouts.breadcrumb-card', [
                    'title' => 'Update Product',
                    'icon' => 'package',
                    'parentTitle' => 'Product',
                    'parentUrl' => url('/product-list'),
                ])
                <!-- end page title -->
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0"></h4>
                            </div><!-- end card header -->
                            <div class="card-body">
                                <form action="{{ route('products.update', $product->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!--<div class="col-xl-6">-->
                                            <!--<div class="mb-3">-->
                                            <!--    <label for="cleave-time" class="form-label">Product Type</label>-->
                                            <!--    <select class="form-control" name="type" id="product_type"  value="{{ old('type') }}">-->
                                            <!--        <option value="menu" {{ $product->type === 'menu' ? 'selected' : '' }}>Menu Item</option>-->
                                            <!--        <option value="product-type" {{ $product->type === 'product-type' ? 'selected' : '' }}>Product Item</option>-->
                                            <!--    </select>-->
                                            <!--</div>-->
                                        <!--</div>-->
                                        
                                        <div class="col-md-6"> 
                                            <div class="mb-3">
                                                <label for="cleave-time" class="form-label">Select Product Category</label>
                                                <select class="form-control"  name="category_id">
                                                    @foreach($productCategories as $list)
                                                    <option value="{{ $list->id }}" {{ $product->category_id == $list->id ? 'selected' : '' }}>
                                                        {{ $list->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div><!-- end col -->
                                        
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="cleave-date" class="form-label">Name</label>
                                                <input type="text" class="form-control" placeholder="Product Name"
                                                id="name" name="name" value="{{$product->name}}">
                                            </div>
                                        </div><!-- end col -->

                                         <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="cleave-date" class="form-label">Author Name</label>
                                                <input type="text" class="form-control" placeholder="Author Name"
                                                id="author_name" name="author_name" value="{{$product->author_name}}">
                                            </div>
                                        </div><!-- end col -->
                                         <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="cleave-date" class="form-label">Rating</label>
                                                <input type="text" class="form-control" placeholder="Product Rating"
                                                id="rating" name="rating" value="{{$product->rating}}">
                                            </div>
                                        </div><!-- end col -->
                                        
                                     

                                    
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="cleave-date" class="form-label">Heading</label>
                                                <input type="text" class="form-control" placeholder="Heading "
                                                id="heading" name="heading" value="{{$product->heading}}">
                                            </div>
                                        </div>

                                        
                                        
                                    </div><!-- end row -->
                                    
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0">Description</h4>
                                                </div>
                                                <div class="card-body">
                                                    <textarea id="description"  name="description" class="ckeditor-classic">{{$product->description}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0">Specifications</h4>
                                                </div>
                                                <div class="card-body">
                                                    <textarea id="specifications"  name="specifications" class="ckeditor-classic">{{$product->specification}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--<div class="row">-->
                                    <!--    <div class="col-lg-12">-->
                                    <!--        <div class="card">-->
                                    <!--            <div class="card-header align-items-center d-flex">-->
                                    <!--                <h4 class="card-title mb-0">Instruction For Use</h4>-->
                                    <!--            </div>-->
                                    <!--            <div class="card-body">-->
                                    <!--                <textarea id="instructions" name="instructions" class="ckeditor-classic">{{$product->Instruction}}</textarea>-->
                                    <!--            </div>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0">What’s inside the Box?</h4>
                                                </div>
                                                <div class="card-body">
                                                    <textarea id="box_contents" name="box_contents" class="ckeditor-classic">{{$product->inside_the_box}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--<div class="row">-->
                                    <!--    <div class="col-lg-12">-->
                                    <!--        <div class="card">-->
                                    <!--            <div class="card-header align-items-center d-flex">-->
                                    <!--                <h4 class="card-title mb-0">Technical Detail</h4>-->
                                    <!--            </div>-->
                                    <!--            <div class="card-body">-->
                                    <!--                <textarea id="technical_detail" name="technical_detail" class="ckeditor-classic">{{$product->technical_detail}}</textarea>-->
                                    <!--            </div>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                    <div class="mt-4">
                                        <div class="row">
                                            <!--<div class="col-xl-6">-->
                                            <!--    <div class="mb-3">-->
                                            <!--        <label for="cleave-time" class="form-label">Quantity</label>-->
                                            <!--        <input type="number" class="form-control" placeholder="Quantity" id="qty" name="qty"-->
                                            <!--        value="{{$product->qty}}">-->
                                            <!--    </div>-->
                                            <!--</div><!-- end col -->
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="cleave-time-format" class="form-label">Price</label>
                                                    <input type="number" class="form-control" placeholder="Price" id="price"
                                                    name="price" step="0.01" value="{{$product->price}}">
                                                </div>
                                            </div>
                                            
                                             <div class="col-xl-6">
                                                <div class="mt-4">
                                                  <select name="status" class="form-control">
                                                        <option value="">-- Select Status --</option>
                                                        <option value="launched" {{ old('status', $product->status ?? '') == 'launched' ? 'selected' : '' }}>Launched</option>
                                                        <option value="coming-soon" {{ old('status', $product->status ?? '') == 'coming-soon' ? 'selected' : '' }}>Coming Soon</option>
                                                    </select>

                                                </div>
                                            </div><!-- end col -->
                                        </div><!-- end row -->
                                        
                                        <div class="row">
                                            <!--<div class="col-xl-6">-->
                                            <!--    <div class="mb-3">-->
                                            <!--        <label for="cleave-time" class="form-label">Product Warranty</label>-->
                                            <!--        <input type="text" class="form-control" placeholder="warranty" id="warranty" name="warranty"-->
                                            <!--        value="{{$product->warranty}}">-->
                                            <!--    </div>-->
                                            <!--</div><!-- end col -->

                                            <!-- end col -->
                                        </div><!-- end row -->
                                    </div>
                                    
                                    <div class="border mt-3 border-dashed"></div>
                                    <div class="mt-4">
                                        <div class="row">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Existing Images</label>
                                                <div class="d-flex flex-wrap ">
                                                   
                                                    @foreach(json_decode(@$product->image) as $img)
                                                    <div class="position-relative me-2 mb-2 col-md-2 col-sm-6">
                                                        <div class="border border-info rounded p-1 h-100 " style="display:flex; justify-content:center;">
                                                            <img src="{{ Storage::disk('s3')->url('product/' . @$img) }}" alt="Image" class="img-thumbnail" style="max-width: 100%; height: auto;">
                                                            <div class="form-check position-absolute bottom-0 bg-white border border-danger rounded ">
                                                                <input class="form-check-input pl-1" type="checkbox" name="remove_images[]" value="{{ @$img }}" id="remove{{ $loop->index }}">
                                                                <label class="form-check-label pr-1" for="remove{{ $loop->index }}">Remove</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- New Images and eBook options -->
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="cleave-ccard" class="form-label">Image</label>
                                                        <input type="file" class="form-control" id="image" name="image[]" placeholder="Image" onchange="previewImages(event)" multiple>
                                                    </div>
                                                    @error('image')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="is-ebook" class="form-label">Is eBook?</label>
                                                    <div class="toggle-label d-flex align-items-center">
                                                        <label class="toggle-switch me-2">
                                                            <input type="checkbox" id="is-ebook" name="is_ebook" value="1" {{ (old('is_ebook', $product->is_ebook) == 1) ? 'checked' : '' }}>
                                                            <span class="toggle-slider"></span>
                                                        </label>
                                                        <span id="ebook-status">{{ (old('is_ebook', $product->is_ebook) == 1) ? 'Yes' : 'No' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="is-sitemap" class="form-label">Include in Sitemap?</label>
                                                    <div class="toggle-label d-flex align-items-center">
                                                        <label class="toggle-switch me-2">
                                                            <input type="checkbox" id="is-sitemap" name="is_sitemap" value="1" {{ (old('is_sitemap', $product->is_sitemap ?? 1) == 1) ? 'checked' : '' }}>
                                                            <span class="toggle-slider"></span>
                                                        </label>
                                                        <span id="sitemap-status">{{ (old('is_sitemap', $product->is_sitemap ?? 1) == 1) ? 'Yes' : 'No' }}</span>
                                                    </div>
                                                    <small class="text-muted">If enabled, this product URL will appear in sitemap.xml</small>
                                                </div>
                                            </div>
                                            </div><!-- end row -->

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="imagePreviewContainer" class="image-preview-container"></div>
                                                </div>
                                            </div><!-- end row -->

                                            <div id="pdfUploadSection" class="row {{ (old('is_ebook', $product->is_ebook) == 1) ? '' : 'd-none' }}">
                                                <div class="col-sm-6 col-md-6 col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="pdf-file" class="form-label">PDF File</label>
                                                        <input type="file" class="form-control" id="pdf-file" name="pdf_file" accept=".pdf" placeholder="Upload PDF">
                                                        @error('pdf_file')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        @if(!empty($product->ebook_pdf))
                                                            @php $pdfs = json_decode($product->ebook_pdf, true) ?? []; @endphp
                                                            @foreach($pdfs as $p)
                                                                <a class="d-block mt-1" href="{{ Storage::disk('s3')->url('ebook/' . $p) }}" target="_blank">Current PDF</a>
                                                            @endforeach
                                                        @endif
                                                        <small class="text-muted">Allowed format: PDF</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-6 col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="cleave-time-format" class="form-label">Ebook Price</label>
                                                        <input type="number" class="form-control" placeholder="Price" id="ebook_price" name="ebook_price" step="0.01" value="{{ old('ebook_price', $product->ebook_price) }}">
                                                    </div>
                                                </div><!-- end col -->
                                            </div><!-- end row -->
                                            
                                            <button style="width:90px;" type="submit" class=" btn btn-primary mt-3">Submit</button>
                                        </div><!-- end row -->
                                    </div>
                                </form><!-- end form -->
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                <!--</div>-->
            </div> <!-- container-fluid -->
        </div>
        
        
    </div>
    
    <script src="{{url('public/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{url('public/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{url('public/admin/assets/js/plugins.js')}}"></script>
    
    <!-- ckeditor -->
    <!-- <script src="{{url('public/admin/assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script> -->
    
    <!-- init js -->
    <!-- <script src="{{url('public/admin/assets/js/pages/form-editor.init.js')}}"></script> -->
    
    <script src="{{url('public/admin/assets/js/app.js')}}"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            ClassicEditor
            .create(document.querySelector('#specifications'))
            .catch(error => {
                console.error(error);
            });
            
            ClassicEditor
            .create(document.querySelector('#instructions'))
            .catch(error => {
                console.error(error);
            });
            
            ClassicEditor
            .create(document.querySelector('#box_contents'))
            .catch(error => {
                console.error(error);
            });
            
            ClassicEditor
            .create(document.querySelector('#technical_detail'))
            .catch(error => {
                console.error(error);
            });

            ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
        });
        
    </script>
     <script>
        // Toggle PDF upload section when is_ebook checkbox changes
        document.addEventListener('DOMContentLoaded', function () {
            const isEbookCheckbox = document.getElementById('is-ebook');
            const pdfSection = document.getElementById('pdfUploadSection');
            const ebookStatus = document.getElementById('ebook-status');

            function togglePdfSection() {
                if (!isEbookCheckbox) return;
                if (isEbookCheckbox.checked) {
                    pdfSection.classList.remove('d-none');
                    ebookStatus.textContent = 'Yes';
                } else {
                    pdfSection.classList.add('d-none');
                    ebookStatus.textContent = 'No';
                }
            }

            if (isEbookCheckbox) {
                isEbookCheckbox.addEventListener('change', togglePdfSection);
                togglePdfSection();
            }

            // Sitemap toggle
            const isSitemapCheckbox = document.getElementById('is-sitemap');
            const sitemapStatus = document.getElementById('sitemap-status');
            if (isSitemapCheckbox) {
                isSitemapCheckbox.addEventListener('change', function() {
                    sitemapStatus.textContent = this.checked ? 'Yes' : 'No';
                });
            }
        });
        function previewImages(event) {
            const files = Array.from(event.target.files); // Convert FileList to an array
            const previewContainer = document.getElementById('imagePreviewContainer');
            const inputElement = document.getElementById('image');

            previewContainer.innerHTML = ''; // Clear previous previews

            files.forEach((file, index) => {
                // Ensure the file is an image
                if (file.type.match('image.*')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imageElement = document.createElement('div');
                        imageElement.classList.add('image-preview');
                        
                        const removeIcon = document.createElement('span');
                        removeIcon.classList.add('remove-icon');
                        removeIcon.innerHTML = '&times;'; // X symbol for removing
                        removeIcon.onclick = function() {
                            files.splice(index, 1); // Remove file from the array
                            updateFileList(inputElement, files); // Update the file input
                            imageElement.remove(); // Remove the preview
                        };
                        
                        imageElement.innerHTML = `<img src="${e.target.result}" alt="Image Preview">`;
                        imageElement.appendChild(removeIcon);
                        previewContainer.appendChild(imageElement);
                    };

                    // Read the file as a Data URL
                    reader.readAsDataURL(file);
                } else {
                    // Handle non-image file selections if needed
                    console.warn('Selected file is not an image: ', file);
                }
            });

            // Update the input element's file list
            updateFileList(inputElement, files);
        }

        function updateFileList(inputElement, files) {
            const dataTransfer = new DataTransfer();
            files.forEach(file => {
                dataTransfer.items.add(file);
            });
            inputElement.files = dataTransfer.files;
        }
    </script>
    {{-- <script>
        function previewImages(event) {
            const files = event.target.files; // Get all selected files
            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = ''; // Clear previous previews
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Ensure the file is an image
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const imageElement = document.createElement('div');
                        imageElement.classList.add('image-preview');
                        
                        const removeIcon = document.createElement('span');
                        removeIcon.classList.add('remove-icon');
                        removeIcon.innerHTML = '&times;'; // X symbol for removing
                        removeIcon.onclick = function() {
                            imageElement.remove(); // Remove the preview
                        };
                        
                        imageElement.innerHTML = `<img src="${e.target.result}" alt="Image Preview">`;
                        imageElement.appendChild(removeIcon);
                        previewContainer.appendChild(imageElement);
                    };
                    
                    // Read the file as a Data URL
                    reader.readAsDataURL(file);
                } else {
                    // Handle non-image file selections if needed
                    console.warn('Selected file is not an image: ', file);
                }
            }
        }
    </script> --}}
    
    @include('layouts.admin-footer')