<!-- resources/views/dashboard.blade.php -->
@extends('adminlte::page')

@push('css')
<style>
    /* Fix sidebar alignment */
    .wrapper {
        min-height: 100vh;
    }
    
    .main-sidebar {
        top: 0 !important;
        padding-top: 0 !important;
        height: 100vh;
        position: fixed;
    }

    .brand-link {
        height: 57px;
        line-height: 57px;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        border-bottom: 1px solid #4b545c;
    }

    .content-wrapper {
        margin-left: 250px;
        min-height: calc(100vh - 57px) !important;
    }

    .main-header {
        margin-left: 250px !important;
        position: fixed;
        right: 0;
        left: 0;
        z-index: 1000;
    }

    .content {
        padding-top: 57px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            margin-left: 0;
        }
        
        .main-header {
            margin-left: 0 !important;
        }
    }

    .room-card {
        position: relative;
        overflow: hidden;
    }

    .room-icons {
        position: absolute;
        right: 15px;
        bottom: 15px;
    }

    .room-icons img {
        width: 50px;
        height: 50px;
    }
</style>
@endpush

@section('title', 'Asset Management Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Stats Row -->
    <div class="row">
        <div class="col-lg-4 col-md-6 col-12">
            <div class="small-box bg-info elevation-3">
                <div class="inner">
                    <h3>{{ $totalAssets }}</h3>
                    <p>Total Assets</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 col-12">
            <div class="small-box bg-warning elevation-3">
                <div class="inner">
                    <h3>{{ $assetsNeedingMaintenance }}</h3>
                    <p>Need Maintenance</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 col-12">
            <div class="small-box bg-success elevation-3">
                <div class="inner">
                    <h3>{{ $assetsInMaintenance }}</h3>
                    <p>In Maintenance</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wrench"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="card elevation-2">
                <div class="card-header">
                    <h3 class="card-title">Asset Distribution by Room</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="assetsByRoomChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card elevation-2">
                <div class="card-header">
                    <h3 class="card-title">Asset Distribution by Category</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="assetsByCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row">
        <!-- Maintenance Due -->
        <div class="col-md-6">
            <div class="card elevation-2">
                <div class="card-header">
                    <h3 class="card-title">Maintenance Due Soon</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0">
                            <thead>
                                <tr>
                                    <th>Asset ID</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenanceDue as $asset)
                                <tr>
                                    <td>{{ $asset->id }}</td>
                                    <td>{{ $asset->description }}</td>
                                    <td>{{ $asset->next_maintenance }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pending Transfers -->
        <div class="col-md-6">
            <div class="card elevation-2">
                <div class="card-header">
                    <h3 class="card-title">Pending Transfers</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0">
                            <thead>
                                <tr>
                                    <th>Asset ID</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingTransfers as $transfer)
                                <tr>
                                    <td>{{ $transfer->barang->id }}</td>
                                    <td>{{ $transfer->from_room }}</td>
                                    <td>{{ $transfer->to_room }}</td>
                                    <td>{{ $transfer->transfer_date }}</td>
                                    <td>
                                        <form action="{{ route('transfers.approve', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Room Distribution Chart
    new Chart(document.getElementById('assetsByRoomChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($assetsByRoom->pluck('room')) !!},
            datasets: [{
                label: 'Number of Assets',
                data: {!! json_encode($assetsByRoom->pluck('total')) !!},
                backgroundColor: 'rgba(60,141,188,0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Category Distribution Chart
    new Chart(document.getElementById('assetsByCategoryChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($assetsByCategory->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($assetsByCategory->pluck('total')) !!},
                backgroundColor: [
                    '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc',
                    '#d2d6de', '#69d5c9', '#c97dd0', '#d58c3f', '#8f69d5'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endpush