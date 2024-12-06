@extends('adminlte::page')

@section('title', 'Approval Perbaikan')

@section('content_header')
    <h1>Approval Perbaikan</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if($pendingApprovals->isEmpty())
                <div class="text-center py-4">
                    <h4>Tidak ada perbaikan yang menunggu persetujuan</h4>
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
                            @foreach($pendingApprovals as $maintenance)
                                <tr>
                                    <td>{{ $maintenance->asset->name }}</td>
                                    <td>{{ $maintenance->technician_name }}</td>
                                    <td>{{ $maintenance->completion_date }}</td>
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
                                                <span class="badge badge-dark">Rusak Total</span>
                                                @break
                                            @case('temporary_fix')
                                                <span class="badge badge-info">Perbaikan Sementara</span>
                                                @break
                                            @case('pending_parts')
                                                <span class="badge badge-secondary">Menunggu Suku Cadang</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $maintenance->follow_up_priority === 'high' ? 'danger' : ($maintenance->follow_up_priority === 'medium' ? 'warning' : 'info') }}">
                                            {{ ucfirst($maintenance->follow_up_priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal{{ $maintenance->id }}">
                                            Detail
                                        </button>
                                        <form action="{{ route('maintenance.approve', $maintenance->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui perbaikan ini?')">
                                                Setujui
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Detail Modal -->
                                <div class="modal fade" id="detailModal{{ $maintenance->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel">Detail Perbaikan - {{ $maintenance->asset->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Deskripsi Masalah</h6>
                                                        <p>{{ $maintenance->description }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Tindakan yang Dilakukan</h6>
                                                        <p>{{ $maintenance->actions_taken }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <h6>Hasil</h6>
                                                        <p>{{ $maintenance->results }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Parts yang Diganti</h6>
                                                        <p>{{ $maintenance->replaced_parts ?: 'Tidak ada' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <h6>Rekomendasi</h6>
                                                        <p>{{ $maintenance->recommendations ?: 'Tidak ada' }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Catatan Tambahan</h6>
                                                        <p>{{ $maintenance->additional_notes ?: 'Tidak ada' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <h6>Total Biaya</h6>
                                                        <p>Rp {{ number_format($maintenance->total_cost, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $pendingApprovals->links() }}
            @endif
        </div>
    </div>
@endsection 