@extends('adminlte::page')

@section('title', 'Daftar Maintenance')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar Maintenance</h1>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Maintenance
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body p-0">
            <div class="d-flex border-bottom">
                 <a href="{{ route('maintenance.index', ['status' => 'scheduled']) }}" 
                   class="px-4 py-2 {{ $status === 'scheduled' ? 'text-primary border-primary border-bottom' : 'text-secondary' }}">
                    Pending
                </a>
                <a href="{{ route('maintenance.index', ['status' => 'active']) }}" 
                   class="px-4 py-2 {{ $status === 'active' ? 'text-primary border-primary border-bottom' : 'text-secondary' }}">
                    Active
                </a>
                <a href="{{ route('maintenance.index', ['status' => 'completed']) }}" 
                   class="px-4 py-2 {{ $status === 'completed' ? 'text-primary border-primary border-bottom' : 'text-secondary' }}">
                    Completed
                </a>
                <a href="{{ route('maintenance.index', ['status' => 'archived']) }}" 
                   class="px-4 py-2 {{ $status === 'archived' ? 'text-primary border-primary border-bottom' : 'text-secondary' }}">
                    Archived
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Asset</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Teknisi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenances as $maintenance)
                            <tr>
                                <td>{{ $maintenance->id }}</td>
                                <td>
                                    @if($maintenance->asset)
                                        {{ $maintenance->asset->name ?? $maintenance->barang_id }}
                                    @else
                                        Asset ID: {{ $maintenance->barang_id }} (Not Found)
                                    @endif
                                </td>
                                <td>{{ $maintenance->type }}</td>
                                <td>
                                    @switch($maintenance->status)
                                        @case('scheduled')
                                            <span class="badge badge-warning">Scheduled</span>
                                            @break
                                        @case('in_progress')
                                            <span class="badge badge-info">In Progress</span>
                                            @break
                                        @case('completed')
                                            <span class="badge badge-success">Completed</span>
                                            @break
                                        @case('archived')
                                            <span class="badge badge-secondary">Archived</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $maintenance->technician_name }}</td>
                                <td>{{ $maintenance->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('maintenance.show', $maintenance->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($maintenance->status !== 'archived')
                                            <form action="{{ route('maintenance.archive', $maintenance->id) }}" 
                                                  method="POST" 
                                                  style="margin-left: 8px;">
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
                                <td colspan="7" class="text-center">Tidak ada data maintenance</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($maintenances->hasPages())
            <div class="card-footer clearfix">
                {{ $maintenances->appends(['status' => $status])->links() }}
            </div>
        @endif
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.archive-btn').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Konfirmasi Arsip',
                    text: "Apakah anda yakin ingin mengarsipkan maintenance ini?",
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