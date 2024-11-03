@extends('adminlte::page')

@section('title', 'Maintenance Logs')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Maintenance Logs</h1>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">New Maintenance Log</a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Cost</th>
                    <th>Performed By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenanceLogs as $log)
                <tr>
                    <td>{{ $log->barang->id }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->maintenance_date }}</td>
                    <td>{{ number_format($log->cost, 2) }}</td>
                    <td>{{ $log->performed_by }}</td>
                    <td>
                        <span class="badge badge-{{ $log->status === 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($log->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $maintenanceLogs->links() }}
        </div>
    </div>
</div>
@stop