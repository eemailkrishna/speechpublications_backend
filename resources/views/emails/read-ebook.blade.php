@include('layouts.store-header')

<div class="breadcrumb-wrapper bg-cover section-padding" style="background-image: url({{ asset('assets/img/hero/breadcrumb-bg.jpg') }});">
    <div class="container">
        <div class="page-heading">
            <h1>Read eBook</h1>
            <div class="page-header">
                <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><i class="fa-solid fa-chevron-right"></i></li>
                    <li>Read eBook: {{ $product->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="read-ebook-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-white">{{ $product->name }}</h4>
                    </div>
                    <div class="card-body p-0" style="height: 80vh;">
                        <!-- Adding #toolbar=0&navpanes=0&scrollbar=0 prevents users from downloading/saving PDF easily -->
                        <iframe src="{{ $pdfUrl }}#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="100%" style="border: none;" oncontextmenu="return false;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Disable right click to prevent saving page -->
<script>
    document.addEventListener('contextmenu', event => event.preventDefault());
</script>

@include('layouts.store-footer')