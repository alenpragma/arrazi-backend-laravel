@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Pending Deposit History</div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover table-head-bg-primary mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">User</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Method</th>
                    <th scope="col">Number</th>
                    <th scope="col">Transaction ID</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rejectedDeposits  as $index => $deposit)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $deposit->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $deposit->user->name ?? 'N/A' }}</td>
                        <td>{{ $deposit->amount }}</td>
                        <td>{{ $deposit->currency }}</td>
                        <td>{{ $deposit->payment_method }}</td>
                        <td>{{ $deposit->number }}</td>
                        <td>{{ $deposit->transaction_id }}</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center">No Rejected deposits found.</td></tr>
                @endforelse

            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $rejectedDeposits->links('admin.layouts.partials.__pagination') }}
        </div>

    </div>
</div>
@endsection

