@extends('adminlte::page')

@section('title', 'Role Permissions')

@section('content_header')
<div class="d-flex justify-content-between">
    <h1>Daftar Jabatan dan Akses</h1>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addRoleModal">
        <i class="fas fa-plus"></i> Tambah Izin Akses
    </button>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#userAccess">Akses User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#jabatanAccess">Akses Jabatan</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content pt-3">
            <!-- User Access Tab -->
            <div class="tab-pane active" id="userAccess">
                <div class="form-group">
                    <label>Filter User:</label>
                    <input type="text" class="form-control" id="searchUser" placeholder="Cari username atau nama...">
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Username / Nama</th>
                                <th>Level Akses</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($userRoles as $role)
                            @php
                                // Parse role name untuk mendapatkan user ID dan level
                                preg_match('/user_(\d+)_level_(\d+)/', $role->name, $matches);
                                $userId = $matches[1] ?? null;
                                $level = $matches[2] ?? null;
                                
                                // Cari user berdasarkan ID
                                $user = $users->firstWhere('id_user', $userId);
                                $userName = $user ? ($user->member->nama ?? 'User #' . $userId) : 'User #' . $userId;
                            @endphp
                            <tr class="user-role-row">
                                <td class="user-name">{{ $userName }}</td>
                                <td>
                                    @if($level == 1)
                                        <span class="badge badge-info">Level 1: Hanya lihat</span>
                                    @elseif($level == 2)
                                        <span class="badge badge-warning">Level 2: Lihat dan edit</span>
                                    @elseif($level == 3)
                                        <span class="badge badge-success">Level 3: Akses penuh</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-warning edit-level" 
                                                data-role-id="{{ $role->id }}"
                                                data-current-level="{{ $level }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-role" 
                                                data-role-id="{{ $role->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada data akses user</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Jabatan Access Tab -->
            <div class="tab-pane fade" id="jabatanAccess">
                <!-- Similar structure as user access but for jabatan -->
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
@include('roleperm.modal')

@stop

@push('css')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function() {
    // Live search untuk user
    $('#searchUser').on('keyup', function() {
        const searchText = $(this).val().toLowerCase();
        $('.user-role-row').each(function() {
            const userName = $(this).find('.user-name').text().toLowerCase();
            $(this).toggle(userName.includes(searchText));
        });
    });

    // Handler untuk edit level
    $('.edit-level').on('click', function() {
        const roleId = $(this).data('role-id');
        const currentLevel = $(this).data('current-level');
        
        Swal.fire({
            title: 'Ubah Level Akses',
            input: 'select',
            inputOptions: {
                '1': 'Level 1: Hanya lihat',
                '2': 'Level 2: Lihat dan edit',
                '3': 'Level 3: Akses penuh'
            },
            inputValue: currentLevel,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/role-permissions/${roleId}/level`,
                    type: 'PUT',
                    data: { level: result.value },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal mengubah level akses!'
                        });
                    }
                });
            }
        });
    });

    // Handler untuk delete
    $('.delete-role').on('click', function() {
        const roleId = $(this).data('role-id');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Akses ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/role-permissions/${roleId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal menghapus akses!'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush 