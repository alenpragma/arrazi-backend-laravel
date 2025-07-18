@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Pending Withdraw History</div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover table-head-bg-primary mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">User</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Charge</th>
                    <th scope="col">Add to Shopping Wallet</th>
                    <th scope="col">Net Amount</th>
                    <th scope="col">Number</th>
                    <th scope="col">Method</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingWithdraws as $index => $withdraw)
                        @php
                        if ($withdraw->status === 'pending') {
                            $settings = app(\App\Models\GeneralSetting::class)->first();
                            $chargePercent = $settings->withdraw_charge ?? 0;
                            $shoppingPercent = $settings->withdraw_shopping_wallet_percentage ?? 0;

                            $charge = ($withdraw->amount * $chargePercent) / 100;
                            $remainingAmount = $withdraw->amount - $charge;
                            $shoppingAmount = ($remainingAmount * $shoppingPercent) / 100;
                            $netAmount = $remainingAmount - $shoppingAmount;
                        } else {
                            $charge = $withdraw->charge ?? 0;
                            $shoppingAmount = $withdraw->shopping_amount ?? 0;
                            $netAmount = $withdraw->net_amount ?? 0;
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1}}</td>
                        <td>{{ $withdraw->created_at ? $withdraw->created_at->format('Y-m-d H:i') : '-' }}</td>
                        <td>{{ $withdraw->user->name ?? 'N/A' }}</td>
                        <td>{{ number_format($withdraw->amount, 2) }}</td>
                        <td>{{ number_format($withdraw->charge, 2) }}</td>
                        <td>{{ number_format($withdraw->shopping_amount, 2) }}</td>
                        <td>{{ number_format($withdraw->net_amount, 2) }}</td>
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
                        <td>
                            @if($withdraw->status === 'pending')
                                <form style="width: auto; background:none; border:none; margin:0" method="POST" action="{{ route('admin.withdraws.updateStatus', $withdraw->id) }}">
                                    @csrf
                                    <button type="submit" name="status" value="approve" class="btn btn-success btn-sm">Approve</button>
                                    <button type="submit" name="status" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            @else
                                <span class="text-muted">No Action</span>
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
            {{ $pendingWithdraws->links('admin.layouts.partials.__pagination') }}
        </div>

    </div>
</div>
@endsection

