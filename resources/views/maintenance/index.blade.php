@extends('adminlte::page')

@section('title', 'Maintenance Logs')

@section('content')
    {{-- Debug info --}}
    @if(config('app.debug'))
        <div class="alert alert-info">
            <p>Debug Info:</p>
            <ul>
                <li>Total Assets: {{ $assets->count() }}</li>
                <li>Assets Status Count: 
                    {{ $assets->groupBy('status')->map->count() }}
                </li>
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add New Maintenance Log</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('maintenance.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Asset</label>
                            <select name="barang_id" class="form-control @error('barang_id') is-invalid @enderror" required>
                                <option value="">Select Asset</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}">
                                        {{ $asset->name }} ({{ $asset->asset_tag }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Maintenance Date</label>
                            <input type="date" name="maintenance_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cost</label>
                            <input type="number" name="cost" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Performed By</label>
                            <input type="text" name="performed_by" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="scheduled">Dijadwalkan</option>
                                <option value="pending">Sedang Dikerjakan</option>
                                <option value="completed">Selesai</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Maintenance Log</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Maintenance Logs List</h3>
                <div class="d-flex align-items-center gap-3">
                    {{-- Pagination controls --}}
                    <div class="d-flex align-items-center">
                        <span class="mr-2">
                            Showing {{ $maintenanceLogs->firstItem() ?? 0 }}-{{ $maintenanceLogs->lastItem() ?? 0 }} 
                            of {{ $maintenanceLogs->total() }}
                        </span>
                        <nav class="mx-2">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Page Link --}}
                                @if ($maintenanceLogs->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $maintenanceLogs->previousPageUrl() }}&status={{ $currentStatus }}" rel="prev">&laquo;</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($maintenanceLogs->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $maintenanceLogs->nextPageUrl() }}&status={{ $currentStatus }}" rel="next">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">&raquo;</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>

                    {{-- Status filter dropdown --}}
                    <select id="statusFilter" class="form-control">
                        <option value="active" {{ $currentStatus == 'active' ? 'selected' : '' }}>Active Maintenance</option>
                        <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed Maintenance</option>
                        <option value="all" {{ $currentStatus == 'all' ? 'selected' : '' }}>All Maintenance</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
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
                    @forelse($maintenanceLogs as $maintenance)
                        <tr>
                            <td>{{ optional($maintenance->asset)->name ?? 'No Asset' }}</td>
                            <td>{{ $maintenance->description }}</td>
                            <td>{{ date('d/m/Y', strtotime($maintenance->maintenance_date)) }}</td>
                            <td>Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</td>
                            <td>{{ $maintenance->performed_by }}</td>
                            <td>
                                @php
                                    $statusLabels = [
                                        'scheduled' => 'Dijadwalkan',
                                        'pending' => 'Sedang Dikerjakan',
                                        'completed' => 'Selesai'
                                    ];

                                    $statusClass = [
                                        'scheduled' => 'warning',
                                        'pending' => 'info',
                                        'completed' => 'success'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusClass[$maintenance->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$maintenance->status] ?? $maintenance->status }}
                                </span>
                            </td>
                            <td>
                                @if($maintenance->status === 'scheduled')
                                    <form action="{{ route('maintenance.updateStatus', $maintenance->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Mulai kerjakan maintenance ini?')">
                                            <i class="fas fa-tools"></i> Mulai Kerjakan
                                        </button>
                                    </form>
                                @endif

                                @if($maintenance->status !== 'completed')
                                    <button type="button" class="btn btn-success btn-sm" onclick="showStatusModal({{ $maintenance->id }})">
                                        <i class="fas fa-check"></i> Selesai
                                    </button>
                                @endif

                                <form action="{{ route('maintenance.destroy', $maintenance->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No maintenance logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
<style>
    .badge {
        font-size: 0.9em;
        padding: 0.5em 0.75em;
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-link {
        padding: 0.25rem 0.5rem;
    }
    .d-flex.gap-3 > * {
        margin-left: 0.5rem;
    }
    .pagination-sm .page-link {
        font-size: 0.875rem;
    }
</style>
@stop

@section('js')
<script>
    function showStatusModal(maintenanceId) {
        $('#updateStatusForm').attr('action', `{{ url('maintenance') }}/${maintenanceId}/complete`);
        $('#statusModal').modal('show');
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#updateStatusForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    $('#statusModal').modal('hide');
                    if (response.redirectUrl) {
                        window.location.href = response.redirectUrl;
                    } else {
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert('Error updating status: ' + xhr.responseText);
                }
            });
        });

        // Status filter functionality
        $('#statusFilter').change(function() {
            let status = $(this).val();
            window.location.href = `{{ route('maintenance.index') }}?status=${status}`;
        });
    });
</script>
@stop

<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Maintenance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Status:</label>
                        <select name="status" class="form-control" required>
                            <option value="scheduled">Dijadwalkan</option>
                            <option value="pending">Sedang Dikerjakan</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>