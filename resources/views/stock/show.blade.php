@extends('adminlte::page')

@section('title', 'Stock Check Details')

@section('content_header')
    <h1>Stock Check Details</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Stock Check #{{ $stockCheck->id }}
            <span class="badge badge-{{ $stockCheck->status === 'completed' ? 'success' : 'warning' }}">
                {{ ucfirst($stockCheck->status) }}
            </span>
        </h3>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Created By:</strong> {{ $stockCheck->creator->name }}</p>
                <p><strong>Created At:</strong> {{ $stockCheck->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>Last Updated:</strong> {{ $stockCheck->last_updated_at ? $stockCheck->last_updated_at->format('Y-m-d H:i') : 'Not updated' }}</p>
                <p><strong>Completed At:</strong> {{ $stockCheck->completed_at ? $stockCheck->completed_at->format('Y-m-d H:i') : 'Not completed' }}</p>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Asset Name</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allAssets as $asset)
                    @php
                        $stockItem = $stockCheck->items->where('asset_id', $asset->id)->first();
                    @endphp
                    <tr>
                        <td>{{ $asset->name }}</td>
                        <td>{{ $stockItem->description ?? 'No description' }}</td>
                        <td>
                            @if($stockItem && $stockItem->is_checked)
                                <span class="badge badge-success">Checked</span>
                            @else
                                <span class="badge badge-secondary">Not Checked</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('stock.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        @if($stockCheck->status === 'completed')
            <a href="{{ route('stock.download.csv', $stockCheck->id) }}" class="btn btn-success">
                <i class="fas fa-file-download"></i> Download CSV
            </a>
        @endif
    </div>
</div>
@endsection 