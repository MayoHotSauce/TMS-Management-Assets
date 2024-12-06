@extends('adminlte::page')

@section('title', 'Detail Perbaikan')

@section('content_header')
    <h1>Detail Perbaikan</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Asset</label>
                        <input type="text" class="form-control" value="{{ $maintenance->asset->name }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="text" class="form-control" value="{{ $maintenance->completion_date }}" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Masalah</label>
                <textarea class="form-control" rows="3" readonly>{{ $maintenance->description }}</textarea>
            </div>

            <div class="form-group">
                <label>Tindakan yang Dilakukan</label>
                <textarea class="form-control" rows="3" readonly>{{ $maintenance->actions_taken }}</textarea>
            </div>

            <div class="form-group">
                <label>Hasil Perbaikan</label>
                <textarea class="form-control" rows="3" readonly>{{ $maintenance->results }}</textarea>
            </div>

            <div class="form-group">
                <label>Part / Suku Cadang yang Diganti</label>
                <textarea class="form-control" rows="2" readonly>{{ $maintenance->replaced_parts ?: 'Tidak ada' }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Total Biaya</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control" value="{{ number_format($maintenance->total_cost, 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status Barang</label>
                        <input type="text" class="form-control" value="@switch($maintenance->equipment_status)
                            @case('fully_repaired')Sepenuhnya Diperbaiki & Siap Digunakan @break
                            @case('partially_repaired')Sebagian Diperbaiki & Kemungkinan Rusak @break
                            @case('needs_replacement')Perlu Penggantian @break
                            @case('beyond_repair')Rusak Total / Tidak Dapat Diperbaiki @break
                            @case('temporary_fix')Perbaikan Sementara @break
                            @case('pending_parts')Menunggu Suku Cadang @break
                            @default{{ $maintenance->equipment_status }}@endswitch" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Rekomendasi Perawatan Selanjutnya</label>
                <textarea class="form-control" rows="3" readonly>{{ $maintenance->recommendations ?: 'Tidak ada' }}</textarea>
            </div>

            <div class="form-group">
                <label>Catatan Tambahan</label>
                <textarea class="form-control" rows="2" readonly>{{ $maintenance->additional_notes ?: 'Tidak ada' }}</textarea>
            </div>
        </div>
    </div>
@endsection 