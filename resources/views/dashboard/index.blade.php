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

<div class="row">
    <div class="col-12">
        <h4>Maintenance Due Soon</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Description</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenances as $maintenance)
                <tr>
                    <td>{{ $maintenance->asset_id }}</td>
                    <td>{{ $maintenance->description }}</td>
                    <td>{{ \Carbon\Carbon::parse($maintenance->due_date)->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop 