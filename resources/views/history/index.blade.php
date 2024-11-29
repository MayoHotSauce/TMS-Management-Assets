@extends('adminlte::page')

@section('title', 'Activity History')

@section('content_header')
    <h1>Activity History</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">User Activities</h3>
                <div class="d-flex gap-2">
                    <select name="module" class="form-control" id="moduleFilter">
                        <option value="">All Modules</option>
                        <option value="category" {{ request('module') == 'category' ? 'selected' : '' }}>Categories</option>
                        <option value="barang" {{ request('module') == 'barang' ? 'selected' : '' }}>Daftar Barang</option>
                        <option value="room" {{ request('module') == 'room' ? 'selected' : '' }}>Ruangan</option>
                        <option value="maintenance" {{ request('module') == 'maintenance' ? 'selected' : '' }}>Perbaikan</option>
                        <option value="asset_request" {{ request('module') == 'asset_request' ? 'selected' : '' }}>Pengajuan</option>
                    </select>
                    <select name="action" class="form-control" id="actionFilter">
                        <option value="">All Actions</option>
                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                        <option value="approve" {{ request('action') == 'approve' ? 'selected' : '' }}>Approve</option>
                        <option value="decline" {{ request('action') == 'decline' ? 'selected' : '' }}>Decline</option>
                    </select>
                    <button type="button" class="btn btn-primary" id="filterButton">Filter</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Module</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td>{{ $activity->created_at }}</td>
                                <td>{{ $activity->user->name }}</td>
                                <td>{{ $activity->module }}</td>
                                <td>{{ $activity->action }}</td>
                                <td>{{ $activity->description }}</td>
                                <td>{{ $activity->ip_address }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $activities->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
document.getElementById('filterButton').addEventListener('click', function() {
    const module = document.getElementById('moduleFilter').value;
    const action = document.getElementById('actionFilter').value;
    
    let url = '{{ route('history.index') }}?';
    if (module) url += 'module=' + module;
    if (action) url += (module ? '&' : '') + 'action=' + action;
    
    window.location.href = url;
});
</script>
@stop

@section('css')
<style>
    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .pagination li {
        display: inline-block;
    }

    .pagination li a,
    .pagination li span {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        color: #007bff;
        text-decoration: none;
    }

    .pagination li.active span {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination li.disabled span {
        color: #6c757d;
        pointer-events: none;
        cursor: not-allowed;
    }

    .pagination li a:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
</style>
@endsection 