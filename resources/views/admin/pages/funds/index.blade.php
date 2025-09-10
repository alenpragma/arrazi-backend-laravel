@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Fund Distribution</h2>

    @include('admin.layouts.partials.__alerts')

    <div class="card mt-4" style="padding-bottom:0;">
        <div class="card-body d-flex" style="padding-bottom:0;">
            <h5 class="mt-3">
                Club Fund Amount:
                <span class="text-success">
                    {{ $funds ? $funds->amount : 0 }} ৳
                </span>
            </h5>

            <div>
                @if($funds && $funds->amount > 0)
                <form id="distributeForm" action="{{ route('admin.club.fund.distribution') }}" method="POST" style=" background: none; width:110px; padding:10px 15px; border:none;">
                    @csrf
                    <button type="submit" class="btn btn-primary" id="distributeBtn">Distribute</button>
                </form>
                @else
                    <button type="submit" class="btn btn-primary mx-4 mb-4" id="distributeBtn" disabled>Distribute</button>
                @endif
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('distributeForm');
    const btn = document.getElementById('distributeBtn');

    if(form && btn){
        form.addEventListener('submit', function(e){
            e.preventDefault();
            if(confirm("Are you sure you want to distribute the club fund to all members?")){
                form.submit();
            }
        });
    }
});
</script>
@endsection
