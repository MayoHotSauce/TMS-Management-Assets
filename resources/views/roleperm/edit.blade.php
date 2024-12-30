<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Izin Akses - {{ $jabatan->nama }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ route('roleperm.update', $jabatan->id_jabatan) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Hak Akses</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="permissions[]" value="view_stock" class="custom-control-input" id="editViewStock">
                        <label class="custom-control-label" for="editViewStock">Lihat Stock</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="permissions[]" value="manage_stock" class="custom-control-input" id="editManageStock">
                        <label class="custom-control-label" for="editManageStock">Kelola Stock</label>
                    </div>
                    <!-- Add more permissions as needed -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div> 