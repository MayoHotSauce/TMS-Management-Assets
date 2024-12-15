@extends('adminlte::page')

@section('title', 'Approval Perbaikan')

@section('content_header')
    <h1>Approval Perbaikan</h1>
@endsection

@section('content')
    <!-- Initial Maintenance Approvals -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Persetujuan Awal Perbaikan</h3>
        </div>
        <div class="card-body">
            @if($initialApprovals->isEmpty())
                <div class="text-center py-3">
                    <p>Tidak ada permintaan perbaikan baru yang menunggu persetujuan</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Asset</th>
                                <th>Tanggal Permintaan</th>
                                <th>Deskripsi</th>
                                <th>Estimasi Biaya</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($initialApprovals as $maintenance)
                                <tr>
                                    <td>{{ $maintenance->asset->name }}</td>
                                    <td>{{ $maintenance->maintenance_date }}</td>
                                    <td>{{ Str::limit($maintenance->description, 50) }}</td>
                                    <td>Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#initialModal{{ $maintenance->id }}">
                                            Detail
                                        </button>
                                        <form action="{{ route('maintenance.approvals.approve', $maintenance->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                        </form>
                                        <form action="{{ route('maintenance.reject', $maintenance->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $initialApprovals->links() }}
            @endif
        </div>
    </div>

    <!-- Final Maintenance Approvals -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Persetujuan Akhir Perbaikan</h3>
        </div>
        <div class="card-body">
            @if($finalApprovals->isEmpty())
                <div class="text-center py-3">
                    <p>Tidak ada perbaikan yang menunggu persetujuan akhir</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Asset</th>
                                <th>Teknisi</th>
                                <th>Tanggal Selesai</th>
                                <th>Status Peralatan</th>
                                <th>Prioritas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($finalApprovals as $maintenance)
                                <tr>
                                    <td>{{ $maintenance->asset->name ?? 'N/A' }}</td>
                                    <td>{{ $maintenance->technician_name ?? 'N/A' }}</td>
                                    <td>{{ $maintenance->completion_date ?? 'N/A' }}</td>
                                    <td>{{ $maintenance->equipment_status ?? '-' }}</td>
                                    <td>{{ $maintenance->follow_up_priority ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('maintenance.approval-detail', $maintenance->id) }}" class="btn btn-sm btn-info">Detail</a>
                                        <button type="button" class="btn btn-sm btn-success" onclick="confirmApprove({{ $maintenance->id }})">Setujui</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $finalApprovals->links() }}
            @endif
        </div>
    </div>

    <!-- Modal Definitions -->
    @foreach($initialApprovals as $maintenance)
        <div class="modal fade" id="initialModal{{ $maintenance->id }}" tabindex="-1" role="dialog" aria-labelledby="initialModalLabel{{ $maintenance->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="initialModalLabel{{ $maintenance->id }}">Detail Permintaan Perbaikan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Asset:</strong> {{ $maintenance->asset->name }}</p>
                        <p><strong>Tanggal Permintaan:</strong> {{ $maintenance->maintenance_date }}</p>
                        <p><strong>Deskripsi:</strong> {{ $maintenance->description }}</p>
                        <p><strong>Estimasi Biaya:</strong> Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($finalApprovals as $maintenance)
        <div class="modal fade" id="finalModal{{ $maintenance->id }}" tabindex="-1" role="dialog" aria-labelledby="finalModalLabel{{ $maintenance->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="finalModalLabel{{ $maintenance->id }}">Detail Perbaikan Selesai</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Asset</th>
                                    <td>{{ $maintenance->asset->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Permintaan</th>
                                    <td>{{ $maintenance->maintenance_date }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td>{{ $maintenance->completion_date }}</td>
                                </tr>
                                <tr>
                                    <th>Teknisi</th>
                                    <td>{{ $maintenance->technician_name }}</td>
                                </tr>
                                <tr>
                                    <th>Tindakan yang Dilakukan</th>
                                    <td>{{ $maintenance->actions_taken }}</td>
                                </tr>
                                <tr>
                                    <th>Hasil Perbaikan</th>
                                    <td>{{ $maintenance->results }}</td>
                                </tr>
                                <tr>
                                    <th>Part yang Diganti</th>
                                    <td>{{ $maintenance->replaced_parts ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Total Biaya</th>
                                    <td>Rp {{ number_format($maintenance->total_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status Peralatan</th>
                                    <td>
                                        @switch($maintenance->equipment_status)
                                            @case('fully_repaired')
                                                <span class="badge badge-success">Sepenuhnya Diperbaiki</span>
                                                @break
                                            @case('partially_repaired')
                                                <span class="badge badge-warning">Sebagian Diperbaiki</span>
                                                @break
                                            @case('needs_replacement')
                                                <span class="badge badge-danger">Perlu Penggantian</span>
                                                @break
                                            @case('beyond_repair')
                                                <span class="badge badge-dark">Tidak Dapat Diperbaiki</span>
                                                @break
                                            @case('temporary_fix')
                                                <span class="badge badge-info">Perbaikan Sementara</span>
                                                @break
                                            @case('pending_parts')
                                                <span class="badge badge-secondary">Menunggu Suku Cadang</span>
                                                @break
                                            @default
                                                {{ $maintenance->equipment_status }}
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>Prioritas Tindak Lanjut</th>
                                    <td>
                                        <span class="badge badge-{{ $maintenance->follow_up_priority === 'high' ? 'danger' : ($maintenance->follow_up_priority === 'medium' ? 'warning' : 'info') }}">
                                            {{ ucfirst($maintenance->follow_up_priority) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Rekomendasi</th>
                                    <td>{{ $maintenance->recommendations ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Catatan Tambahan</th>
                                    <td>{{ $maintenance->additional_notes ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-success approve-maintenance" data-id="{{ $maintenance->id }}">
                            Setujui
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        function confirmApprove(id) {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: "Apakah anda yakin ingin menyetujui perbaikan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ url("/maintenance") }}/' + id + '/approve';
                    
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
        }

        // Check for flash message from controller
        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
        </script>
    @endpush
@endsection 