@extends('adminlte::page')

@section('title', 'Maintenance Logs')

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
                    <select id="statusFilter" class="form-control ml-2">
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
                        @if($maintenance->approval_status === 'approved')
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
                                        <a href="{{ route('maintenance.showCompletion', $maintenance->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Selesai
                                        </a>
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
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No maintenance logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span>
                    Showing {{ $maintenanceLogs->firstItem() ?? 0 }}-{{ $maintenanceLogs->lastItem() ?? 0 }} 
                    of {{ $maintenanceLogs->total() }}
                </span>
                {{ $maintenanceLogs->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .badge {
        font-size: 0.9em;
        padding: 0.5em 0.75em;
    }
    .gap-3 > * {
        margin-left: 0.5rem;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#statusFilter').change(function() {
            let status = $(this).val();
            window.location.href = `{{ route('maintenance.index') }}?status=${status}`;
        });
    });
</script>
@stop