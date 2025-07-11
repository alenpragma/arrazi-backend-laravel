		<!-- Sidebar -->
		<div class="sidebar" data-background-color="dark">
			<div class="sidebar-logo">
				<!-- Logo Header -->
				<div class="logo-header" data-background-color="dark">

					<a href="{{route('admin.dashboard')}}" class="logo">
                        @if($generalSettings->logo)
                        <img src="{{ Storage::url($generalSettings->logo) }}" alt="{{ $generalSettings->app_name }}" class="navbar-brand" height="20">
                    @else
                        <h1>{{ $generalSettings->app_name ?? 'App Name' }}</h1>
                    @endif
					</a>
					<div class="nav-toggle">
						<button class="btn btn-toggle toggle-sidebar">
							<i class="gg-menu-right"></i>
						</button>
						<button class="btn btn-toggle sidenav-toggler">
							<i class="gg-menu-left"></i>
						</button>
					</div>
					<button class="topbar-toggler more">
						<i class="gg-more-vertical-alt"></i>
					</button>

				</div>
				<!-- End Logo Header -->
			</div>
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<ul class="nav nav-secondary">
						<li class="nav-item active">
							<a href="{{route('admin.dashboard')}}">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('admin.users.index') }}">
								<i class="fas fa-users"></i>
								<p>Users</p>
								{{-- <span class="caret"></span> --}}
							</a>
							{{-- <div class="collapse" id="users">
								<ul class="nav nav-collapse">
									<li>
										<a href="">
											<span class="sub-item">All Users</span>
										</a>
									</li>
									<li>
										<a href="">
											<span class="sub-item">Active Users</span>
										</a>
									</li>
									<li>
										<a href="">
											<span class="sub-item">Inactive Users</span>
										</a>
									</li>
                                    <li>
										<a href="">
											<span class="sub-item">Block Users</span>
										</a>
									</li>
                                    <li>
										<a href="">
											<span class="sub-item">Wallet Block Users</span>
										</a>
									</li>

								</ul>
							</div> --}}
						</li>
                        <li class="nav-item">
							<a href="{{route('admin.dealers.list')}}">
								<i class="fas fa-user-tie"></i>
								<p>Dealer</p>
							</a>
						</li>
                        <li class="nav-item">
							<a data-bs-toggle="collapse" href="#deposit">
								<i class="far fa-money-bill-alt"></i>
								<p>Deposit <span class="text-danger">🔴({{ $pendingDepositCount ?? 0 }})</span></span></p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="deposit">
								<ul class="nav nav-collapse">
                                    <li>
                                        <a href="{{route('admin.deposits.pending')}}">
                                            <span class="sub-item">Pending Deposits<span class="text-danger">🔴({{ $pendingDepositCount ?? 0 }})</span></span>
                                        </a>
                                    </li>
                                    <li>
										<a href="{{route('admin.deposit')}}">
											<span class="sub-item">Deposit History</span>
										</a>
									</li>
                                    <li>
                                        <a href="{{route('admin.deposits.rejected')}}">
                                            <span class="sub-item">Rejected Deposits</span>
                                        </a>
                                    </li>
								</ul>
							</div>
						</li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#orders">
                                <i class="fas fa-users"></i>
                                <p>Orders</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="orders">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="{{ route('admin.orders.index') }}">
                                            <span class="sub-item">All Orders</span>
                                        </a>
                                    </li>
                                    @php
                                        $statuses = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                    @endphp

                                    @foreach($statuses as $key => $badge)
                                        <li class="nav-item">
                                            <a href="{{ route('admin.orders.index', ['status' => $key]) }}" class="nav-link">
                                                <span class="sub-item">{{ ucfirst($key) }} Orders</span>
                                                <span class="badge badge-{{ $badge }} float-right">
                                                    {{ \App\Models\Order::where('status', $key)->count() }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>


                        <li class="nav-item">
							<a data-bs-toggle="collapse" href="#products">
								<i class="fas fa-boxes"></i>
								<p>Products</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="products">
								<ul class="nav nav-collapse">
                                    <li>
										<a href="{{route('admin.products.index')}}">
											<span class="sub-item">All Products</span>
										</a>
									</li>
									<li>
										<a href="{{route('admin.products.create')}}">
											<span class="sub-item">Add Products</span>
										</a>
									</li>
								</ul>
							</div>
						</li>

                        <li class="nav-item">
							<a href="{{route('admin.stocks.index')}}">
								<i class="fas fa-tasks"></i>
								<p>Stock Buyer</p>
							</a>
						</li>

                        <li class="nav-item">
							<a data-bs-toggle="collapse" href="#withdraw">
								<i class="far fa-money-bill-alt"></i>
								<p>Withdraw <span class="text-danger">🔴({{ $pendingWithdrawCount?? 0 }})</span></span></p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="withdraw">
								<ul class="nav nav-collapse">
                                    <li>
                                        <a href="{{route('admin.withdraws.pending')}}">
                                            <span class="sub-item">Pending Withdraw<span class="text-danger">🔴({{ $pendingWithdrawCount?? 0 }})</span></span>
                                        </a>
                                    </li>
                                    <li>
										<a href="{{route('admin.withdraw')}}">
											<span class="sub-item">Withdraw History</span>
										</a>
									</li>
                                    <li>
                                        <a href="{{route('admin.withdraws.rejected')}}">
                                            <span class="sub-item">Rejected Withdraw</span>
                                        </a>
                                    </li>
								</ul>
							</div>
						</li>
                        {{-- <li class="nav-item">
							<a href="">
								<i class="fas fa-tasks"></i>
								<p>Transactions History</p>
							</a>
						</li> --}}
                        <li class="nav-item">
							<a data-bs-toggle="collapse" href="#settings">
								<i class="fas fa-cog"></i>
								<p>Settings</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="settings">
								<ul class="nav nav-collapse">
                                    <li>
										<a href="{{route('admin.general.settings')}}">
											<span class="sub-item">General Settings</span>
										</a>
									</li>
									{{-- <li>
										<a href="">
											<span class="sub-item">Referral Settings</span>
										</a>
									</li> --}}
								</ul>
							</div>
						</li>
                        {{-- <li class="nav-item">
							<a href="{{route('admin.broadcast')}}">
								<i class="fas fa-tasks"></i>
								<p>Broadcast</p>
							</a>
						</li> --}}
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->