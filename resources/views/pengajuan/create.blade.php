@extends('adminlte::page')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Buat Pengajuan Asset Baru</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}">Pengajuan</a></li>
                        <li class="breadcrumb-item active">Buat</li>
                    </ol>
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
                            <label for="category">Kategori</label>
                            <select class="form-control @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price">Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="approver_email">Email Approver</label>
                            <input type="email" class="form-control @error('approver_email') is-invalid @enderror" 
                                   id="approver_email" name="approver_email" value="{{ old('approver_email') }}" required>
                            @error('approver_email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
    // Prevent multiple form submissions
    $('form').submit(function() {
        // Disable the submit button
        $(this).find(':submit').prop('disabled', true);
        
        // Optional: Add loading state
        $(this).find(':submit').html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    });

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
            // Re-enable the submit button if validation fails
            $(this).find(':submit').prop('disabled', false);
        }
    });
});
</script>
@endpush
@endsection