@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stock Purchase History</h3>
            <div class="card-tools">
                {{-- Optional: Add stock manually or other actions --}}
            </div>
        </div>
        <div class="card-body">
            @include('admin.layouts.partials.__alerts')

            <form method="GET" action="{{ route('admin.stocks.index') }}" class="mb-3 d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by user name, email or status" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-head-bg-primary mt-4" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Amount ($)</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $index => $stock)
                        <tr>
                            <td>{{ $stocks->firstItem() + $index }}</td>
                            <td>{{ $stock->user->name ?? 'N/A' }}</td>
                            <td>{{ $stock->user->email ?? 'N/A' }}</td>
                            <td>${{ number_format($stock->amount, 2) }}</td>
                            <td>{{ ucfirst($stock->status) }}</td>
                            <td>{{ $stock->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No stock purchases found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $stocks->appends(['search' => request('search')])->links('admin.layouts.partials.__pagination') }}
        </div>
    </div>
</div>
@endsection
