<x-app-layout title="Laporan - Flopac.id" icon='<i data-lucide="file-chart-column" class="me-3"></i> Laporan'>
    <div class="container-fluid">
        <div class="mb-3 text-end">
            <a href="{{ route('laporan.exportPdf') }}" target="_blank" class="btn btn-info" style="border-radius: 8px;">
                <p class="d-flex align-items-center mb-0">
                    <i data-lucide="download" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    Export PDF Laporan
                </p>
            </a>
        </div>

        <div class="mb-3 d-flex justify-end">
            <div class="custom-search-container">
                <input type="text" id="customSearch" class="custom-search-input" placeholder="Search">
                <i data-lucide="search" class="custom-search-icon" style="width: 18px; height: 18px;"></i>
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
                            @forelse($groupedData as $index => $item)
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
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i data-lucide="file-x" style="width: 48px; height: 48px; color: #6b7280; margin-bottom: 1rem;"></i>
                                        <div style="color: #6b7280;">Tidak ada data laporan</div>
                                    </td>
                                </tr>
                            @endforelse
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
                            "order": [[ 1, "desc" ]], // Sort by date column descending
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

                        // Custom search functionality
                        $('#customSearch').on('keyup', function() {
                            table.search(this.value).draw();
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
