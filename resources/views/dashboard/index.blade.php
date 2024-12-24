@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalAssets }}</h3>
                <p>Total Assets</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $needMaintenance }}</h3>
                <p>Need Maintenance</p>
            </div>
            <div class="icon">
                <i class="fas fa-wrench"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $inMaintenance }}</h3>
                <p>In Maintenance</p>
            </div>
            <div class="icon">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalMaintenance }}</h3>
                <p>Total Maintenance Logs</p>
            </div>
            <div class="icon">
                <i class="fas fa-list"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tambahan Section Ruangan -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Ruangan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($rooms as $room)
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('rooms.show', $room->id) }}" class="room-button">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h4>{{ $room->name }}</h4>
                                        <p>{{ $room->assets_count ?? 0 }} assets</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card fixed-height-card">
            <div class="card-header">
                <h3 class="card-title">Recently Added Daftar Barang</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="30%">Nama Barang</th>
                                <th width="70%">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDaftarBarang as $barang)
                                <tr>
                                    <td>{{ $barang->name }}</td>
                                    <td>{{ $barang->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card fixed-height-card">
            <div class="card-header">
                <h3 class="card-title">Recently Added Maintenance</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="30%">Nama Barang</th>
                                <th width="70%">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMaintenance as $maintenance)
                                <tr>
                                    <td>{{ $maintenance->asset?->name ?? 'No Asset' }}</td>
                                    <td>{{ $maintenance->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style untuk semua small-box */
    .small-box {
        position: relative;
        overflow: hidden;
        min-height: 100px;
        display: flex;
        align-items: center;
    }

    .small-box .icon {
        font-size: 70px;
        position: absolute;
        right: 15px;
        opacity: 0.3;
        z-index: 0;
        height: 100%;
        display: flex;
        align-items: center;
    }
    
    .small-box .inner {
        position: relative;
        z-index: 1;
        padding: 15px;
        width: 100%;
    }

    /* Style khusus untuk room-button */
    .room-button {
        display: block;
        text-decoration: none;
        color: inherit;
    }
    
    .room-button .small-box {
        margin-bottom: 0;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .room-button .small-box .inner {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .room-button .small-box .inner h4 {
        color: white;
        margin: 0;
        padding-bottom: 5px;
        font-size: 24px;
    }
    
    .room-button .small-box .inner p {
        color: rgba(255,255,255,0.8);
        margin: 0;
        font-size: 16px;
    }

    /* Tambahan untuk memastikan konsistensi */
    .small-box h3 {
        font-size: 38px;
        margin: 0;
        padding: 0;
    }

    .small-box p {
        font-size: 16px;
        margin: 0;
    }

    /* Tambahkan style untuk card dengan tinggi tetap */
    .fixed-height-card {
        height: 300px; /* Sesuaikan tinggi yang diinginkan */
        margin-bottom: 20px;
    }

    .fixed-height-card .card-body {
        height: calc(100% - 50px); /* 50px untuk card-header */
        overflow-y: auto;
    }

    .table-responsive {
        height: 100%;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 1;
    }
</style>
@stop