<x-app-layout title="Laporan - Flopac.id" icon='<i data-lucide="file-chart-column" class="me-3"></i> Laporan'>
    <div class="container-fluid">
        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4" style="background: var(--color-background); border-radius: 12px;">
            <div class="card-body" style="padding: 1.5rem;">
                <h6 class="mb-3" style="color: var(--color-foreground); font-weight: 600;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="filter" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                        Filter Laporan
                    </p>
                </h6>
                <form id="filterForm" method="GET" action="{{ route('laporan.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Nama Barang</label>
                            <select name="barang_id" class="form-select" style="border-radius: 8px;">
                                <option value="">Semua Barang</option>
                                @foreach($availableBarang as $barang)
                                    <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                        {{ $barang->nama_barang }} - {{ $barang->warna }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" style="border-radius: 8px;" 
                                   value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" style="border-radius: 8px;" 
                                   value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-info" style="border-radius: 8px;">
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="search" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                                        Filter
                                    </p>
                                </button>
                                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="x" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                                        Reset
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Export & Search Section -->
        <div class="mb-3 d-flex justify-content-end align-items-center">
            <div>
                @if($groupedData->count() > 0)
                    <a href="{{ route('laporan.exportPdf', request()->query()) }}" target="_blank" class="btn btn-info" style="border-radius: 8px;">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="download" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                            Export PDF Laporan
                        </p>
                    </a>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px;">
            <div class="card-body" style="padding: 1.5rem;">
                <div class="table-responsive">
                    <table id="laporanTable" class="table table-hover align-middle table-transparent">
                        <thead>
                            <tr>
                                <th style="color: var(--color-foreground); font-weight: 600;">No</th>
                                <th style="color: var(--color-foreground); font-weight: 600;">Tanggal</th>
                                <th style="color: var(--color-foreground); font-weight: 600;">Nama Barang</th>
                                <th style="color: var(--color-foreground); font-weight: 600;">Warna Barang</th>
                                <th style="color: var(--color-foreground); font-weight: 600;">Satuan</th>
                                <th style="color: var(--color-foreground); font-weight: 600;">Stock Dihabiskan</th>
                                <th style="color: var(--color-foreground); font-weight: 600;">Jumlah Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedData as $index => $item)
                                <tr class="clickable-row" data-href="{{ route('laporan.show', $item->tanggal->format('Y-m-d') . '_' . $item->barang->id) }}" style="cursor: pointer;">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                                    <td style="font-weight: 500;">{{ $item->barang->nama_barang }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->barang->warna }}</span>
                                    </td>
                                    <td>{{ $item->barang->satuan }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-danger">{{ $item->total_stock_used }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-info">{{ $item->total_orders }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Auto hide alerts
            setTimeout(function() {
                $('#success-alert, #info-alert').fadeOut('slow');
            }, 5000);

            // Initialize DataTable with consistent configuration
            if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                try {
                    setTimeout(function() {
                        // Destroy existing DataTable if it exists
                        if ($.fn.DataTable.isDataTable('#laporanTable')) {
                            $('#laporanTable').DataTable().destroy();
                        }

                        var table = $('#laporanTable').DataTable({
                            "language": {
                                "lengthMenu": "Tampilkan _MENU_ entri",
                                "zeroRecords": "Data tidak ditemukan",
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                                "infoEmpty": "Tidak ada data yang tersedia",
                                "infoFiltered": "(difilter dari _MAX_ total data)",
                                "search": "Cari:",
                                "paginate": {
                                    "first": "Pertama",
                                    "last": "Terakhir", 
                                    "next": "Selanjutnya",
                                    "previous": "Sebelumnya"
                                }
                            },
                            "pageLength": 10,
                            "responsive": true,
                            "searching": false, // Disable built-in search since we have custom search
                            "order": [[ 1, "asc" ]], // Sort by date column ascending
                            "drawCallback": function() {
                                // Re-initialize Lucide icons after each draw
                                if (typeof lucide !== 'undefined') {
                                    lucide.createIcons();
                                }
                                
                                // Style the pagination and other elements
                                $('.dataTables_length select').addClass('form-select form-select-sm');
                                $('.dataTables_length').addClass('mb-3');
                                
                                // Style pagination
                                $('.dataTables_paginate').addClass('mt-3');
                                $('.dataTables_info').addClass('mt-3');
                            }
                        });

                        // Row click functionality
                        $('#laporanTable tbody').on('click', 'tr.clickable-row', function() {
                            var href = $(this).data('href');
                            if (href) {
                                window.location.href = href;
                            }
                        });

                        // Add hover effect for clickable rows
                        $('#laporanTable tbody').on('mouseenter', 'tr.clickable-row', function() {
                            $(this).addClass('table-active');
                        }).on('mouseleave', 'tr.clickable-row', function() {
                            $(this).removeClass('table-active');
                        });

                    }, 100);
                } catch (error) {
                    console.log('DataTable initialization error:', error);
                }
            }
        });

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
    @endpush
</x-app-layout>
