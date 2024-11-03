@extends('adminlte::page')

@section('title', 'Edit Asset')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Asset</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('barang.update', $barang->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $barang->name }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" value="{{ $barang->description }}" required>
            </div>

            <div class="form-group">
                <label>Room</label>
                <select name="room_id" class="form-control" required>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $barang->room_id == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $barang->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Purchase Date</label>
                <input type="date" name="purchase_date" 
                       class="form-control" 
                       value="{{ $barang->purchase_date }}" 
                       required>
            </div>

            <div class="form-group">
                <label>Purchase Cost</label>
                <input type="number" name="purchase_cost" 
                       class="form-control" 
                       value="{{ $barang->purchase_cost }}" 
                       required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection 

@section('title', 'Edit Asset')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Asset</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('barang.update', $barang->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $barang->name }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" value="{{ $barang->description }}" required>
            </div>

            <div class="form-group">
                <label>Room</label>
                <select name="room_id" class="form-control" required>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $barang->room_id == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $barang->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Purchase Date</label>
                <input type="date" name="purchase_date" 
                       class="form-control" 
                       value="{{ $barang->purchase_date }}" 
                       required>
            </div>

            <div class="form-group">
                <label>Purchase Cost</label>
                <input type="number" name="purchase_cost" 
                       class="form-control" 
                       value="{{ $barang->purchase_cost }}" 
                       required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection 