@extends('adminlte::page')

@section('title', 'Buat Maintenance Baru')

@section('content_header')
    <h1>Buat Log Maintenance Baru</h1>
@stop

@section('content')
<div class="card">
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

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="barang_id">Asset</label>
                        <select name="barang_id" id="barang_id" class="form-control @error('barang_id') is-invalid @enderror" required>
                            <option value="">Pilih Asset</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" {{ old('barang_id') == $asset->id ? 'selected' : '' }}>
                                    {{ $asset->name }} ({{ $asset->asset_tag }})
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="maintenance_date">Tanggal Maintenance</label>
                        <input type="date" name="maintenance_date" id="maintenance_date" 
                            value="{{ old('maintenance_date') }}"
                            class="form-control @error('maintenance_date') is-invalid @enderror" required>
                        @error('maintenance_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="3" 
                    class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cost">Biaya</label>
                        <input type="number" name="cost" id="cost" 
                            value="{{ old('cost') }}"
                            class="form-control @error('cost') is-invalid @enderror" required>
                        @error('cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="performed_by">Dikerjakan Oleh</label>
                        <input type="text" name="performed_by" id="performed_by" 
                            value="{{ old('performed_by') }}"
                            class="form-control @error('performed_by') is-invalid @enderror" required>
                        @error('performed_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Buat Log Maintenance</button>
                <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@stop