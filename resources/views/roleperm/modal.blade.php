<!-- Modal untuk tambah/edit role -->
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Izin Akses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="roleForm">
                    <!-- Tipe Akses -->
                    <div class="mb-3">
                        <label class="form-label">Pilih Tipe</label>
                        <select class="form-select" id="accessType" name="type">
                            <option value="">Pilih Tipe</option>
                            <option value="jabatan">Untuk Jabatan</option>
                            <option value="specific">Untuk User Spesifik</option>
                        </select>
                    </div>

                    <!-- Section untuk User Spesifik -->
                    <div id="userSpecificSection" style="display:none;">
                        <!-- Filter Jabatan -->
                        <div class="mb-3">
                            <label class="form-label">Filter Berdasarkan Jabatan</label>
                            <select class="form-select" id="userJabatanFilter">
                                <option value="">Semua Jabatan</option>
                                @foreach($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilih User -->
                        <div class="mb-3">
                            <label class="form-label">Pilih User</label>
                            <select class="form-select" id="userSelect" name="user_id">
                                <option value="">Pilih User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id_user }}" data-jabatan="{{ $user->jabatan_id }}">
                                        {{ $user->member->nama ?? 'Tidak ada nama' }} 
                                        (ID: {{ $user->member_id }}) - 
                                        {{ $user->jabatan->nama ?? 'Tidak ada jabatan' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Section untuk Jabatan -->
                    <div class="mb-3" id="jabatanSection" style="display:none;">
                        <label class="form-label">Pilih Jabatan</label>
                        <select class="form-select" id="jabatanSelect" name="jabatan_id">
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatans as $jabatan)
                                <option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Level Akses -->
                    <div class="mb-3">
                        <label class="form-label">Level Akses</label>
                        <select class="form-select" id="accessLevel" name="level">
                            <option value="">Pilih Level</option>
                            <option value="1">Level 1: Hanya lihat</option>
                            <option value="2">Level 2: Lihat dan edit</option>
                            <option value="3">Level 3: Akses penuh</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveAccess">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function() {
    // Filter user berdasarkan jabatan
    $('#userJabatanFilter').on('change', function() {
        const selectedJabatan = $(this).val();
        const userSelect = $('#userSelect');
        
        // Show all options first
        userSelect.find('option').show();
        
        if(selectedJabatan) {
            // Hide options that don't match the selected jabatan
            userSelect.find('option').each(function() {
                if($(this).data('jabatan') != selectedJabatan && $(this).val() != '') {
                    $(this).hide();
                }
            });
        }
    });

    // Handler untuk perubahan tipe akses
    $('#accessType').on('change', function() {
        const selectedType = $(this).val();
        
        // Sembunyikan semua section dulu
        $('#jabatanSection, #userSpecificSection').hide();
        
        // Tampilkan section sesuai pilihan
        if (selectedType === 'specific') {
            $('#userSpecificSection').show();
        } else if (selectedType === 'jabatan') {
            $('#jabatanSection').show();
        }
    });

    // Handler untuk tombol simpan
    $('#saveAccess').on('click', function() {
        const type = $('#accessType').val();
        const level = $('#accessLevel').val();
        let data = {
            type: type,
            level: level
        };

        // Tambahkan data sesuai tipe yang dipilih
        if (type === 'specific') {
            data.user_id = $('#userSelect').val();
        } else if (type === 'jabatan') {
            data.jabatan_id = $('#jabatanSelect').val();
        }

        // Validasi input
        if (!type || !level) {
            alert('Mohon lengkapi semua field yang diperlukan');
            return;
        }

        if ((type === 'specific' && !data.user_id) || 
            (type === 'jabatan' && !data.jabatan_id)) {
            alert('Mohon pilih user atau jabatan');
            return;
        }

        // Kirim request Ajax
        $.ajax({
            url: '/role-permissions/assign',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Berhasil menyimpan izin akses');
                $('#addRoleModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error details:', xhr.responseText);
                alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || error));
            }
        });
    });
});
</script>
@endpush 
</div> 