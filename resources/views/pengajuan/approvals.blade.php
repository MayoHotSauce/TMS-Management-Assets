@extends('adminlte::page')

@section('title', 'Approval Pengajuan Asset')

@section('content_header')
    <h1>Approval Pengajuan Asset</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengajuan Menunggu Persetujuan</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Asset</th>
                    <th>Kategori</th>
                    <th>Ruangan</th>
                    <th>Harga</th>
                    <th>Pemohon</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->name }}</td>
                    <td>{{ $request->category }}</td>
                    <td>{{ $request->room ? $request->room->name : '-' }}</td>
                    <td>Rp {{ number_format($request->price, 0, ',', '.') }}</td>
                    <td>{{ $request->requester_email }}</td>
                    <td>{{ $request->created_at ? $request->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <form action="{{ route('pengajuan.approve', $request->id) }}" method="POST" class="d-inline approve-btn">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                        </form>
                        <button type="button" 
                                class="btn btn-danger btn-sm reject-btn" 
                                data-toggle="modal" 
                                data-target="#rejectModal{{ $request->id }}" 
                                data-request-id="{{ $request->id }}">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada pengajuan yang menunggu persetujuan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
    <div class="card-footer clearfix">
        {{ $requests->links() }}
    </div>
    @endif
</div>

@foreach($requests as $request)
<div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pengajuan.reject', $request->id) }}" method="POST" id="rejectForm{{ $request->id }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pengajuan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan</label>
                        <textarea name="notes" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // For approve button
            $('.approve-btn').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
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
                        form.submit();
                    }
                });
            });

            // For reject button
            $('.reject-btn').click(function(e) {
                e.preventDefault();
                const requestId = $(this).data('request-id');
                
                Swal.fire({
                    title: 'Konfirmasi Penolakan',
                    text: 'Masukkan alasan penolakan:',
                    input: 'textarea',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Tolak',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Anda harus memasukkan alasan penolakan!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $(`#rejectForm${requestId}`);
                        form.find('textarea[name="notes"]').val(result.value);
                        form.submit();
                    }
                });
            });

            // Show success/error messages if they exist in session
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#28a745'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            @endif
        });
    </script>
@stop