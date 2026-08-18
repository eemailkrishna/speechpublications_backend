<div class="row">
    <div class="col-12">
        <div class="card breadcrumb-card mb-3">
            <div class="card-body py-3 d-sm-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0 mb-sm-0 d-flex align-items-center">
                    <i data-lucide="{{ $icon ?? 'file-text' }}" class="icon-lg me-2 text-primary"></i>
                    {{ $title }}
                </h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ $homeUrl ?? 'javascript: void(0);' }}">Home</a></li>
                    @if(!empty($parentTitle) && !empty($parentUrl))
                        <li class="breadcrumb-item"><a href="{{ $parentUrl }}">{{ $parentTitle }}</a></li>
                    @endif
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
