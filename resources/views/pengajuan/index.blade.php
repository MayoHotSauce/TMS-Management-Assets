@extends('adminlte::page')

@section('title', 'Daftar Pengajuan Asset')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar Pengajuan Asset</h1>
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Pengajuan
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header p-0">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $status === 'active' ? 'active' : '' }}" 
                   href="{{ route('pengajuan.index', ['status' => 'active']) }}">
                    Active
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'completed' ? 'active' : '' }}" 
                   href="{{ route('pengajuan.index', ['status' => 'completed']) }}">
                    Completed
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'archived' ? 'active' : '' }}" 
                   href="{{ route('pengajuan.index', ['status' => 'archived']) }}">
                    Archived
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
                                <span class="badge badge-warning">Pending</span>
                                @break
                            @case('approved')
                                <span class="badge badge-success">Approved</span>
                                @break
                            @case('declined')
                                <span class="badge badge-danger">Declined</span>
                                @break
                            @case('archived')
                                <span class="badge badge-secondary">Archived</span>
                                @break
                        @endswitch
                    </td>
                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('pengajuan.show', $request->id) }}" 
                               class="btn btn-info btn-sm mr-1" 
                               title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($request->status !== 'archived')
                                <form action="{{ route('pengajuan.archive', $request->id) }}" 
                                      method="POST" 
                                      class="d-inline ml-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-secondary btn-sm archive-btn" 
                                            title="Arsipkan">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </form>
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
<script>
    $(document).ready(function() {
        // Archive confirmation
        $('.archive-btn').click(function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Konfirmasi Arsip',
                text: "Apakah anda yakin ingin mengarsipkan pengajuan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Arsipkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@stop