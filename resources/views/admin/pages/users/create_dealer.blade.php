@extends('admin.layouts.app')

@php
$allDealers = \App\Models\User::all(['id','name','email','refer_by','position', 'password']);
@endphp

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create / Assign Dealer</h1>
    </div>

    @include('admin.layouts.partials.__alerts')

    <div class="card">
        <div class="card-header">
            <h5>Search Dealer by Email / Name</h5>
        </div>
        <div class="card-body">

            <div class="mb-3 position-relative" style="width: 400px; margin: auto;">
                <label for="">Search Dealer by Email / Name</label>
                <input type="text" id="search-dealer" class="form-control" placeholder="Type email or name">
                <div id="dealer-suggestions" class="position-absolute bg-white border" style="z-index:1000;width:100%;display:none;"></div>
            </div>

            <form id="dealer-form" action="{{ route('admin.dealers.store') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label>Name</label>
                    <input type="text" name="name" id="dealer_name" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" name="email" id="dealer_email" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Refer By</label>
                    <input type="text" name="refer_code" id="dealer_refer_by" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Password</label>
                    <input type="password" name="password" id="dealer_password" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Option</label>
                    <select name="position" id="dealer_option" class="form-control">
                        <option value="Left">Left</option>
                        <option value="Right">Right</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Role</label>
                    <input type="text" name="role" value="dealer" class="form-control" readonly>
                </div>

                <button type="submit" class="btn btn-primary">Save / Assign Dealer</button>
            </form>
        </div>
    </div>
</div>
@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function(){
        const allDealers = @json($allDealers);
        const $suggestions = $('#dealer-suggestions');

        function filterDealers(query) {
            query = query.toLowerCase().trim();
            if(!query) return [];
            return allDealers.filter(u =>
                u.email.toLowerCase().includes(query) || u.name.toLowerCase().includes(query)
            );
        }

        $('#search-dealer').on('input', function(){
            let query = $(this).val();
            let matches = filterDealers(query);

            $suggestions.empty();
            if(matches.length === 0){
                $suggestions.append(`<div class="p-2 text-muted">No dealer found. A new dealer will be created.</div>`);
            } else {
                matches.forEach(user => {
                    const userData = btoa(JSON.stringify(user));
                    $suggestions.append(`
                        <div class="p-2 border-bottom suggestion-item" data-user='${userData}'>
                            ${user.name} (${user.email})
                        </div>
                    `);
                });
            }
            $suggestions.show();
        });

        $(document).on('click', '.suggestion-item', function(){
            let user = JSON.parse(atob($(this).attr('data-user')));

            $('#dealer_name').val(user.name).prop('readonly', true);
            $('#dealer_email').val(user.email).prop('readonly', true);
            $('#dealer_option').val(user.position ?? '').prop('disabled', true);

            if (user.refer_by) {
                let referUser = allDealers.find(u => u.id === user.refer_by);
                if (referUser) {
                    $('#dealer_refer_by').val(referUser.name).prop('readonly', true);
                } else {
                    $('#dealer_refer_by').val('N/A').prop('readonly', true);
                }
            } else {
                $('#dealer_refer_by').val('N/A').prop('readonly', true);
            }

            $('#dealer_password').val('').prop('disabled', true);

            $suggestions.hide();
        });

        $(document).click(function(e){
            if(!$(e.target).closest('#dealer-suggestions,#search-dealer').length){
                $suggestions.hide();
            }
        });
    });
</script>


<style>
#dealer-suggestions {
    position: absolute;
    z-index: 1000;
    width: 100%;
    max-width: 500px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: none;
}
.suggestion-item {
    cursor: pointer;
}
.suggestion-item:hover {
    background-color: #f0f0f0;
}
</style>
