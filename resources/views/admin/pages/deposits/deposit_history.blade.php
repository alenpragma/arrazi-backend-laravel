@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Deposit History</div>
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
                    <th scope="col">Status</th>
                    <th scope="col">Transaction ID</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($deposits as $index => $deposit)
                    <tr>
                        <td>{{ $index + 1}}</td>
                        <td>{{ $deposit->created_at ? $deposit->created_at->format('Y-m-d H:i') : '-' }}</td>
                        <td>{{ $deposit->user->name ?? 'Unknown' }}</td>
                        <td>{{ $deposit->amount }}</td>
                        <td>{{ $deposit->currency }}</td>
                        <td>{{ $deposit->paymentMethod->method_name ?? 'N/A' }}</td>
                        <td>{{ $deposit->number }}</td>
                        <td>
                            <span class="badge
                                @if($deposit->status == 'pending') bg-warning
                                @elseif($deposit->status == 'approved') bg-success
                                @else bg-danger @endif">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </td>
                        <td>{{ $deposit->transaction_id }}</td>
                        <td  >
                            <div class="d-flex">
                                @if($deposit->status == 'pending')
                            <form style="width: auto; background:none; border:none; margin:0" action="{{ route('admin.deposits.updateStatus', $deposit->id) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button class="btn btn-success btn-sm" onclick="return confirm('Approve this deposit?')">Approve</button>
                            </form>
                            <form style="width: auto; background:none; border:none; margin:0" action="{{ route('admin.deposits.updateStatus', $deposit->id) }}" method="POST" class="mt-1">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Reject this deposit?')">Reject</button>
                            </form>
                            @else
                            <span class="text-muted">No action</span>
                            @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No deposit found</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $deposits->links('admin.layouts.partials.__pagination') }}
        </div>

    </div>
</div>
@endsection

