@extends('adminlte::page')

@section('title', 'Detail Persetujuan Akhir')

@section('content_header')
    <h1>Detail Persetujuan Akhir</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h5>Asset: {{ $maintenance->asset->name }}</h5>
        <p><strong>Tanggal Selesai:</strong> {{ $maintenance->completion_date }}</p>
        <p><strong>Teknisi:</strong> {{ $maintenance->technician }}</p>
        <p><strong>Status Peralatan:</strong> {{ $maintenance->equipment_status }}</p>
        <p><strong>Prioritas Tindak Lanjut:</strong> {{ $maintenance->next_priority }}</p>
        <p><strong>Tindakan yang Dilakukan:</strong> {{ $maintenance->actions_taken }}</p>
        <p><strong>Hasil Perbaikan:</strong> {{ $maintenance->repair_result }}</p>
        <p><strong>Part yang Diganti:</strong> {{ $maintenance->replaced_parts }}</p>
        <p><strong>Total Biaya:</strong> Rp {{ number_format($maintenance->total_cost, 2) }}</p>
        <p><strong>Rekomendasi:</strong> {{ $maintenance->recommendations }}</p>
        <p><strong>Catatan Tambahan:</strong> {{ $maintenance->additional_notes }}</p>
        
        <a href="{{ route('maintenance.approvals') }}" class="btn btn-secondary">Kembali</a>
        <form action="{{ route('maintenance.approvals.approve', $maintenance->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success">Setujui</button>
        </form>
    </div>
</div>
@stop 