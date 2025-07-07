@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class=" justify-content-between align-items-center">
                <h3 class="card-title">All Orders</h3>
                @if(request()->has('status'))
                <div class="alert alert-info">
                    Showing <strong>{{ ucfirst(request()->status) }}</strong> orders
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary float-end mt-0" style="margin-top: -5px !important;">Clear Filter</a>
                </div>
            @endif
            </div>
        </div>
        <div class="card-body">
            @include('admin.layouts.partials.__alerts')

            {{-- <div class="mb-0">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-end">
                    <!-- Status Filter -->
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                        <label for="status" class="form-label small mb-0">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Date From Filter -->
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                        <label for="date_from" class="form-label small mb-0">From</label>
                        <input type="date" name="date_from" id="date_from"
                               class="form-control form-control-sm"
                               value="{{ $dateFrom }}">
                    </div>

                    <!-- Date To Filter -->
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                        <label for="date_to" class="form-label small mb-0">To</label>
                        <input type="date" name="date_to" id="date_to"
                               class="form-control form-control-sm"
                               value="{{ $dateTo }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary flex-grow-1">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div> --}}


            <div class="table-responsive">
                <table class="table table-striped table-hover table-head-bg-primary mt-4"  width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Name</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>PV</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->title ?? 'N/A' }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->amount }}</td>
                            <td>{{ $order->pv }}</td>
                            <td>{{ ucfirst($order->payment_method) }}</td>
                            <td>
                                @php
                                    $badgeClass = match($order->status) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                @if($order->status !== 'completed')
                                    <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm status-select d-inline" style="width:120px !important" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>🔵 Processing</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>🟢 Completed</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>🔴 Cancelled</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="badge bg-success">Completed</span>
                                @endif
                            </td>
                            {{-- <td>
                                @include('admin.pages.orders.partials.__actions')
                            </td> --}}
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No Order found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <div class="mt-4">
                {{ $orders->links('admin.layouts.partials.__pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .color-preview {
        display: inline-block;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #ddd;
    }
    .container form{
        width: 100% !important;
        background: none !important;
        border: none !important;
        margin-bottom: 0 !important;
    }
        @media (max-width: 576px) {
        .form-label {
            font-size: 0.75rem;
        }
        .btn-sm i {
            display: none;
        }
    }

    .align-items-end {
        align-items: flex-end;
    }
</style>