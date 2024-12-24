@extends('adminlte::page')

@section('title', 'Asset Management')

@section('content')
    <h1>Asset Management</h1>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Asset List</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#logoModal">
                    <i class="fas fa-image"></i> Update Logo
                </button>
                <a href="{{ route('barang.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Asset
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Asset Tag</th>
                        <th>Nama Barang</th>
                        <th>Deskripsi</th>
                        <th>Ruangan</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal Pembelian</th>
                        <th>Biaya</th>
                        <th>Riwayat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $item)
                    <tr>
                        <td>{{ $item->asset_tag }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ optional($item->room)->name }}</td>
                        <td>{{ optional($item->category)->name }}</td>
                        <td>
                            @php
                                $statusLabels = [
                                    'siap_dipakai' => 'Siap Dipakai',
                                    'sedang_dipakai' => 'Sedang Dipakai',
                                    'dalam_perbaikan' => 'Dalam Perbaikan',
                                    'rusak' => 'Rusak',
                                    'siap_dipinjam' => 'Siap Dipinjam',
                                    'sedang_dipinjam' => 'Sedang Dipinjam',
                                    'dimusnahkan' => 'Dimusnahkan'
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusClass[$item->status] ?? 'secondary' }}">
                                {{ $statusLabels[$item->status] ?? ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </td>
                        <td>{{ $item->purchase_date }}</td>
                        <td>Rp {{ number_format($item->purchase_cost, 2) }}</td>
                        <td>
                            @php
                                $maintenanceCount = $item->maintenance_count ?? 0;
                            @endphp
                            <span class="badge badge-{{ $maintenanceCount > 0 ? 'info' : 'secondary' }}">
                                {{ $maintenanceCount }} kali
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success print-label" 
                                    onclick="showPrintDialog('{{ $item->name }}', '{{ $item->asset_tag }}', '{{ optional($item->room)->name }}', '{{ $item->purchase_date }}')">
                                <i class="fas fa-print"></i>
                            </button>
                            <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-warning change-status-btn" 
                                    data-toggle="modal" 
                                    data-target="#changeStatusModal" 
                                    data-id="{{ $item->id }}"
                                    data-current-status="{{ $item->status }}">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $barang->links() }}
        </div>
    </div>

    <!-- Logo Upload Modal -->
    <div class="modal fade" id="logoModal" tabindex="-1" role="dialog" aria-labelledby="logoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoModalLabel">Update Company Logo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('company.logo.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="logo">Select New Logo</label>
                            <input type="file" class="form-control-file" id="logo" name="logo" accept="image/*" required>
                        </div>
                        <div id="logo-preview" class="mt-3 text-center">
                            @if(file_exists(public_path('storage/company/logo.png')))
                                <img src="{{ asset('storage/company/logo.png') }}" style="max-width: 200px;">
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload Logo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Print Dialog Modal -->
    <div class="modal fade" id="printDialog" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Print Labels</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Primary Item Section -->
                    <div class="primary-item mb-4">
                        <h6>Primary Item</h6>
                        <div class="form-group">
                            <label>Selected Item: <span id="selectedItemName"></span></label>
                        </div>
                        <div class="form-group">
                            <label for="printQuantity">Number of copies:</label>
                            <input type="number" class="form-control" id="printQuantity" min="1" value="1">
                        </div>
                    </div>

                    <!-- Additional Items Section -->
                    <div class="additional-items">
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="addMoreItems">
                            <label class="form-check-label" for="addMoreItems">Add more items to print</label>
                        </div>
                        
                        <div id="additionalItemsContainer" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Asset ID</th>
                                            <th>Name</th>
                                            <th>Room</th>
                                            <th>Copies</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($barang as $item)
                                        <tr class="additional-item-row" data-asset-id="{{ $item->asset_tag }}">
                                            <td>
                                                <input type="checkbox" class="additional-item-check" 
                                                    data-id="{{ $item->asset_tag }}"
                                                    data-name="{{ $item->name }}"
                                                    data-room="{{ $item->room ? $item->room->name : 'No Room' }}"
                                                    data-year="{{ $item->purchase_date }}">
                                            </td>
                                            <td>{{ $item->asset_tag }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->room ? $item->room->name : 'No Room' }}</td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm additional-quantity" 
                                                    min="1" value="1" style="width: 70px;" disabled>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Print Options -->
                    <div class="print-options mt-4">
                        <div class="form-group">
                            <label for="printLayout">Print Style:</label>
                            <select class="form-control" id="printLayout">
                                <option value="single">Single Label Per Page</option>
                                <option value="fill">Fill Page</option>
                                <option value="merge">Merge All</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="executePrint()">Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Change Modal -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Asset Status</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="changeStatusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="status">New Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="siap_dipakai">Siap Dipakai</option>
                                <option value="sedang_dipakai">Sedang Dipakai</option>
                                <option value="dalam_perbaikan">Dalam Perbaikan</option>
                                <option value="rusak">Rusak</option>
                                <option value="siap_dipinjam">Siap Dipinjam</option>
                                <option value="sedang_dipinjam">Sedang Dipinjam</option>
                                <option value="dimusnahkan">Dimusnahkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
let currentPrintData = null;

function showPrintDialog(name, code, room, year) {
    currentPrintData = { name, code, room, year };
    $('#selectedItemName').text(name + ' (' + code + ')');
    
    // Hide or disable the primary item in additional items list
    $(`.additional-item-row[data-asset-id="${code}"]`).hide(); // Option 1: Hide the row
    // OR
    // $(`.additional-item-row[data-asset-id="${code}"] input`).prop('disabled', true); // Option 2: Disable checkbox
    
    $('#printDialog').modal('show');
}

// Reset the dialog when it's closed
$('#printDialog').on('hidden.bs.modal', function () {
    $('.additional-item-row').show(); // Show all rows again
    // OR
    // $('.additional-item-row input').prop('disabled', false); // Enable all checkboxes
    $('#addMoreItems').prop('checked', false).trigger('change');
});

// Handle additional items checkbox
$('#addMoreItems').change(function() {
    $('#additionalItemsContainer').toggle(this.checked);
    $('.additional-quantity').prop('disabled', !this.checked);
});

// Handle individual item checkboxes
$('.additional-item-check').change(function() {
    $(this).closest('tr').find('.additional-quantity').prop('disabled', !this.checked);
});

function executePrint() {
    const primaryQuantity = parseInt($('#printQuantity').val()) || 1;
    const layout = $('#printLayout').val();
    
    // Collect all items to print
    let itemsToPrint = [{
        ...currentPrintData,
        quantity: primaryQuantity
    }];

    // Add additional selected items
    if ($('#addMoreItems').is(':checked')) {
        $('.additional-item-check:checked').each(function() {
            const $row = $(this).closest('tr');
            itemsToPrint.push({
                name: $(this).data('name'),
                code: $(this).data('id'),
                room: $(this).data('room'),
                year: $(this).data('year'),
                quantity: parseInt($row.find('.additional-quantity').val()) || 1
            });
        });
    }

    printLabels(itemsToPrint, layout);
    $('#printDialog').modal('hide');
}

function printLabels(items, layout) {
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    const logoPath = "{{ asset('storage/company/logo.png') }}";
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                @page {
                    size: A4;
                    margin: 5mm;
                }
                body {
                    margin: 0;
                    padding: 0;
                }
                .page {
                    width: 210mm;
                    min-height: 297mm;
                    padding: 5mm 0;
                    margin: 0 auto;
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                    align-content: flex-start;
                    row-gap: 5mm;
                }
                .label-container {
                    width: 85mm;
                    height: 32mm;
                    border: 1px solid #660066;
                    page-break-inside: avoid;
                    margin: 0;
                }
                .label-content {
                    height: 100%;
                    display: flex;
                }
                .logo-section {
                    width: 28mm;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-right: 1px solid #660066;
                    padding: 2mm;
                }
                .logo-section img {
                    width: 22mm;
                    height: 22mm;
                    object-fit: contain;
                }
                .info-section {
                    flex: 1;
                    padding: 1mm 2mm;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }
                .company-name {
                    font-family: Arial, sans-serif;
                    font-size: 10px;
                    font-weight: bold;
                    text-align: center;
                    border-bottom: 1px solid #660066;
                    padding: 0.5mm 0;
                    margin-bottom: 0.5mm;
                }
                .info-rows {
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    height: 20mm;
                }
                .info-row {
                    font-family: Arial, sans-serif;
                    font-size: 9px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 1px solid #660066;
                    padding: 0.5mm 0;
                    margin: 0;
                    height: 4.5mm;
                }
                .info-row:last-child {
                    border-bottom: none;
                }
                .info-label {
                    font-weight: bold;
                }
                .info-value {
                    text-align: right;
                }
                @media print {
                    body {
                        width: 210mm;
                    }
                    .page {
                        page-break-after: always;
                    }
                    .label-container {
                        break-inside: avoid;
                    }
                }
            </style>
        </head>
        <body>
    `);

    let labelsHtml = '';
    let labelCount = 0;
    const labelsPerPage = layout === 'single' ? 1 : 8;

    items.forEach(item => {
        for (let i = 0; i < item.quantity; i++) {
            if (labelCount > 0 && labelCount % labelsPerPage === 0 && layout !== 'merge') {
                printWindow.document.write(`<div class="page">${labelsHtml}</div>`);
                labelsHtml = '';
            }

            labelsHtml += `
                <div class="label-container">
                    <div class="label-content">
                        <div class="logo-section">
                            <img src="${logoPath}" alt="Company Logo">
                        </div>
                        <div class="info-section">
                            <div class="company-name">PT TECHNOLOGY MULTI SYSTEM</div>
                            <div class="info-rows">
                                <div class="info-row">
                                    <span class="info-label">NAMA BARANG</span>
                                    <span class="info-value">: ${item.name}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">KODE BARANG</span>
                                    <span class="info-value">: ${item.code}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">RUANGAN</span>
                                    <span class="info-value">: ${item.room}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">TAHUN PENGADAAN</span>
                                    <span class="info-value">: ${item.year}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            labelCount++;
        }
    });

    if (labelsHtml) {
        printWindow.document.write(`<div class="page">${labelsHtml}</div>`);
    }

    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function() {
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    };
}

// Initialize event handlers when document is ready
$(document).ready(function() {
    $('#addMoreItems').change();
    $('.additional-item-check').change();

    // Handle status change button click
    $('.change-status-btn').click(function() {
        const id = $(this).data('id');
        const currentStatus = $(this).data('current-status');
        
        // Set the form action URL
        $('#changeStatusForm').attr('action', `/barang/${id}/change-status`);
        
        // Set the current status in the dropdown
        $('#status').val(currentStatus);
    });
});
</script>
@stop