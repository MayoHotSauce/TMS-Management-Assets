@extends('adminlte::page')

@section('title', 'Daftar Perbaikan')

@section('content_header')
    <h1>Maintenance Logs List</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Maintenance Logs List</h3>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Maintenance
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div class="mb-3">
                <select id="statusFilter" class="form-control ml-2" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
            </div>

            <table class="table table-bordered" id="maintenance-table">
                <thead>
                    <tr>
                        <th>Asset Name</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Cost</th>
                        <th>Performed By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintenances as $maintenance)
                        <tr>
                            <td>{{ $maintenance->asset->name ?? 'N/A' }}</td>
                            <td>{{ $maintenance->description }}</td>
                            <td>{{ $maintenance->maintenance_date }}</td>
                            <td>{{ number_format($maintenance->cost, 2) }}</td>
                            <td>{{ $maintenance->performed_by }}</td>
                            <td>
                                @if($maintenance->status == 'scheduled')
                                    <span class="badge badge-warning">Menunggu Persetujuan</span>
                                @elseif($maintenance->status == 'in_progress')
                                    <span class="badge badge-info">Dalam Proses</span>
                                @elseif($maintenance->status == 'completed')
                                    <span class="badge badge-success">Selesai</span>
                                @else
                                    <span class="badge badge-secondary">{{ $maintenance->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('maintenance.show', $maintenance->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('maintenance.edit', $maintenance->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @if($maintenance->status == 'in_progress')
                                    <a href="{{ route('maintenance.completion.form', $maintenance->id) }}" class="btn btn-sm btn-success">
                                        Selesai
                                    </a>
                                @endif
                                <button type="button" class="btn btn-sm btn-secondary" onclick="confirmArchive(this)">
                                    Arsip
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span>
                    Showing {{ $maintenances->firstItem() ?? 0 }}-{{ $maintenances->lastItem() ?? 0 }} of {{ $maintenances->total() }}
                </span>
                {{ $maintenances->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        $('#statusFilter').on('change', function() {
            window.location.href = "{{ route('maintenance.index') }}?status=" + $(this).val();
        });
    });

    function confirmArchive(button) {
        console.log('Archive button clicked');
        Swal.fire({
            title: 'Konfirmasi Arsip',
            text: "Apakah anda yakin ingin mengarsipkan data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Arsipkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Submitting form...');
                button.closest('form').submit();
            }
        });
    }

    // Add SweetAlert for success/error messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}"
        });
    @endif
    </script>
@stop