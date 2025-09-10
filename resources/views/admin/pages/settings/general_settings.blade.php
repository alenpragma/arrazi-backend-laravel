@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>General Settings</h2>

    @include('admin.layouts.partials.__alerts')

    <form action="{{ route('admin.general.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <div class="row">
            <!-- Left Sidebar Navigation -->
            <div class="col-md-4">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-stock-tab" data-bs-toggle="pill" data-bs-target="#v-pills-stock" type="button" role="tab" aria-controls="v-pills-stock" aria-selected="true">
                        <i class="fas fa-box me-2"></i> Stock Settings
                    </button>
                    <button class="nav-link" id="v-pills-club-tab" data-bs-toggle="pill" data-bs-target="#v-pills-club" type="button" role="tab" aria-controls="v-pills-club" aria-selected="false">
                        <i class="fas fa-star me-2"></i> Club Settings
                    </button>
                    <button class="nav-link" id="v-pills-pv-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-pv" type="button" role="tab"
                        aria-controls="v-pills-pv" aria-selected="false">
                        <i class="fas fa-coins me-2"></i> PV Settings
                    </button>
                    <button class="nav-link" id="v-pills-withdraw-tab" data-bs-toggle="pill" data-bs-target="#v-pills-withdraw" type="button" role="tab" aria-controls="v-pills-withdraw" aria-selected="false">
                        <i class="fas fa-money-bill-wave me-2"></i> Withdraw Settings
                    </button>
                    <button class="nav-link" id="v-pills-app-tab" data-bs-toggle="pill" data-bs-target="#v-pills-app" type="button" role="tab" aria-controls="v-pills-app" aria-selected="false">
                        <i class="fas fa-cog me-2"></i> App Settings
                    </button>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="col-md-8">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- Stock Settings Tab -->
                    <div class="tab-pane fade show active" id="v-pills-stock" role="tabpanel" aria-labelledby="v-pills-stock-tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-box me-2"></i>Stock Settings</h5>
                                <div class="mb-3">
                                    <label for="max_stock_per_user">Max. Stock Buy per User</label>
                                    <input type="number" step="0.01" id="max_stock_per_user" name="max_stock_per_user"
                                           value="{{ old('max_stock_per_user', $generalSettings->max_stock_per_user) }}"
                                           required class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Club Settings Tab -->
                    <div class="tab-pane fade" id="v-pills-club" role="tabpanel" aria-labelledby="v-pills-club-tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-star me-2"></i> Club Settings</h5>

                                <div class="mb-3">
                                    <label for="club_required_pv">Required PV</label>
                                    <input type="number" step="0.01" id="club_required_pv" name="club_required_pv"
                                        value="{{ old('club_required_pv', $generalSettings->club_required_pv ?? 1000) }}"
                                        required class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="club_image">Club Badge</label>
                                    <input type="file" id="club_image" name="club_image" class="form-control">
                                    @if(isset($generalSettings->club_image))
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $generalSettings->club_image) }}"
                                                alt="Club Image"
                                                style="max-width: 50px; max-height: 50px;">
                                            <span class="ms-2">Current image</span>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- PV Settings Tab -->
                    <div class="tab-pane fade" id="v-pills-pv" role="tabpanel" aria-labelledby="v-pills-pv-tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-coins me-2"></i> PV Settings</h5>
                                <div class="mb-3 d-flex align-items-center" style="gap: 10px;">
                                    <label for="pv_value" class="form-label mb-0" style="white-space: nowrap;">
                                        1 PV =
                                    </label>
                                    <div class="input-group" style="max-width: 200px;">
                                        <input type="number" step="0.01" id="pv_value" name="pv_value"
                                            value="{{ old('pv_value', $generalSettings->pv_value ?? 2) }}"
                                            required class="form-control">
                                        <span class="input-group-text">$</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Withdraw Settings Tab -->
                    <div class="tab-pane fade" id="v-pills-withdraw" role="tabpanel" aria-labelledby="v-pills-withdraw-tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-money-bill-wave me-2"></i>Withdraw Settings</h5>
                                <div class="mb-3">
                                    <label for="withdraw_shopping_wallet_percentage">Withdraw to Shopping Wallet (%)</label>
                                    <input type="number" step="0.01" id="withdraw_shopping_wallet_percentage"
                                           name="withdraw_shopping_wallet_percentage"
                                           value="{{ old('withdraw_shopping_wallet_percentage', $generalSettings->withdraw_shopping_wallet_percentage) }}"
                                           required class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="withdraw_charge">Withdraw Charge (%)</label>
                                    <input type="number" step="0.01" id="withdraw_charge" name="withdraw_charge"
                                           value="{{ old('withdraw_charge', $generalSettings->withdraw_charge) }}"
                                           required class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- App Settings Tab -->
                    <div class="tab-pane fade" id="v-pills-app" role="tabpanel" aria-labelledby="v-pills-app-tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-cog me-2"></i>App Settings</h5>
                                <div class="mb-3">
                                    <label for="app_name">App Name</label>
                                    <input type="text" id="app_name" name="app_name"
                                           value="{{ old('app_name', $generalSettings->app_name) }}"
                                           required class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="favicon">Favicon (200x200px)</label>
                                    <input type="file" id="favicon" name="favicon" class="form-control">
                                    @if(isset($generalSettings->favicon))
                                        <div class="mt-2">
                                            <img src="{{ asset('public/storage/' . $generalSettings->favicon) }}"
                                                 alt="Current Favicon"
                                                 style="max-width: 32px; max-height: 32px;">
                                            <span class="ms-2">Current favicon</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="logo">Logo (300x45px)</label>
                                    <input type="file" id="logo" name="logo" class="form-control">
                                    @if(isset($generalSettings->logo))
                                        <div class="mt-2">
                                            <img src="{{ asset('public/storage/' . $generalSettings->logo) }}"
                                                 alt="Current Logo"
                                                 style="max-width: 300px; max-height: 45px;">
                                            <span class="ms-2">Current logo</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button (outside tabs but inside the right content area) -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Bootstrap Tab JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tabElms = [].slice.call(document.querySelectorAll('button[data-bs-toggle="pill"]'));
        tabElms.forEach(function(tabElm) {
            tabElm.addEventListener('click', function (event) {
                event.preventDefault();
                var tab = new bootstrap.Tab(tabElm);
                tab.show();
            });
        });
    });
</script>

<style>
    form{
        width: 70% !important;
    }
    .nav-pills .nav-link {
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
        text-align: left;
        padding: 0.75rem 1rem;
        color: #495057;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: white;
    }

    .nav-pills .nav-link:hover:not(.active) {
        background-color: #f8f9fa;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-title {
        color: #343a40;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #eee;
    }
</style>
@endsection
