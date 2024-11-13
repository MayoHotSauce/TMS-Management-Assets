@extends('adminlte::page')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body text-center">
            <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
            <h3 class="mt-3">Pilihan anda telah di rekap, Terima Kasih!</h3>
            <p>Asset ini sudah berhasil diajukan {{ session('status') }}.</p>
            <p class="text-muted">Link approval ini sudah di Dipakai.</p>
        </div>
    </div>
</div>
@endsection 
