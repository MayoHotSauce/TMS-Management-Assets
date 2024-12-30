@extends('adminlte::page')

@section('title', 'Daftar Maintenance')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar Maintenance</h1>
        @can('create_maintenance')
            <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Maintenance
            </a>
        @endcan
    </div>
@stop

@section('css')
    <!-- existing CSS -->
@stop

@section('content')
    <div class="card">
        <div class="card-body p-0">
            <div class="d-flex border-bottom">
                 <a href="{{ route('maintenance.index', ['status' => 'scheduled']) }}" 
                   class="px-4 py-2 {{ $status === 'scheduled' ? 'text-primary border-primary border-bottom' : 'text-secondary' }}">
                    Menunggu Approval
                </a>
                <a href="{{ route('maintenance.index', ['status' => 'active']) }}" 
                   class="px-4 py-2 {{ $status === 'active' ? 'text-primary border-primary border-bottom' : 'text-secondary' }}">
                    Sedang Dikerjakan
                </a>
                <a href="{{ route('maintenance.index', ['status' => 'revisi']) }}" 
                   class="px-4 py-2 {{ $status === 'revisi' ? 'text-primary border-primary border-bottom' : 'text-secondary' }}">
                    Revisi
                </a>
                <a href="{{ route('maintenance.index', ['status' => 'completed']) }}" 
                   class="px-4 py-2 {{ $status === 'completed' ? 'text-primary border-primary border-bottom' : 'text-secondary' }}">
                    Selesai
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
                                <td>
                                    @can('view_maintenance')
                                        <a href="{{ route('maintenance.show', $maintenance->id) }}">
                                            {{ $maintenance->id }}
                                        </a>
                                    @else
                                        {{ $maintenance->id }}
                                    @endcan
                                </td>
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
                                            <span class="badge badge-warning">Menunggu Approval</span>
                                            @break
                                        @case('pending_final_approval')
                                            <span class="badge badge-info">Menunggu Final Approval</span>
                                            @break
                                        @case('in_progress')
                                            <span class="badge badge-info">Sedang Dikerjakan</span>
                                            @break
                                        @case('archived')
                                            <span class="badge badge-secondary">Revisi</span>
                                            @break
                                        @case('completed')
                                            <span class="badge badge-success">Selesai</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $maintenance->technician_name }}</td>
                                <td>{{ $maintenance->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center" style="gap: 10px;">
                                        <a href="{{ route('maintenance.show', $maintenance->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($maintenance->status === 'archived')
                                            <button type="button" 
                                                    class="btn btn-primary btn-sm" 
                                                    onclick="confirmRestart({{ $maintenance->id }})"
                                                    title="Kerjakan Ulang">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        @endif

                                        @if($maintenance->status === 'in_progress')
                                            <a href="{{ route('maintenance.completion-form', $maintenance->id) }}" 
                                               class="btn btn-success btn-sm"
                                               title="Form Penyelesaian">
                                                <i class="fas fa-check"></i>
                                            </a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Show alert if there's a success message
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif

        // Show alert if there's an error message
        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        @endif

        function confirmRestart(id) {
            Swal.fire({
                title: 'Kerjakan Ulang Maintenance',
                text: "Apakah anda yakin ingin mengerjakan ulang maintenance ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kerjakan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/maintenance/' + id + '/restart';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@stop