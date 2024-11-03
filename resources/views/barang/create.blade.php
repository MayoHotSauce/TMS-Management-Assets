@extends('adminlte::page')

@section('title', 'Add New Asset')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Asset</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Purchase Cost</label>
                <input type="number" name="purchase_cost" class="form-control" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label>Room</label>
                <select name="room_id" class="form-control" required>
                    <option value="">Select Room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Purchase Date</label>
                <input type="date" name="purchase_date" 
                       class="form-control" 
                       value="{{ date('Y-m-d') }}" 
                       required>
            </div>

            <button type="submit" class="btn btn-primary">Save Asset</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@stop