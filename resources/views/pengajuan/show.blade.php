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
                                    <td>{{ $pengajuan->id }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Asset</th>
                                    <td>{{ $pengajuan->name }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $pengajuan->category }}</td>
                                </tr>
                                <tr>
                                    <th>Harga</th>
                                    <td>Rp {{ number_format($pengajuan->price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($pengajuan->status == 'pending')
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif($pengajuan->status == 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <td>
                                        @if($pengajuan->created_at)
                                            {{ $pengajuan->created_at->format('d M Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pemohon</th>
                                    <td>{{ $pengajuan->requester_email }}</td>
                                </tr>
                                @if($pengajuan->notes)
                                <tr>
                                    <th>Catatan</th>
                                    <td>{{ $pengajuan->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Deskripsi</h4>
                                </div>
                                <div class="card-body">
                                    {{ $pengajuan->description ?? 'Tidak ada deskripsi' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-default">Kembali</a>
                    
                    @if($pengajuan->status == 'pending' && auth()->user()->email == $pengajuan->approver_email)
                    <div class="float-right">
                        <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </form>
                        <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" style="display: inline;">
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