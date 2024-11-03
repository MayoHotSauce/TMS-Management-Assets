@extends('adminlte::page')

@section('title', 'Daftar Barang')

@section('content_header')
    <h1>Daftar Barang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List Barang</h3>
            <div class="card-tools">
                <a href="{{ route('data-barang.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Barang
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Tag</th>
                        <th>Ruangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $barang)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $barang->name }}</td>
                        <td>{{ $barang->asset_tag }}</td>
                        <td>{{ $barang->room }}</td>
                        <td>{{ $barang->status }}</td>
                        <td>
                            <a href="{{ route('data-barang.edit', $barang->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('data-barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop 