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
                    @foreach($maintenanceLogs as $log)
                        <tr>
                            <td>{{ $log->asset->name ?? 'N/A' }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->maintenance_date }}</td>
                            <td>{{ number_format($log->cost, 2) }}</td>
                            <td>{{ $log->performed_by }}</td>
                            <td>
                                @if($log->status == 'scheduled')
                                    <span class="badge badge-warning">Menunggu Persetujuan</span>
                                @elseif($log->status == 'in_progress')
                                    <span class="badge badge-info">In Progress</span>
                                @elseif($log->status == 'pending_final_approval')
                                    <span class="badge badge-secondary">Menunggu Persetujuan Akhir</span>
                                @endif
                            </td>
                            <td>
                                @if($log->status == 'scheduled')
                                    <button class="btn btn-primary btn-sm start-work" data-id="{{ $log->id }}">
                                        Kerjakan
                                    </button>
                                    <button class="btn btn-success btn-sm complete-work" data-id="{{ $log->id }}">
                                        Selesai
                                    </button>
                                @elseif($log->status == 'in_progress')
                                    <button class="btn btn-success btn-sm complete-work" data-id="{{ $log->id }}">
                                        Selesai
                                    </button>
                                @endif
                                
                                @if($log->status != 'archived')
                                    <button class="btn btn-secondary btn-sm archive-maintenance" data-id="{{ $log->id }}">
                                        Arsipkan
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span>
                    Showing {{ $maintenanceLogs->firstItem() ?? 0 }}-{{ $maintenanceLogs->lastItem() ?? 0 }} of {{ $maintenanceLogs->total() }}
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

        // Start Work Button
        $('.start-work').on('click', function() {
            const id = $(this).data('id');
            if (confirm('Kerjakan Perbaikan ini?')) {
                $.ajax({
                    url: `/maintenance/${id}/start`, // Adjust the URL as needed
                    type: 'PUT',
                    success: function(response) {
                        location.reload(); // Reload the page to see the updated status
                    },
                    error: function(xhr) {
                        alert('Error updating status');
                    }
                });
            }
        });

        // Complete Work Button
        $('.complete-work').on('click', function() {
            const id = $(this).data('id');
            window.location.href = `/maintenance/${id}/completion-form`;
        });
    });
</script>
@stop