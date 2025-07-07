@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Products Management</h3>
            <div class="card-tools">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('admin.layouts.partials.__alerts')

            <div class="table-responsive">
                <table class="table table-striped table-hover table-head-bg-primary mt-4" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Sale Price</th>
                            <th>Discount Price</th>
                            <th>Point</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><img src="{{ asset('storage/' . $product->images) }}" width="30"></td>
                            <td>{{ $product->title }}</td>
                            <td>{{ $product->slug }}</td>
                            <td>{{ $product->sale_price }}</td>
                            <td>{{ $product->discount }}</td>
                            <td>{{ $product->points }}</td>
                            <td>{{ $product->stock ? 'In Stock' : 'Out of Stock' }}</td>
                            <td>{{ $product->status ? 'Active' : 'Inactive' }}</td>
                            {{-- <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr> --}}
                            <td>
                                @include('admin.pages.products.partials.__actions')
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No Product found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $products->links('admin.layouts.partials.__pagination') }}
        </div>
    </div>
</div>
@endsection