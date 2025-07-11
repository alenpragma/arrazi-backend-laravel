@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">👤 User Details — {{ $user->name }}</h4>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6"><strong>ID:</strong> {{ $user->id }}</div>
                <div class="col-md-6"><strong>Email:</strong> {{ $user->email }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Refer Code:</strong> <span class="badge bg-info text-dark">{{ $user->refer_code }}</span></div>
                <div class="col-md-6"><strong>Referred By:</strong> {{ $user->referer->name ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Dealer:</strong> {{ $user->dealer }}</div>
                <div class="col-md-6"><strong>Role:</strong> <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Position:</strong> {{ ucfirst($user->position) }}</div>
                <div class="col-md-6"><strong>Joined:</strong> {{ optional($user->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
            </div>

            <hr>

            <h5 class="text-primary">💼 Wallet & Points</h5>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Shopping Wallet:</strong> {{ $user->shopping_wallet }}</div>
                <div class="col-md-4"><strong>Income Wallet:</strong> {{ $user->income_wallet }}</div>
                <div class="col-md-4"><strong>Points:</strong> {{ $user->points }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Left Points:</strong> {{ $user->left_points }}</div>
                <div class="col-md-4"><strong>Right Points:</strong> {{ $user->right_points }}</div>
                <div class="col-md-4"><strong>Total Left Points:</strong> {{ $totalLeftPoints }}</div>
                <div class="col-md-4"><strong>Total Right Points:</strong> {{ $totalRightPoints }}</div>
            </div>

            <hr>

            <h5 class="text-primary">🔗 Direct Links</h5>
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item">Upline: {{ $user->upline->name ?? '-' }}</li>
                <li class="list-group-item">Left: {{ $user->left->name ?? '-' }}</li>
                <li class="list-group-item">Right: {{ $user->right->name ?? '-' }}</li>
            </ul>

            <hr>

            <h5 class="text-primary">📋 First 20 Downline Users</h5>
            <ul class="list-group">
                @forelse($downlines as $downline)
                    <li class="list-group-item">
                        <strong>{{ $downline['name'] }}</strong> ({{ $downline['email'] }})
                        <br>
                        <span class="badge bg-success">{{ ucfirst($downline['position']) }}</span>
                        — {{ $downline['points'] }} pts
                    </li>
                @empty
                    <li class="list-group-item text-muted">No downline users found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
