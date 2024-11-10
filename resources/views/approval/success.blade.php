@extends('adminlte::page')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body text-center">
            <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
            <h3 class="mt-3">Action Completed Successfully</h3>
            <p>The asset request has been {{ session('status') }}.</p>
            <p class="text-muted">This approval link has been used and cannot be accessed again.</p>
        </div>
    </div>
</div>
@endsection 
