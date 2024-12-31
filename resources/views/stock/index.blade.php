@extends('adminlte::page')

@section('title', 'TMS Management')

@section('content_header')
    <h1>Stock Of Name</h1>
@endsection

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Asset List</h2>
        <div>
            @can('update stock')
                <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
            @endcan
            @can('confirm stock')
                <button type="button" class="btn btn-success" id="confirmBtn">Konfirmasi</button>
            @endcan
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
                        <td class="text-center">
                            <div class="custom-control custom-checkbox">
                                <input 
                                    type="checkbox" 
                                    class="custom-control-input" 
                                    name="assets[]" 
                                    value="{{ $asset->id }}" 
                                    id="checkbox{{ $asset->id }}"
                                    {{ optional($asset->pivot)->is_checked ? 'checked' : '' }}
                                >
                                <label class="custom-control-label" for="checkbox{{ $asset->id }}"></label>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin semua asset sudah dicek?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="modalYesBtn">Ya</button>
                    <button type="button" class="btn btn-danger" id="modalNoBtn">Tidak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Large custom checkbox styling */
        .custom-control-label::before,
        .custom-control-label::after {
            top: -0.5rem;
            left: -1.5rem;
            width: 2rem;
            height: 2rem;
        }

        .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #007bff;
        }

        .custom-checkbox .custom-control-label::before {
            border-radius: 0.25rem;
            border: 2px solid #007bff;
        }

        .custom-control-input:checked ~ .custom-control-label::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
            background-size: 1.5rem;
        }

        .custom-control {
            padding-left: 2.5rem;
            margin: 1rem 0;
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        // Update button handler
        $('#updateBtn').click(function() {
            $('#stockCheckForm').attr('action', '{{ route("stock.update") }}').submit();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Stock berhasil diperbarui!'
            });
        });

        // Confirm button handler
        $('#confirmBtn').click(function() {
            // First submit the form to save any changes
            $.ajax({
                url: '{{ route("stock.update") }}',
                type: 'POST',
                data: $('#stockCheckForm').serialize(),
                success: function() {
                    // After saving changes, show the confirmation modal
                    $('#confirmModal').modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal menyimpan perubahan'
                    });
                }
            });
        });

        // Handle Yes button in modal
        $('#modalYesBtn').click(function() {
            $('#confirmModal').modal('hide');
            $.ajax({
                url: '{{ route("stock.confirm") }}',
                type: 'POST',
                data: $('#stockCheckForm').serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pengecekan stock selesai!',
                        showCancelButton: true,
                        confirmButtonText: 'Download Excel',
                        cancelButtonText: 'Lewati'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = response.excel_url;
                        } else {
                            window.location.href = '{{ route("stock.list") }}';
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat melakukan pengecekan stock'
                    });
                }
            });
        });

        // Handle No button in modal
        $('#modalNoBtn').click(function() {
            $('#confirmModal').modal('hide');
        });
    });
    </script>
@endsection
