@extends('adminlte::page')

@section('title', 'Formulir Penyelesaian')

@section('content_header')
    <h1>Formulir Penyelesaian Perbaikan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('maintenance.submit-completion', $maintenance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="asset">Asset</label>
                <input type="text" class="form-control" id="asset" value="{{ $maintenance->asset->name }}" readonly>
            </div>

            <div class="form-group">
                <label for="request_date">Tanggal Permintaan</label>
                <input type="date" class="form-control" id="request_date" value="{{ $maintenance->maintenance_date }}" readonly>
            </div>

            <div class="form-group">
                <label for="completion_date">Tanggal Selesai</label>
                <input type="date" name="completion_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="technician">Teknisi</label>
                <input type="text" name="technician" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="actions_taken">Tindakan yang Dilakukan</label>
                <textarea name="actions_taken" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="repair_result">Hasil Perbaikan</label>
                <textarea name="repair_result" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="replaced_parts">Part yang Diganti</label>
                <textarea name="replaced_parts" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="total_cost">Total Biaya</label>
                <input type="number" name="total_cost" class="form-control" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="equipment_status">Status Peralatan</label>
                <input type="text" name="equipment_status" class="form-control">
            </div>

            <div class="form-group">
                <label for="next_priority">Prioritas Tindak Lanjut</label>
                <input type="text" name="next_priority" class="form-control">
            </div>

            <div class="form-group">
                <label for="recommendations">Rekomendasi</label>
                <textarea name="recommendations" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="additional_notes">Catatan Tambahan</label>
                <textarea name="additional_notes" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Setujui</button>
            <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">Tutup</a>
        </form>
    </div>
</div>
@stop

@section('css')
    <!-- Add any additional CSS here -->
@endsection

@section('js')
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            console.log('Form script loaded'); // Debug line
            
            $('#completionForm').on('submit', function(e) {
                console.log('Form submitted'); // Debug line
                e.preventDefault();
                
                // Show loading state
                $('#submitBtn').prop('disabled', true).html('Mengirim...');
                
                // Get form data
                var formData = $(this).serialize();
                console.log('Form data:', formData); // Debug line
                
                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Success response:', response); // Debug line
                        
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data penyelesaian perbaikan berhasil disimpan',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                window.location.href = "{{ route('maintenance.index') }}";
                            });
                        } else {
                            $('#submitBtn').prop('disabled', false).html('Kirim');
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Terjadi kesalahan saat menyimpan data',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', xhr.responseText); // Debug line
                        
                        // Re-enable submit button
                        $('#submitBtn').prop('disabled', false).html('Kirim');
                        
                        // Parse error message
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        // Show error message
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endsection 