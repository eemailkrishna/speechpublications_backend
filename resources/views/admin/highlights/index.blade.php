@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h3>Highlights</h3>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <form action="{{ route('admin.highlights.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="row g-2">
            <div class="col-auto">
                <select name="news_id" class="form-select">
                    @foreach($newsList as $n)
                        <option value="{{ $n->id }}">{{ $n->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Add to Highlights</button>
            </div>
        </div>
    </form>

    <div class="list-group">
        @foreach($highlights as $h)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $h->title }}</strong>
                    <div class="text-muted">{{ optional($h->publish_date)->format('Y-m-d') }}</div>
                </div>
                <form action="{{ route('admin.highlights.destroy', $h) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Remove</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
