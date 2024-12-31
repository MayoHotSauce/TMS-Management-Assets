@extends('adminlte::page')

@section('title', 'Daftar Pengajuan Asset')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar Pengajuan Asset</h1>
        @can('create pengajuan')
            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Pengajuan
            </a>
        @endcan
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header p-0">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
                   href="{{ route('pengajuan.index', ['status' => 'pending']) }}">
                    Persetujuan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'active' ? 'active' : '' }}" 
                   href="{{ route('pengajuan.index', ['status' => 'active']) }}">
                    Aktif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'bukti' ? 'active' : '' }}" 
                   href="{{ route('pengajuan.index', ['status' => 'bukti']) }}">
                    Bukti
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'completed' ? 'active' : '' }}" 
                   href="{{ route('pengajuan.index', ['status' => 'completed']) }}">
                    Selesai
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Asset</th>
                    <th>Kategori</th>
                    <th>Ruangan</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->name }}</td>
                    <td>{{ $request->category }}</td>
                    <td>{{ $request->room ? $request->room->name : '-' }}</td>
                    <td>Rp {{ number_format($request->price, 0, ',', '.') }}</td>
                    <td>
                        @switch($request->status)
                            @case('pending')
                                <span class="badge badge-warning">Menunggu Persetujuan</span>
                                @break
                            @case('active')
                                <span class="badge badge-info">Aktif</span>
                                @break
                            @case('bukti')
                                <span class="badge badge-primary">Menunggu Bukti</span>
                                @break
                            @case('final_approval')
                                <span class="badge badge-warning">Menunggu Final</span>
                                @break
                            @case('completed')
                                <span class="badge badge-success">Selesai</span>
                                @break
                            @case('rejected')
                                <span class="badge badge-danger">Ditolak</span>
                                @break
                        @endswitch
                    </td>
                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('pengajuan.show', $request->id) }}" 
                               class="btn btn-info btn-sm" 
                               title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($status === 'active')
                                <a href="{{ route('pengajuan.submit-proof', $request->id) }}" 
                                   class="btn btn-success btn-sm ms-1 submit-proof-btn" 
                                   title="Submit Bukti"
                                   data-request-id="{{ $request->id }}">
                                    <i class="fas fa-check"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada pengajuan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
    <div class="card-footer clearfix">
        {{ $requests->appends(['status' => $status])->links() }}
    </div>
    @endif
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Submit Proof confirmation
        $('.submit-proof-btn').click(function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            
            Swal.fire({
                title: 'Submit Bukti Pembelian',
                text: "Anda akan mengirimkan bukti pembelian untuk asset ini. Lanjutkan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Submit Bukti!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
@stop