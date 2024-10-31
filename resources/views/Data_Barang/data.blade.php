<!-- resources/views/data-barang/index.blade.php -->
@extends('adminlte::page')
extends('layouts.app')

@section('title', 'Data Barang')

@section('content_header')
    <h1>Data Barang</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Barang</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-barang">
                <i class="fas fa-plus"></i> Tambah Barang
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="barang-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Deskripsi</th>
                    <th>Ruangan</th>
                    <th>Kategori</th>
                    <th>Tahun Pengadaan</th>
                    <th>Tanggal Input</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Add Barang -->
<div class="modal fade" id="modal-add-barang">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-add-barang">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="description" required>
                    </div>
                    <div class="form-group">
                        <label>Ruangan</label>
                        <select class="form-control" name="room" required>
                            <option value="Ruang Utama">Ruang Utama</option>
                            <option value="Ruang Meeting">Ruang Meeting</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tahun Pengadaan</label>
                        <input type="number" class="form-control" name="tahun_pengadaan" 
                               min="1900" max="{{ date('Y') }}" value="{{ date('Y') }}" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Barang -->
<div class="modal fade" id="modal-edit-barang">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit-barang">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Barang</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Similar fields as add modal -->
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="description" id="edit-description" required>
                    </div>
                    <div class="form-group">
                        <label>Ruangan</label>
                        <select class="form-control" name="room" id="edit-room" required>
                            <option value="Ruang Utama">Ruang Utama</option>
                            <option value="Ruang Meeting">Ruang Meeting</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" name="category_id" id="edit-category" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tahun Pengadaan</label>
                        <input type="number" class="form-control" name="tahun_pengadaan" 
                               id="edit-tahun" min="1900" max="{{ date('Y') }}" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    var table = $('#barang-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('data-barang.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'description', name: 'description'},
            {data: 'room', name: 'room'},
            {data: 'kategori', name: 'kategori'},
            {data: 'tahun_pengadaan', name: 'tahun_pengadaan'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    // Handle Add Form Submit
    $('#form-add-barang').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('data-barang.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#modal-add-barang').modal('hide');
                table.ajax.reload();
                toastr.success('Data berhasil ditambahkan');
            },
            error: function(xhr) {
                toastr.error('Terjadi kesalahan');
            }
        });
    });

    // Handle Edit Button Click
    $('body').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get("{{ route('data-barang.index') }}" + '/' + id + '/edit', function(data) {
            $('#edit-id').val(data.id);
            $('#edit-description').val(data.description);
            $('#edit-room').val(data.room);
            $('#edit-category').val(data.category_id);
            $('#edit-tahun').val(data.tahun_pengadaan);
            $('#modal-edit-barang').modal('show');
        });
    });

    // Handle Edit Form Submit
    $('#form-edit-barang').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-id').val();
        $.ajax({
            url: "{{ route('data-barang.index') }}" + '/' + id,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#modal-edit-barang').modal('hide');
                table.ajax.reload();
                toastr.success('Data berhasil diupdate');
            },
            error: function(xhr) {
                toastr.error('Terjadi kesalahan');
            }
        });
    });

    // Handle Delete Button Click
    $('body').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        if(confirm('Apakah anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: "{{ route('data-barang.index') }}" + '/' + id,
                method: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function(response) {
                    table.ajax.reload();
                    toastr.success('Data berhasil dihapus');
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan');
                }
            });
        }
    });
});
</script>
@stop