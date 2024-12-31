@extends('adminlte::page')

@section('title', 'Stock Check List')

@section('content_header')
    <h1>Stock Check List</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Completed Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockChecks as $check)
                <tr>
                    <td>{{ $check->creator->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $check->status === 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($check->status) }}
                        </span>
                    </td>
                    <td>
                        {{ $check->last_updated_at ? $check->last_updated_at->format('Y-m-d H:i') : 'Not updated' }}
                    </td>
                    <td>
                        {{ $check->completed_at ? $check->completed_at->format('Y-m-d H:i') : 'Not completed' }}
                    </td>
                    <td>
                        @can('view stock')
                            <a href="{{ route('stock.show', $check->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                        @endcan
                        @if($check->status === 'completed')
                            @can('download stock report')
                                <a href="{{ route('stock.download.csv', $check->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-file-download"></i> CSV
                                </a>
                            @endcan
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 