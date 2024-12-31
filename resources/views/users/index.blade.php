@extends('adminlte::page')

@section('title', 'Manajemen User')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Manajemen User</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah User</a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Divisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->member_id }}</td>
                            <td>{{ $user->member->nama ?? '-' }}</td>
                            <td>{{ $user->member->email ?? '-' }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $user->jabatan->nama ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ $user->divisi->nama ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if($user->status === 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('users.edit', $user->id_user) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-warning" 
                                            title="Manage Role"
                                            data-toggle="modal" 
                                            data-target="#roleModal{{ $user->id_user }}">
                                        <i class="fas fa-user-tag"></i>
                                    </button>
                                    <form action="{{ route('users.destroy', $user->id_user) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Manage Role -->
                                <div class="modal fade" id="roleModal{{ $user->id_user }}" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel{{ $user->id_user }}">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="roleModalLabel{{ $user->id_user }}">
                                                    Manage Role - {{ $user->member->nama }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="roleForm{{ $user->id_user }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="d-block">Pilih Role:</label>
                                                        <div class="role-options">
                                                            @foreach($roles as $role)
                                                                <div class="custom-control custom-checkbox mb-2">
                                                                    <input type="checkbox" 
                                                                           class="custom-control-input" 
                                                                           id="role{{ $role->id }}{{ $user->id_user }}" 
                                                                           name="roles[]"
                                                                           value="{{ $role->name }}"
                                                                           {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="role{{ $role->id }}{{ $user->id_user }}">
                                                                        {{ ucfirst($role->name) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                <button type="button" class="btn btn-primary save-roles" data-user-id="{{ $user->id_user }}">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
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
    .badge {
        font-size: 90%;
    }
    .role-options {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Inisialisasi tooltip
    $('[title]').tooltip();

    // Handle save roles
    $('.save-roles').click(function() {
        const userId = $(this).data('user-id');
        const roles = [];
        const form = $(`#roleForm${userId}`);
        
        form.find('input[type="checkbox"]:checked').each(function() {
            roles.push($(this).val());
        });

        // Tampilkan loading state
        const button = $(this);
        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        button.prop('disabled', true);

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            url: `/users/${userId}/roles`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                roles: roles
            },
            success: function(response) {
                if (response.success) {
                    // Tampilkan pesan sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Refresh halaman
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat menyimpan role';
                
                if (xhr.status === 403) {
                    message = 'Anda tidak memiliki izin untuk mengubah role';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message
                });
            },
            complete: function() {
                // Kembalikan button ke state awal
                button.html(originalText);
                button.prop('disabled', false);
            }
        });
    });
});
</script>
@stop 