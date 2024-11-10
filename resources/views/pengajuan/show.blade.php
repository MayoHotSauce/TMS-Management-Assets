@extends('adminlte::page')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Pengajuan Asset</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}">Pengajuan</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pengajuan</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px">ID Pengajuan</th>
                                    <td>{{ $assetRequest->id }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Asset</th>
                                    <td>{{ $assetRequest->name }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $assetRequest->category }}</td>
                                </tr>
                                <tr>
                                    <th>Harga</th>
                                    <td>Rp {{ number_format($assetRequest->price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($assetRequest->status == 'pending')
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif($assetRequest->status == 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <td>{{ $assetRequest->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Email Pemohon</th>
                                    <td>{{ $assetRequest->requester_email }}</td>
                                </tr>
                                <tr>
                                    <th>Email Approver</th>
                                    <td>{{ $assetRequest->approver_email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Deskripsi</h4>
                                </div>
                                <div class="card-body">
                                    {{ $assetRequest->description ?? 'Tidak ada deskripsi' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-default">Kembali</a>
                    
                    @if($assetRequest->status == 'pending' && auth()->user()->email == $assetRequest->approver_email)
                    <div class="float-right">
                        <form action="{{ route('pengajuan.update', $assetRequest->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </form>
                        <form action="{{ route('pengajuan.update', $assetRequest->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="declined">
                            <button type="submit" class="btn btn-danger">Tolak</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection 