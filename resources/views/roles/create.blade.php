@extends('adminlte::page')

@section('title', 'Tambah Role')

@section('content_header')
    <h1>Tambah Role Baru</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Role</label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Permissions</label>
                <div class="permissions-options">
                    @foreach($permissions as $permission)
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" 
                                   class="custom-control-input" 
                                   id="permission{{ $permission->id }}" 
                                   name="permissions[]" 
                                   value="{{ $permission->name }}"
                                   {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="permission{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@stop 