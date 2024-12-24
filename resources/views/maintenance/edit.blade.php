@extends('adminlte::page')

@section('title', 'Edit Log Maintenance')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Log Maintenance</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('maintenance.update', $maintenance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="barang_id">ID Asset</label>
                <select name="barang_id" id="barang_id" class="form-control">
                    <option value="">Pilih Asset</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ $asset->id == $maintenance->barang_id ? 'selected' : '' }}>
                            {{ $asset->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <input type="text" name="description" class="form-control" value="{{ $maintenance->description }}">
            </div>

            <div class="form-group">
                <label for="maintenance_date">Tanggal</label>
                <input type="date" name="maintenance_date" class="form-control" value="{{ $maintenance->maintenance_date }}">
            </div>

            <div class="form-group">
                <label for="cost">Biaya</label>
                <input type="number" name="cost" class="form-control" value="{{ $maintenance->cost }}">
            </div>

            <div class="form-group">
                <label for="performed_by">Dikerjakan Oleh</label>
                <input type="text" name="performed_by" class="form-control" value="{{ $maintenance->performed_by }}">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="completed" {{ $maintenance->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="pending" {{ $maintenance->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="scheduled" {{ $maintenance->status == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Perbarui Log Maintenance</button>
            <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    // Add any JavaScript validation or enhancement here
</script>
@stop