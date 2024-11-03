@extends('adminlte::page')

@section('title', 'Asset Transfers')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Asset Transfers</h1>
        <a href="{{ route('transfers.create') }}" class="btn btn-primary">New Transfer</a>
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
                    <th>From Room</th>
                    <th>To Room</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfers as $transfer)
                <tr>
                    <td>{{ $transfer->barang->id }}</td>
                    <td>{{ $transfer->barang->description }}</td>
                    <td>{{ $transfer->from_room }}</td>
                    <td>{{ $transfer->to_room }}</td>
                    <td>{{ $transfer->transfer_date }}</td>
                    <td>
                        <span class="badge badge-{{ $transfer->status === 'completed' ? 'success' : ($transfer->status === 'pending' ? 'warning' : 'info') }}">
                            {{ ucfirst($transfer->status) }}
                        </span>
                    </td>
                    <td>
                        @if($transfer->status === 'pending')
                            <form action="{{ route('transfers.approve', $transfer) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $transfers->links() }}
        </div>
    </div>
</div>
@stop