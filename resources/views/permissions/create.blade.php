@extends('adminlte::page')

@section('title', 'Tambah Permission')

@section('content_header')
    <h1>Tambah Permission Baru</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Permission</label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       placeholder="Contoh: edit posts"
                       required>
                <small class="form-text text-muted">
                    Gunakan format kata kerja + objek (contoh: create users, edit posts, delete comments)
                </small>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Auto lowercase input
    $('#name').on('input', function() {
        this.value = this.value.toLowerCase();
    });
});
</script>
@stop 