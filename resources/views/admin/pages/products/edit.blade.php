@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Edit Product</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.pages.products.partials.__form')
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
