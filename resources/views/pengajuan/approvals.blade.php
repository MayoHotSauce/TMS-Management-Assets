@extends('adminlte::page')

@section('title', 'Persetujuan Pengajuan Asset')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                                    <a href="{{ route('pengajuan.show', $request->id) }}" 
                                       class="btn btn-info btn-sm"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-success btn-sm approve-btn"
                                            data-request-id="{{ $request->id }}"
                                            title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm reject-btn" 
                                            data-request-id="{{ $request->id }}"
                                            title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
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
                                <a href="{{ Storage::url($request->proof_image) }}" 
                                   target="_blank"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-image"></i> Lihat
                                </a>
                            </td>
                            <td>
                                <div class="btn-group" style="gap: 5px;">
                                    <a href="{{ route('pengajuan.show', $request->id) }}" 
                                       class="btn btn-info btn-sm"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-success btn-sm final-approve-btn"
                                            data-request-id="{{ $request->id }}"
                                            title="Setujui Final">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm final-reject-btn" 
                                            data-request-id="{{ $request->id }}"
                                            title="Tolak Final">
                                        <i class="fas fa-times"></i>
                                    </button>
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
            text: "Apakah anda yakin ingin menyetujui pengajuan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pengajuan/${requestId}/approve`;
                
                // Add CSRF token
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

    // Final Approval
    $('.final-approve-btn').click(function() {
        const requestId = $(this).data('request-id');
        
        Swal.fire({
            title: 'Konfirmasi Persetujuan Final',
            text: "Apakah anda yakin ingin menyetujui final pengajuan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Setujui Final!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pengajuan/${requestId}/final-approve`;
                
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
});
</script>
@stop