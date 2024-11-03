@extends('adminlte::page')

@section('title', 'Asset Management')

@section('content_header')
    <h1>Asset Management</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Asset List</h3>
        <div class="card-tools">
            <a href="{{ route('barang.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Asset
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Room</th>
                    <th>Category</th>
                    <th>Year</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barang as $item)
                <tr>
                    <td>{{ $item->asset_tag }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->room ? $item->room->name : 'No Room' }}</td>
                    <td>{{ $item->category ? $item->category->name : 'No Category' }}</td>
                    <td>{{ $item->purchase_date }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $barang->links() }}
    </div>
</div>
@stop