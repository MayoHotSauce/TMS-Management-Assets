@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
    <h1>Edit Category</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
    <label for="maintenance_date">Date</label>
    <input type="date" 
           class="form-control @error('maintenance_date') is-invalid @enderror" 
           id="maintenance_date" 
           name="maintenance_date" 
           value="{{ old('maintenance_date', isset($maintenance) ? date('Y-m-d', strtotime($maintenance->maintenance_date)) : '') }}">
    @error('maintenance_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                <button type="submit" class="btn btn-primary">Update Category</button>
                <a href="{{ route('categories.index') }}" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
@stop
