@extends('adminlte::page')

@section('title', 'Detail Persetujuan Akhir')

@section('content_header')
    <h1>Detail Persetujuan Akhir</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h5>Asset: {{ $maintenance->asset->name }}</h5>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <p><strong>Tanggal Selesai:</strong> {{ $maintenance->completion_date }}</p>
                <p><strong>Teknisi:</strong> {{ $maintenance->technician_name }}</p>
                <p><strong>Status Peralatan:</strong> 
                    @php
                        $statusMap = [
                            'berfungsi_100' => 'Berfungsi 100%',
                            'berfungsi_sebagian' => 'Berfungsi Sebagian',
                            'perlu_tindak_lanjut' => 'Perlu Tindak Lanjut',
                            'perlu_penggantian' => 'Perlu Penggantian',
                            'rusak' => 'Rusak',
                            'tidak_dapat_diperbaiki' => 'Tidak Dapat Diperbaiki'
                        ];
                    @endphp
                    {{ $statusMap[$maintenance->equipment_status] ?? $maintenance->equipment_status }}
                </p>
                <p><strong>Prioritas Tindak Lanjut:</strong> {{ $maintenance->follow_up_priority }}</p>
                <p><strong>Tindakan yang Dilakukan:</strong> {{ $maintenance->actions_taken }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Hasil Perbaikan:</strong> {{ $maintenance->results }}</p>
                <p><strong>Part yang Diganti:</strong> {{ $maintenance->replaced_parts }}</p>
                <p><strong>Total Biaya:</strong> Rp {{ number_format($maintenance->total_cost, 2) }}</p>
                <p><strong>Rekomendasi:</strong> {{ $maintenance->recommendations ?? 'N/A' }}</p>
                <p><strong>Catatan Tambahan:</strong> {{ $maintenance->additional_notes ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('maintenance.approvals') }}" class="btn btn-secondary">Kembali</a>
            <button type="button" class="btn btn-success" onclick="approveRequest({{ $maintenance->id }})">Setujui</button>
        </div>
    </div>
</div>

@push('js')
<script>
function approveRequest(id) {
    Swal.fire({
        title: 'Konfirmasi',
        text: "Apakah anda yakin ingin menyetujui perbaikan ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Setujui!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('maintenance.approvals.approve', '') }}/" + id,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.href = "{{ route('maintenance.approvals') }}";
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Status perbaikan saat ini: completed. Tidak dapat diproses.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}
</script>
@endpush
@stop 