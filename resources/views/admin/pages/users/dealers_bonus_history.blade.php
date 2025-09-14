@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Dealer Bonus History</h4>

    <!-- Search Form -->
    <form action="{{ route('admin.dealers.bonus.history') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="email" class="form-control" placeholder="Search by dealer email" value="{{ request('email') }}">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="responsive">
                 <table class="table table-striped table-hover table-head-bg-primary mt-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Dealer Name</th>
                            <th>Dealer Email</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $history)
                            <tr>
                                <td>{{ $loop->iteration + ($histories->currentPage()-1) * $histories->perPage() }}</td>
                                <td>{{ $history->dealer->name ?? '-' }}</td>
                                <td>{{ $history->dealer->email ?? '-' }}</td>
                                <td>#{{ $history->order_id ?? '-' }}</td>
                                <td>{{ number_format($history->amount, 2) }}&#x09F3;</td>
                                <td>{{ $history->description }}</td>
                                <td>{{ $history->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No bonus history found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $histories->appends(['search' => request('search')])->links('admin.layouts.partials.__pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection
