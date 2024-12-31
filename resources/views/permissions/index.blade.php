@extends('adminlte::page')

@section('title', 'Manajemen Permission')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Manajemen Permission</h1>
        <a href="{{ route('permissions.create') }}" class="btn btn-primary">Tambah Permission</a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Permission</th>
                        <th>Guard Name</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td><span class="badge badge-info">{{ $permission->guard_name }}</span></td>
                            <td>{{ $permission->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" 
                                            class="btn btn-sm btn-info" 
                                            data-toggle="modal" 
                                            data-target="#editModal{{ $permission->id }}"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if(!in_array($permission->name, ['manage users', 'manage roles', 'manage permissions']))
                                        <form action="{{ route('permissions.destroy', $permission) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus permission ini?');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $permission->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $permission->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('permissions.update', $permission) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $permission->id }}">Edit Permission</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="name{{ $permission->id }}">Nama Permission</label>
                                                        <input type="text" 
                                                               class="form-control" 
                                                               id="name{{ $permission->id }}" 
                                                               name="name" 
                                                               value="{{ $permission->name }}"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .btn-group {
        gap: 5px;
    }
</style>
@stop 