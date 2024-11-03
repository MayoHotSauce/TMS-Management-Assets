@extends('adminlte::page')

@section('title', 'New Maintenance Log')

@section('content_header')
    <h1>New Maintenance Log</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="barang_id">Asset</label>
                <select name="barang_id" id="barang_id" class="form-control @error('barang_id') is-invalid @enderror" required>
                    <option value="">Select Asset</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">
                            {{ $asset->id }} - {{ $asset->description }}
                        </option>
                    @endforeach
                </select>
                @error('barang_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="maintenance_date">Maintenance Date</label>
                <input type="date" name="maintenance_date" id="maintenance_date" 
                    class="form-control @error('maintenance_date') is-invalid @enderror" 
                    required>
                @error('maintenance_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" 
                    class="form-control @error('description') is-invalid @enderror" 
                    rows="3" required></textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="cost">Cost</label>
                <input type="number" step="0.01" name="cost" id="cost" 
                    class="form-control @error('cost') is-invalid @enderror" 
                    required>
                @error('cost')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="performed_by">Performed By</label>
                <input type="text" name="performed_by" id="performed_by" 
                    class="form-control @error('performed_by') is-invalid @enderror" 
                    required>
                @error('performed_by')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="scheduled">Scheduled</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
                @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Create Maintenance Log</button>
        </form>
    </div>
</div>
@stop