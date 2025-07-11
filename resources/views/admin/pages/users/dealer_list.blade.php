@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"> Dealers</h4>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dealers.list') }}" class="mb-3 d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name, email or phone" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-head-bg-primary mt-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Wallet ($)</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dealers as $index => $dealer)
                        <tr>
                            <td>{{ $dealers->firstItem() + $index }}</td>
                            <td>{{ $dealer->name }}</td>
                            <td>{{ $dealer->email }}</td>
                            <td>{{ $dealer->phone ?? '-' }}</td>
                            <td>${{ number_format($dealer->shopping_wallet + $dealer->income_wallet, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $dealer->is_active ? 'success' : 'secondary' }}">
                                    {{ $dealer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $dealer->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.dealers.show', $dealer->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No dealers found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $dealers->appends(['search' => request('search')])->links('admin.layouts.partials.__pagination') }}
        </div>
    </div>
</div>
@endsection
