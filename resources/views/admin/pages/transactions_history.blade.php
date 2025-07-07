

@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Transactions History</div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover table-head-bg-primary mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Transaction Type</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>@ {{ $transaction->user->username }}</td>
                    <td>${{ number_format($transaction->amount, 3) }}</td>
                    <td>{{ ucfirst($transaction->transaction_type) }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $transactions->links('admin.layouts.partials.__pagination') }}
        </div>

    </div>
</div>
@endsection

