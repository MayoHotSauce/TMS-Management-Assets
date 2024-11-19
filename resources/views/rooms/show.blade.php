@extends('adminlte::page')

@section('title', 'Detail Ruangan')

@section('content_header')
    <h1>Detail Ruangan: {{ $room->name }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Asset di {{ $room->name }}</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Asset Tag</th>
                        <th>Nama Barang</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal Pembelian</th>
                        <th>Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                        <tr>
                            <td>{{ $asset->asset_tag }}</td>
                            <td>{{ $asset->name }}</td>
                            <td>{{ $asset->description }}</td>
                            <td>{{ optional($asset->category)->name }}</td>
                            <td>
                                @php
                                    $statusLabels = [
                                        'siap_dipakai' => 'Siap Dipakai',
                                        'sedang_dipakai' => 'Sedang Dipakai',
                                        'dalam_perbaikan' => 'Dalam Perbaikan',
                                        'rusak' => 'Rusak',
                                        'siap_dipinjam' => 'Siap Dipinjam',
                                        'sedang_dipinjam' => 'Sedang Dipinjam',
                                        'dimusnahkan' => 'Dimusnahkan'
                                    ];

                                    $statusClass = [
                                        'siap_dipakai' => 'success',
                                        'sedang_dipakai' => 'primary',
                                        'dalam_perbaikan' => 'warning',
                                        'rusak' => 'danger',
                                        'siap_dipinjam' => 'info',
                                        'sedang_dipinjam' => 'secondary',
                                        'dimusnahkan' => 'dark'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusClass[$asset->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$asset->status] ?? ucfirst(str_replace('_', ' ', $asset->status)) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($asset->purchase_cost, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('barang.edit', $asset->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('barang.destroy', $asset->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada asset di ruangan ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@stop

@section('css')
<style>
    .table-responsive {
        overflow-x: auto;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>
@stop 