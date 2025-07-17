@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Transfer History</div>
    </div>
    {{-- <form method="GET" action="{{ route('admin.transfer-history.index') }}" class="mb-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by user name or email" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </form> --}}
    <div class="card-body table-responsive">
        <form method="GET" action="{{ route('admin.transfer-history.index') }}" class="mb-3 mt-3 d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by name, email or phone" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <table class="table table-striped table-hover table-head-bg-primary mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">User</th>
                    <th scope="col">From Wallet</th>
                    <th scope="col">To Wallet</th>
                    <th scope="col">Type</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transfers as $index => $transfer)
                    <tr>
                        <td>{{ $index + $transfers->firstItem() }}</td>
                        <td>{{ $transfer->created_at ? $transfer->created_at->format('Y-m-d H:i') : '-' }}</td>
                        <td>{{ $transfer->user->name ?? 'N/A' }} </td>
                        <td>{{ ucfirst($transfer->from) }}</td>
                        <td>{{ ucfirst($transfer->to) }}</td>
                        <td>{{ ucfirst($transfer->type) }}</td>
                        <td>{{ number_format($transfer->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No Transfer History Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $transfers->links('admin.layouts.partials.__pagination') }}
        </div>
    </div>
</div>
@endsection
