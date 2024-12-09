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
                                        <form action="{{ route('maintenance.approve', $maintenance->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
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
                                            @default
                                                <span class="badge badge-secondary">{{ $maintenance->equipment_status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $maintenance->follow_up_priority === 'high' ? 'danger' : ($maintenance->follow_up_priority === 'medium' ? 'warning' : 'info') }}">
                                            {{ ucfirst($maintenance->follow_up_priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#finalModal{{ $maintenance->id }}">
                                            Detail
                                        </button>
                                        <form action="{{ route('maintenance.approve', $maintenance->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                        </form>
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

    <!-- Include the modal definitions here (similar to your existing ones) -->
@endsection 