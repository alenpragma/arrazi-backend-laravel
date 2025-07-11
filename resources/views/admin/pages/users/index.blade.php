@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">All Users</div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3 mt-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by name, email or phone" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover table-head-bg-primary mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Refer Code</th>
                    <th scope="col">Refer By</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->refer_code }}</td>
                    <td>{{ $user->referer->refer_code }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
                 @empty
                        <tr>
                            <td colspan="7" class="text-center">No user found</td>
                        </tr>
                @endforelse
            </tbody>
        </table>
        {{ $users->links('admin.layouts.partials.__pagination') }}
    </div>
</div>

@endsection
