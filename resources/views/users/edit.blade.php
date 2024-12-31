@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.update', $user->id_user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="member_id">Member ID</label>
                <input type="text" 
                       class="form-control @error('member_id') is-invalid @enderror" 
                       id="member_id" 
                       name="member_id" 
                       value="{{ old('member_id', $user->member_id) }}"
                       readonly>
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
                       value="{{ old('nama', $user->member->nama) }}"
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
                       value="{{ old('email', $user->member->email) }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password">
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
                    <option value="2" {{ $user->jabatan_id == 2 ? 'selected' : '' }}>Owner</option>
                    <option value="3" {{ $user->jabatan_id == 3 ? 'selected' : '' }}>Kadiv ACK</option>
                    <option value="4" {{ $user->jabatan_id == 4 ? 'selected' : '' }}>Direktur Keuangan</option>
                    <option value="5" {{ $user->jabatan_id == 5 ? 'selected' : '' }}>Staff SDM</option>
                    <option value="6" {{ $user->jabatan_id == 6 ? 'selected' : '' }}>MT Marketing</option>
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
                    <option value="1" {{ $user->divisi_id == 1 ? 'selected' : '' }}>RPU</option>
                    <option value="2" {{ $user->divisi_id == 2 ? 'selected' : '' }}>ACK</option>
                    <option value="3" {{ $user->divisi_id == 3 ? 'selected' : '' }}>SDM</option>
                    <option value="4" {{ $user->divisi_id == 4 ? 'selected' : '' }}>MPJ</option>
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
                    <option value="aktif" {{ $user->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak aktif" {{ $user->status == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@stop 