@extends('adminlte::page')

@section('title', 'Manajemen Peran & Izin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Jabatan</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRoleModal">
                <i class="fas fa-plus"></i> Tambah Izin Akses
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Jabatan</th>
                    <th>Level Akses</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jabatans as $jabatan)
                    <tr>
                        <td>{{ $jabatan->nama }}</td>
                        <td>
                            @php
                                $roleName = 'jabatan_' . $jabatan->id_jabatan;
                                $role = \Spatie\Permission\Models\Role::where('name', $roleName)->first();
                                $level = $role ? substr($role->name, -1) : 'Tidak ada izin';
                            @endphp
                            @if($level === 'Tidak ada izin')
                                <span class="badge badge-secondary">{{ $level }}</span>
                            @else
                                <span class="badge badge-info">Level {{ $level }}</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" 
                                    onclick="editAccess('{{ $jabatan->id_jabatan }}', '{{ $jabatan->nama }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Izin Akses</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('roleperm.assign') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Tipe</label>
                        <select name="permission_type" class="form-control" required>
                            <option value="jabatan">Untuk Jabatan</option>
                            <option value="specific">Untuk User Spesifik</option>
                        </select>
                    </div>

                    <div class="form-group jabatan-select">
                        <label>Pilih Jabatan</label>
                        <select name="jabatan_id" class="form-control">
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatans as $jabatan)
                                <option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Level Akses</label>
                        <select name="access_level" class="form-control" required>
                            <option value="">Pilih Level</option>
                            <option value="1">Level 1 - Hanya melihat data</option>
                            <option value="2">Level 2 - Dapat membuat dan mengedit</option>
                            <option value="3">Level 3 - Akses penuh termasuk hapus dan approval</option>
                        </select>
                        <small class="text-muted">
                            Level 1: Hanya melihat data<br>
                            Level 2: Dapat membuat dan mengedit<br>
                            Level 3: Akses penuh termasuk hapus dan approval
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function() {
    $('select[name="permission_type"]').change(function() {
        const type = $(this).val();
        if (type === 'jabatan') {
            $('.jabatan-select').show();
            $('.user-select').hide();
        } else {
            $('.jabatan-select').hide();
            $('.user-select').show();
        }
    });
});

function editAccess(jabatanId, jabatanName) {
    $('#addRoleModal').modal('show');
    $('select[name="jabatan_id"]').val(jabatanId);
    $('select[name="permission_type"]').val('jabatan').trigger('change');
}
</script>
@endpush
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable with check
        if ($.fn.DataTable) {
            $('.table').DataTable();
            console.log('DataTable initialized');
        } else {
            console.error('DataTable plugin not found');
        }

        // Debug initial state
        console.log('jQuery version:', $.fn.jquery);
        console.log('Initial permission type:', $('#permissionType').val());
        console.log('Initial jabatan:', $('#jabatanSelect').val());

        // Permission type change handler
        $('#permissionType').on('change', function() {
            var selectedValue = $(this).val();
            console.log('Permission type changed to:', selectedValue);
            
            if (selectedValue === 'specific') {
                var jabatanValue = $('#jabatanSelect').val();
                $('#userSelect').show();
                
                if (jabatanValue) {
                    loadUsers(jabatanValue);
                } else {
                    console.log('Please select a jabatan first');
                }
            } else {
                $('#userSelect').hide();
            }
        });

        // Jabatan change handler
        $('#jabatanSelect').on('change', function() {
            var jabatanValue = $(this).val();
            console.log('Jabatan changed to:', jabatanValue);
            
            if ($('#permissionType').val() === 'specific') {
                loadUsers(jabatanValue);
            }
        });

        // Load users function
        function loadUsers(jabatanId) {
            if (!jabatanId) {
                console.log('No jabatan ID provided');
                return;
            }

            console.log('Loading users for jabatan:', jabatanId);
            
            $.ajax({
                url: '/get-users-by-jabatan/' + jabatanId,
                type: 'GET',
                beforeSend: function() {
                    $('#userDropdown').prop('disabled', true);
                    console.log('Loading users...');
                },
                success: function(response) {
                    var dropdown = $('#userDropdown');
                    
                    if (!dropdown.length) {
                        console.error('User dropdown not found');
                        return;
                    }
                    
                    dropdown.empty().prop('disabled', false);
                    dropdown.append('<option value="">Pilih Pengguna</option>');
                    
                    if (response.users && response.users.length > 0) {
                        console.log('Found', response.users.length, 'users');
                        $.each(response.users, function(key, user) {
                            var userName = user.member ? user.member.nama : 'N/A';
                            dropdown.append(
                                $('<option></option>')
                                    .val(user.id_user)
                                    .text(userName)
                            );
                        });
                    } else {
                        dropdown.append('<option value="">Tidak ada pengguna</option>');
                        console.log('No users found for this jabatan');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    $('#userDropdown')
                        .empty()
                        .prop('disabled', false)
                        .append('<option value="">Error loading users</option>');
                }
            });
        }
    });
    </script>
@stop 