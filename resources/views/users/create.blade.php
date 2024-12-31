@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content_header')
    <h1>Tambah User Baru</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="member_id">Member ID</label>
                <input type="text" 
                       class="form-control @error('member_id') is-invalid @enderror" 
                       id="member_id" 
                       name="member_id" 
                       value="{{ old('member_id') }}"
                       required>
                @error('member_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" 
                       class="form-control @error('nama') is-invalid @enderror" 
                       id="nama" 
                       name="nama" 
                       value="{{ old('nama') }}"
                       required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="jabatan_id">Jabatan</label>
                <select class="form-control @error('jabatan_id') is-invalid @enderror" 
                        id="jabatan_id" 
                        name="jabatan_id" 
                        required>
                    <option value="">Pilih Jabatan</option>
                    <option value="2">Owner</option>
                    <option value="3">Kadiv ACK</option>
                    <option value="4">Direktur Keuangan</option>
                    <option value="5">Staff SDM</option>
                    <option value="6">MT Marketing</option>
                </select>
                @error('jabatan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="divisi_id">Divisi</label>
                <select class="form-control @error('divisi_id') is-invalid @enderror" 
                        id="divisi_id" 
                        name="divisi_id" 
                        required>
                    <option value="">Pilih Divisi</option>
                    <option value="1">RPU</option>
                    <option value="2">ACK</option>
                    <option value="3">SDM</option>
                    <option value="4">MPJ</option>
                </select>
                @error('divisi_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control @error('status') is-invalid @enderror" 
                        id="status" 
                        name="status" 
                        required>
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Inisialisasi Select2 jika diperlukan
    $('#member_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih Member'
    });
});
</script>
@stop 