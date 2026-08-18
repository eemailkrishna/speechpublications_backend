
    
    
    <div id="layout-wrapper">
        
        @include('layouts.admin-header')
       
        
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

            /* Toggle Switch Styles */
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

            #pdfUploadSection {
                /* Grid will be handled by Bootstrap classes */
            }
        </style>
        
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
                        'title' => 'Create Product',
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
                                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div>
                                            <!-- <h5 class="fs-md mb-3 text-muted">Create Product</h5> -->
                                            <div class="row">
                                                <!--<div class="col-xl-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label for="cleave-time" class="form-label">Product Type</label>-->
                                                <!--        <select class="form-control" name="type">-->
                                                <!--            <option value="menu">Menu Item</option>-->
                                                <!--            <option value="product-type">Product Item</option>-->
                                                <!--        </select>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <div class="col-md-6"> 
                                                    <div class="mb-3">
                                                        <label for="cleave-time" class="form-label">Select Product Category</label>
                                                        <select class="form-control"  name="category_id">
                                                            @foreach(@$productCategories as $list)
                                                            <option value="{{$list->id}}">{{$list->name}}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="cleave-date" class="form-label">Name</label>
                                                        <input type="text" class="form-control" placeholder="Product Name" id="name" name="name" value="{{ old('name') }}" >
                                                    </div>
                                                 </div><!-- end col -->   
                                                    <div class="col-xl-6">
                                                     <div class="mb-3">
                                                        <label for="cleave-date" class="form-label">Author Name</label>
                                                        <input type="text" class="form-control" placeholder="Author Name" id="author_name" name="author_name" value="{{ old('author_name') }}" >
                                                    </div>
                                                    </div><!-- end col -->
                                                    <div class="col-xl-6">
                                                     <div class="mb-3">
                                                        <label for="cleave-date" class="form-label">Rating</label>
                                                        <input type="text" class="form-control" placeholder="Product Rating" id="rating" name="rating" value="{{ old('rating') }}" >
                                                    </div>
                                                </div><!-- end col -->
                                                
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="cleave-date-format" class="form-label">Description</label>
                                                        <input type="text" class="form-control" placeholder="Description"  id="description" name="description">
                                                    </div>
                                                </div><!-- end col -->
                                                  <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="cleave-date" class="form-label">Heading</label>
                                                    <input type="text" class="form-control" placeholder="Heading "
                                                    id="heading" name="heading" value="">
                                                </div>
                                                
                                            </div>
                                          
                                        </div>
                                        
                                       
                                        
                                        <div class="row">
                                          
                                        </div><!-- end row -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header align-items-center d-flex">
                                                        <h4 class="card-title mb-0">Specifications</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <textarea id="specifications"  name="specifications" class="ckeditor-classic"></textarea>
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
                                        <!--                <textarea id="instructions" name="instructions" class="ckeditor-classic"></textarea>-->
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
                                                        <textarea id="box_contents" name="box_contents" class="ckeditor-classic"></textarea>
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
                                        <!--                <textarea id="technical_detail" name="technical_detail" class="ckeditor-classic"></textarea>-->
                                        <!--            </div>-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        
                                        
                                        <div class="mt-4">
                                            
                                            <div class="row">
                                                
                                                <!--<div class="col-xl-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label for="cleave-time" class="form-label">Quantity</label>-->
                                                <!--        <input type="number" class="form-control" placeholder="Quantity" id="qty" name="qty" value="{{ old('qty') }}">-->
                                                <!--    </div>-->
                                                    
                                                <!--</div><!-- end col -->
                                                
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="cleave-time-format" class="form-label">Price</label>
                                                        <input type="number" class="form-control" placeholder="Price" id="price" name="price" step="0.01" value="{{ old('price') }}">
                                                    </div>
                                                </div><!-- end col -->
                                               
                                                 <div class="col-xl-6">
                                                    <div class="mt-4">
                                                       <select name="status" class="form-control" >
                                                            <option value="coming-soon">-- Select Status --</option>
                                                            <option value="launched">Launched</option>
                                                            <option value="coming-soon">Coming Soon</option>
                                                        </select>
                                                    </div>
                                                    
                                                </div>
                                            </div><!-- end row -->
                                            <div class="row">
                                                <!--<div class="col-xl-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label for="cleave-time" class="form-label">Product Warranty</label>-->
                                                <!--        <input type="text" class="form-control" placeholder="warranty" id="warranty" name="warranty"-->
                                                <!--        value="">-->
                                                <!--    </div>-->
                                                    
                                                <!--</div><!-- end col -->
                                                

                                                
                                           
                                        </div>
                                        
                                        <!-- PDF Upload Section (shown only for eBooks) -->
                                       
                                        
                                        <div class="border mt-3 border-dashed"></div>
                                        
                                        <div class="mt-4">
                                            
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
                                                        <div class="toggle-label">
                                                            <label class="toggle-switch">
                                                                <input type="checkbox" id="is-ebook" name="is_ebook" value="1">
                                                                <span class="toggle-slider"></span>
                                                            </label>
                                                            <span id="ebook-status">No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end row -->

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="imagePreviewContainer" class="image-preview-container"></div>
                                                </div>
                                            </div><!-- end row -->

                                            <div id="pdfUploadSection" class="row d-none">
                                                <div class="col-sm-6 col-md-6 col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="pdf-file" class="form-label">PDF File</label>
                                                        <input type="file" class="form-control" id="pdf-file" name="pdf_file" accept=".pdf" placeholder="Upload PDF">
                                                        @error('pdf_file')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        <small class="text-muted">Allowed format: PDF</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-6 col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="cleave-time-format" class="form-label">Ebook Price</label>
                                                        <input type="number" class="form-control" placeholder="Price" id="ebook_price" name="ebook_price" step="0.01" value="{{ old('ebook_price') }}">
                                                    </div>
                                                </div><!-- end col -->
                                            </div><!-- end row -->
                                            
                                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                            
                                            
                                        </div>
                                    </form><!-- end form -->
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div> <!-- container-fluid -->
            </div>
          
        </div>
</div>
        
        {{-- ------------------------------------------- --}}
        <script src="{{url('public/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{url('public/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{url('public/admin/assets/js/plugins.js')}}"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
        
        <!-- ckeditor -->
        <!-- <script src="{{url('public/admin/assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script> -->
        
        <!-- init js -->
        <!-- <script src="{{url('public/admin/assets/js/pages/form-editor.init.js')}}"></script> -->
        
        <script src="{{url('public/admin/assets/js/app.js')}}"></script>
        
        <script>
            // Toggle eBook and show/hide PDF upload
            const ebookToggle = document.getElementById('is-ebook');
            const pdfUploadSection = document.getElementById('pdfUploadSection');
            const ebookStatus = document.getElementById('ebook-status');

            ebookToggle.addEventListener('change', function() {
                if (this.checked) {
                    pdfUploadSection.classList.remove('d-none');
                    ebookStatus.textContent = 'Yes';
                } else {
                    pdfUploadSection.classList.add('d-none');
                    ebookStatus.textContent = 'No';
                    // Clear the PDF file input when unchecked
                    document.getElementById('pdf-file').value = '';
                }
            });

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
            });
            
        </script>
        
        <script>
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
         <script>
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
        </script> 
        @include('layouts.admin-footer')
        