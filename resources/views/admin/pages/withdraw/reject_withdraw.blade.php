@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Rejected Withdraw History</div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover table-head-bg-primary mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">User</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Number</th>
                    <th scope="col">Method</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rejectWithdraws as $index => $withdraw)
                    <tr>
                        <td>{{ $index + 1}}</td>
                        <td>{{ $withdraw->created_at ? $withdraw->created_at->format('Y-m-d H:i') : '-' }}</td>
                        <td>{{ $withdraw->user->name ?? 'N/A' }}</td>
                        <td>{{ $withdraw->amount }}</td>
                        <td>{{ $withdraw->number }}</td>
                        <td>{{ $withdraw->method }}</td>
                        <td>
                            @if($withdraw->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($withdraw->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($withdraw->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No pending withdraw  found</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $rejectWithdraws->links('admin.layouts.partials.__pagination') }}
        </div>

    </div>
</div>
@endsection

