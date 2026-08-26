@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h3>Popular Books</h3>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form action="{{ route('admin.popular-books.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="product_id" class="form-select">
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Add to Popular</button>
            </div>
        </div>
    </form>

    <div class="list-group">
        @foreach($popularBooks as $pb)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $pb->name }}</strong>
                    <div class="text-muted">₹{{ $pb->price ?? '' }}</div>
                </div>
                <form action="{{ route('admin.popular-books.destroy', $pb) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Remove</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
