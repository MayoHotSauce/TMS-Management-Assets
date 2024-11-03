@extends('adminlte::page')

@section('title', 'Create Room')

@section('content_header')
    <h1>Create New Room</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Room Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="floor">Floor</label>
                    <input type="text" class="form-control @error('floor') is-invalid @enderror" 
                           id="floor" name="floor" value="{{ old('floor') }}" required>
                    @error('floor')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="building">Building</label>
                    <input type="text" class="form-control @error('building') is-invalid @enderror" 
                           id="building" name="building" value="{{ old('building') }}" required>
                    @error('building')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                           id="capacity" name="capacity" value="{{ old('capacity') }}">
                    @error('capacity')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="responsible_person">Responsible Person</label>
                    <input type="text" class="form-control @error('responsible_person') is-invalid @enderror" 
                           id="responsible_person" name="responsible_person" value="{{ old('responsible_person') }}">
                    @error('responsible_person')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create Room</button>
                <a href="{{ route('rooms.index') }}" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
@stop