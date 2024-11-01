@extends('adminlte::page')

@section('title', 'Add New Asset')

@section('content_header')
    <h1>Add New Asset</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control @error('description') is-invalid @enderror" 
                       id="description" name="description" value="{{ old('description') }}" required>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="room">Room</label>
                <input type="text" class="form-control @error('room') is-invalid @enderror" 
                       id="room" name="room" value="{{ old('room') }}" required>
                @error('room')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control @error('category_id') is-invalid @enderror" 
                        id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="tahun_pengadaan">Year of Procurement</label>
                <input type="number" class="form-control @error('tahun_pengadaan') is-invalid @enderror" 
                       id="tahun_pengadaan" name="tahun_pengadaan" value="{{ old('tahun_pengadaan', date('Y')) }}" required>
                @error('tahun_pengadaan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save Asset</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@stop