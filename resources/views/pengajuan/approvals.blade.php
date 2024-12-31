@extends('adminlte::page')

@section('title', 'Persetujuan Pengajuan Asset')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
.modal-image {
    max-width: 100%;
    height: auto;
}
</style>
@stop

@section('content')
<div class="row">
    <!-- First Approvals -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Persetujuan Awal Pengajuan Asset</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Asset</th>
                            <th>Kategori</th>
                            <th>Ruangan</th>
                            <th>Estimasi Biaya</th>
                            <th>Pemohon</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($firstApprovals as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->name }}</td>
                            <td>{{ $request->category }}</td>
                            <td>{{ $request->room ? $request->room->name : '-' }}</td>
                            <td>Rp {{ number_format($request->price, 0, ',', '.') }}</td>
                            <td>{{ $request->requester_email }}</td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" style="gap: 5px;">
                                    @can('view pengajuan')
                                        <a href="{{ route('pengajuan.show', $request->id) }}" 
                                           class="btn btn-info btn-sm"
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan

                                    @can('approve pengajuan')
                                        <button type="button" 
                                                class="btn btn-success btn-sm approve-btn"
                                                data-request-id="{{ $request->id }}"
                                                title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endcan

                                    @can('reject pengajuan')
                                        <button type="button" 
                                                class="btn btn-danger btn-sm reject-btn" 
                                                data-request-id="{{ $request->id }}"
                                                title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada pengajuan yang menunggu persetujuan awal</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Final Approvals -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Persetujuan Final Pengajuan Asset</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Asset</th>
                            <th>Kategori</th>
                            <th>Estimasi Biaya</th>
                            <th>Biaya Aktual</th>
                            <th>Pemohon</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($finalApprovals as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->name }}</td>
                            <td>{{ $request->category }}</td>
                            <td>Rp {{ number_format($request->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($request->final_cost, 0, ',', '.') }}</td>
                            <td>{{ $request->requester_email }}</td>
                            <td>
                                @if($request->proof_image)
                                    <button type="button" 
                                            class="btn btn-sm btn-info preview-image-btn"
                                            data-image-url="{{ Storage::url('proofs/' . $request->proof_image) }}">
                                        <i class="fas fa-image"></i> Lihat
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" style="gap: 5px;">
                                    @can('view pengajuan')
                                        <a href="{{ route('pengajuan.show', $request->id) }}" 
                                           class="btn btn-info btn-sm"
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan

                                    @can('final approve pengajuan')
                                        <button type="button" 
                                                class="btn btn-success btn-sm final-approve-btn"
                                                data-request-id="{{ $request->id }}"
                                                title="Setujui Final">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endcan

                                    @can('final reject pengajuan')
                                        <button type="button" 
                                                class="btn btn-danger btn-sm final-reject-btn" 
                                                data-request-id="{{ $request->id }}"
                                                title="Tolak Final">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada pengajuan yang menunggu persetujuan final</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('pengajuan.reject-modal')

<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Bukti Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="previewImage" class="modal-image">
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // First Approval
    $('.approve-btn').click(function() {
        const requestId = $(this).data('request-id');
        
        Swal.fire({
            title: 'Konfirmasi Persetujuan',
            text: "Apakah Anda yakin ingin menyetujui pengajuan aset ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pengajuan/${requestId}/approve`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Final Approval with AJAX
    $('.final-approve-btn').click(function() {
        const requestId = $(this).data('request-id');
        const button = $(this);
        
        Swal.fire({
            title: 'Konfirmasi Persetujuan Final',
            text: "Apakah Anda yakin ingin memberikan persetujuan final untuk pengajuan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Setujui Final!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/pengajuan/${requestId}/final-approve`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Pengajuan telah disetujui final',
                                icon: 'success'
                            }).then(() => {
                                // Remove the row from the table
                                button.closest('tr').remove();
                                
                                // If table is empty, show empty message
                                if ($('#finalApprovalsTable tbody tr').length === 0) {
                                    $('#finalApprovalsTable tbody').append(
                                        '<tr><td colspan="8" class="text-center">Tidak ada pengajuan yang menunggu persetujuan final</td></tr>'
                                    );
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menyetujui pengajuan',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Image preview modal
    $('.preview-image-btn').click(function(e) {
        e.preventDefault();
        var imageUrl = $(this).data('image-url');
        $('#previewImage').attr('src', imageUrl);
        $('#imagePreviewModal').modal('show');
    });
});
</script>
@stop