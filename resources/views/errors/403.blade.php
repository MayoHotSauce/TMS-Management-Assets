@extends('adminlte::page')

@section('title', '403 Forbidden')

@section('content_header')
@stop

@section('content')
<div class="error-page">
    <div class="d-flex align-items-center justify-content-center min-vh-75">
        <div class="text-center">
            <h2 class="headline text-warning"> 403</h2>
            <div class="error-content mt-4">
                <h3>
                    <i class="fas fa-exclamation-triangle text-warning"></i> 
                    Oops! Akses Ditolak.
                </h3>
                <p class="mt-3">
                    Anda tidak memiliki izin untuk mengakses halaman ini.
                    Silahkan <a href="{{ route('dashboard') }}">kembali ke dashboard</a> atau hubungi administrator.
                </p>
                <div class="mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-warning">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary ml-2">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .error-page {
        margin: 20px auto 0;
        width: 100%;
    }
    .error-page > .headline {
        float: left;
        font-size: 100px;
        font-weight: 300;
    }
    .error-page > .error-content {
        margin-left: 190px;
    }
    .error-page > .error-content > h3 {
        font-weight: 300;
        font-size: 25px;
    }
    .min-vh-75 {
        min-height: 75vh;
    }
    @media (max-width: 767px) {
        .error-page > .headline {
            float: none;
            text-align: center;
        }
        .error-page > .error-content {
            margin-left: 0;
            text-align: center;
        }
    }
</style>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('403 page loaded');
</script>
@stop 