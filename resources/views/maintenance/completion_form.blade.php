@extends('adminlte::page')

@section('title', 'Form Penyelesaian Maintenance')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Form Penyelesaian Maintenance</h3>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('maintenance.complete', $maintenance->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-secondary">Nama Asset</label>
                            <input type="text" class="form-control form-control-lg" value="{{ $maintenance->asset->name ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-secondary">Tanggal Selesai</label>
                            <input type="date" name="completion_date" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="text-secondary">Deskripsi Masalah</label>
                    <textarea class="form-control bg-light" rows="3" readonly>{{ $maintenance->description }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="text-secondary">Tindakan yang Dilakukan</label>
                    <textarea name="actions_taken" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="text-secondary">Hasil Perbaikan</label>
                    <textarea name="results" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="text-secondary">Part / Komponen yang Diganti</label>
                    <textarea name="replaced_parts" class="form-control" rows="3"></textarea>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-secondary">Total Biaya</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="total_cost" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-secondary">Status Barang</label>
                            <select name="equipment_status" class="form-control form-control-lg" required>
                                <option value="">Pilih Status</option>
                                <option value="berfungsi_100">Berfungsi 100%</option>
                                <option value="berfungsi_sebagian">Berfungsi Sebagian</option>
                                <option value="menunggu_komponen">Menunggu Komponen</option>
                                <option value="dalam_pemesanan">Komponen Dalam Pemesanan</option>
                                <option value="perlu_penggantian">Perlu Penggantian Komponen</option>
                                <option value="tidak_dapat_diperbaiki">Tidak Dapat Diperbaiki</option>
                                <option value="rusak_total">Rusak Total</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="text-secondary">Rekomendasi</label>
                    <textarea name="recommendations" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="text-secondary">Catatan Tambahan</label>
                    <textarea name="additional_notes" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="text-secondary">Teknisi yang Menangani</label>
                    <input type="text" name="technician_name" class="form-control" required>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('maintenance.index') }}" class="btn btn-secondary btn-lg px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-lg px-4 ml-2">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .card {
        border: none;
        border-radius: 8px;
    }
    .card-header {
        border-radius: 8px 8px 0 0;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
    .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    textarea {
        resize: none;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
    }
    .shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
    }
</style>
@stop