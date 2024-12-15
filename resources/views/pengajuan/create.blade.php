@extends('adminlte::page')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Buat Pengajuan Asset Baru</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Pengajuan Asset</h3>
                </div>
                <form action="{{ route('pengajuan.store') }}" method="POST" id="pengajuanForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama Asset</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="room_id">Ruangan</label>
                            <select class="form-control @error('room_id') is-invalid @enderror" 
                                    id="room_id" name="room_id" required>
                                <option value="">Pilih Ruangan</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">
                                Deskripsi <small class="text-muted">(Optional)</small>
                            </label>
                            <textarea 
                                class="form-control @error('description') is-invalid @enderror"
                                id="description" 
                                name="description"
                                rows="3"
                                placeholder="Masukkan deskripsi (opsional)">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Format price input with thousand separator
    $('#price').on('input', function() {
        let value = $(this).val().replace(/[^\d]/g, '');
        $(this).val(value);
    });

    // Form validation
    $('#pengajuanForm').on('submit', function(e) {
        let price = $('#price').val();
        if (price <= 0) {
            e.preventDefault();
            alert('Harga harus lebih besar dari 0');
            return false;
        }
    });
});
</script>
@endpush
@endsection