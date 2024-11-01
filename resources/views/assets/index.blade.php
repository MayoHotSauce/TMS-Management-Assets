<!-- resources/views/assets/index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Asset Management</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('assets.create') }}" class="btn btn-primary float-right">Add New Asset</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Asset List</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Asset Tag</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assets as $asset)
                                    <tr>
                                        <td>{{ $asset->asset_tag }}</td>
                                        <td>{{ $asset->name }}</td>
                                        <td>{{ $asset->category->name }}</td>
                                        <td>{{ $asset->location->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $asset->status === 'available' ? 'success' : 
                                                                    ($asset->status === 'in_use' ? 'primary' : 
                                                                    ($asset->status === 'maintenance' ? 'warning' : 'danger')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $assets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>