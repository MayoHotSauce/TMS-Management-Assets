@extends('adminlte::page')

@section('title', 'Submit Bukti Pembelian')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Submit Bukti Pembelian Asset</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Bukti Pembelian</h3>
                </div>
                <form action="{{ route('pengajuan.submit-proof', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Asset</label>
                                    <input type="text" class="form-control" value="{{ $pengajuan->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estimasi Biaya Awal</label>
                                    <input type="text" class="form-control" value="Rp {{ number_format($pengajuan->price, 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="proof_image">Foto Bukti Pembelian <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('proof_image') is-invalid @enderror" 
                                   id="proof_image" name="proof_image" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                            @error('proof_image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="total_cost">Total Biaya Aktual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('total_cost') is-invalid @enderror" 
                                       id="total_cost" name="total_cost" required>
                            </div>
                            @error('total_cost')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="proof_description">Keterangan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('proof_description') is-invalid @enderror" 
                                      id="proof_description" name="proof_description" rows="3" required
                                      placeholder="Jelaskan detail pembelian, termasuk jika ada perbedaan harga"></textarea>
                            @error('proof_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit Bukti</button>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Preview image before upload
    $("#proof_image").change(function() {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
});
</script>
@endpush 