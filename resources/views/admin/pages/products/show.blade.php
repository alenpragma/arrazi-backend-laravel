@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Product Details</h3>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-secondary">← Back</a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    @if($product->images)
                        <img src="{{ asset('storage/' . $product->images) }}" class="img-fluid rounded shadow-sm" alt="Product Image">
                    @else
                        <p>No image available.</p>
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th>Title</th>
                            <td>{{ $product->title }}</td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td>{{ $product->slug }}</td>
                        </tr>
                        <tr>
                            <th>Regular Price</th>
                            <td>{{ number_format($product->regular_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Sale Price</th>
                            <td>{{ number_format($product->sale_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>{{ number_format($product->discount, 2) }}%</td>
                        </tr>
                        <tr>
                            <th>Points</th>
                            <td>{{ $product->points }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $product->description }}</td>
                        </tr>
                        <tr>
                            <th>Stock</th>
                            <td>
                                @if($product->stock)
                                    <span class="badge bg-success">In Stock</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($product->status)
                                    <span class="badge bg-primary">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $product->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $product->updated_at->format('d M Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">Edit Product</a>
        </div>
    </div>
</div>
@endsection
