@extends('adminlte::page')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body text-center">
            <i class="fas fa-exclamation-circle text-danger" style="font-size: 48px;"></i>
            <h3 class="mt-3">Invalid or Expired Link</h3>
            <p>This approval link has already been used or is no longer valid.</p>
            <p class="text-muted">Each approval link can only be used once.</p>
        </div>
    </div>
</div>
@endsection 