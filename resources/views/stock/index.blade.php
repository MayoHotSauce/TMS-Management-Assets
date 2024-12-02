@extends('adminlte::page')

@section('title', 'Stock Management')

@section('content_header')
    <h1>Stock Management</h1>
@endsection

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Asset List</h2>
        <form action="{{ route('stock.confirm') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Confirm</button>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Asset Name</th>
                <th>Check</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop through assets and create checkboxes --}}
            @foreach ($assets as $asset)
                <tr>
                    <td>{{ $asset->name }}</td>
                    <td>
                        <input type="checkbox" name="assets[]" value="{{ $asset->id }}">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
