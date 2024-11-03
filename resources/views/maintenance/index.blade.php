@extends('adminlte::page')

@section('title', 'Maintenance Logs')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Maintenance Logs</h3>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary float-right">New Maintenance Log</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
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
                @foreach($maintenanceLogs as $maintenance)
                <tr>
                    <td>{{ $maintenance->asset_name ?? 'No Asset' }}</td>
                    <td>{{ $maintenance->description }}</td>
                    <td>{{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('Y-m-d') }}</td>
                    <td>{{ number_format($maintenance->cost, 2, ',', '.') }}</td>
                    <td>{{ $maintenance->performed_by }}</td>
                    <td>{{ $maintenance->status }}</td>
                    <td>
                        <a href="{{ route('maintenance.edit', $maintenance->id) }}" class="btn btn-info">Edit</a>
                        <form action="{{ route('maintenance.destroy', $maintenance->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Add any JavaScript functionality here
    });
</script>
@stop