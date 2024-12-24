@extends('adminlte::page')

@section('title', 'Detail Pengajuan Asset')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Pengajuan Asset</h1>
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
                            <div class="form-group">
                                <label>Nama Asset</label>
                                <input type="text" class="form-control" value="{{ $pengajuan->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Kategori</label>
                                <input type="text" class="form-control" value="{{ $pengajuan->category }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Ruangan</label>
                                <input type="text" class="form-control" value="{{ $pengajuan->room ? $pengajuan->room->name : '-' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <input type="text" class="form-control" value="{{ $pengajuan->status }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Estimasi Biaya</label>
                                <input type="text" class="form-control" value="Rp {{ number_format($pengajuan->price, 0, ',', '.') }}" readonly>
                            </div>
                            @if($pengajuan->final_cost)
                            <div class="form-group">
                                <label>Biaya Aktual</label>
                                <input type="text" class="form-control" value="Rp {{ number_format($pengajuan->final_cost, 0, ',', '.') }}" readonly>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($pengajuan->proof_image)
                    <div class="form-group">
                        <label>Bukti Pembelian</label>
                        <div class="mt-2">
                            <img src="{{ Storage::url('proofs/' . $pengajuan->proof_image) }}" 
                                 alt="Bukti Pembelian" 
                                 class="img-fluid"
                                 style="max-width: 500px;">
                        </div>
                    </div>
                    @endif

                    @if($pengajuan->proof_description)
                    <div class="form-group">
                        <label>Keterangan Bukti</label>
                        <textarea class="form-control" rows="3" readonly>{{ $pengajuan->proof_description }}</textarea>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection 