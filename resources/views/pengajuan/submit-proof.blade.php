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
                <form action="{{ route('pengajuan.submit-proof', $pengajuan->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data" 
                      id="proofForm">
                    @csrf
                    <div class="card-body">
                        <!-- Debug info -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Asset</label>
                                    <input type="text" class="form-control" value="{{ $pengajuan->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estimasi Biaya</label>
                                    <input type="text" class="form-control" value="Rp {{ number_format($pengajuan->price, 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Foto Bukti Pembelian <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('proof_image') is-invalid @enderror" 
                                       id="proof_image" 
                                       name="proof_image" 
                                       accept="image/*" 
                                       capture="environment"
                                       required>
                                <label class="custom-file-label" for="proof_image">Pilih file atau ambil foto</label>
                            </div>
                            @error('proof_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="mt-2">
                                <img id="image-preview" src="#" alt="Preview" style="max-width: 300px; display: none;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Total Biaya Aktual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" 
                                       class="form-control @error('final_cost') is-invalid @enderror" 
                                       name="final_cost" 
                                       required>
                            </div>
                            @error('final_cost')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Keterangan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('proof_description') is-invalid @enderror" 
                                      name="proof_description" 
                                      rows="3" 
                                      required></textarea>
                            @error('proof_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit Bukti</button>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#proofForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Bukti pembelian berhasil disubmit',
                    icon: 'success'
                }).then(() => {
                    window.location.href = "{{ route('pengajuan.index') }}";
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat submit bukti',
                    icon: 'error'
                });
            }
        });
    });

    // Preview image
    $("#proof_image").change(function() {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
});
</script>
@stop

@push('css')
<style>
.custom-file-input:lang(en)~.custom-file-label::after {
    content: "Browse";
}
.image-preview-container {
    margin-top: 10px;
    text-align: center;
}
#image-preview {
    max-width: 100%;
    max-height: 300px;
    margin-top: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
</style>
@endpush