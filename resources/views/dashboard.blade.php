<!-- resources/views/dashboard.blade.php -->
@extends('adminlte::page')

@section('title', 'Asset Management Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    <!-- Asset Statistics -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalAssets }}</h3>
                <p>Total Assets</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $assetsNeedingMaintenance }}</h3>
                <p>Need Maintenance</p>
            </div>
            <div class="icon">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
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

<div class="row">
    <!-- Asset Distribution Chart -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Asset Distribution by Room</h3>
            </div>
            <div class="card-body">
                <canvas id="assetsByRoomChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Category Distribution Chart -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Asset Distribution by Category</h3>
            </div>
            <div class="card-body">
                <canvas id="assetsByCategoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Maintenance Due -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Maintenance Due Soon</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table">
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
    
    <!-- Pending Transfers -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pending Transfers</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Asset ID</th>
                            <th>From</th>
                            <th>To</th>
                            <th>