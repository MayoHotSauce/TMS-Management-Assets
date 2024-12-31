@extends('adminlte::page')

@section('title', 'Categories Management')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Categories Management</h1>
        @can('create category')
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Create New Category</a>
        @endcan
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description }}</td>
                            <td>{{ $category->created_at }}</td>
                            <td>
                                <div class="btn-group">
                                    @can('edit category')
                                        <a href="{{ route('categories.edit', $category) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('delete category')
                                        <form action="{{ route('categories.destroy', $category) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure?');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop