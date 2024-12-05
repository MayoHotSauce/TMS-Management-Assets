@extends('adminlte::page')

@section('title', 'Stock Management')

@section('content_header')
    <h1>Stock Management</h1>
@endsection

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Asset List</h2>
        <div>
            <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
            <button type="button" class="btn btn-success" id="confirmBtn">Confirm</button>
        </div>
    </div>

    <form id="stockCheckForm" action="{{ route('stock.update') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Asset Name</th>
                    <th>Description</th>
                    <th>Check</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assets as $asset)
                    <tr>
                        <td>{{ $asset->name }}</td>
                        <td>
                            <textarea 
                                name="descriptions[{{ $asset->id }}]" 
                                class="form-control" 
                                rows="2"
                            >{{ optional($asset->pivot)->description ?? '' }}</textarea>
                        </td>
                        <td>
                            <input 
                                type="checkbox" 
                                name="assets[]" 
                                value="{{ $asset->id }}" 
                                {{ optional($asset->pivot)->is_checked ? 'checked' : '' }}
                            >
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('#updateBtn').click(function() {
        $('#stockCheckForm').attr('action', '{{ route("stock.update") }}').submit();
    });

    $('#confirmBtn').click(function() {
        // First confirmation
        if (!confirm('Are you sure everything has been checked?')) {
            return;
        }

        $.ajax({
            url: '{{ route("stock.confirm") }}',
            type: 'POST',
            data: $('#stockCheckForm').serialize(),
            success: function(response) {
                alert('Stock check completed successfully!');
                
                // Ask about downloading Excel
                if (confirm('Would you like to download as Excel?')) {
                    window.location.href = response.excel_url;
                } else {
                    window.location.href = '{{ route("stock.list") }}';
                }
            },
            error: function() {
                alert('An error occurred while completing the stock check.');
            }
        });
    });
});
</script>
@endsection
