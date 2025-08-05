<x-app-layout title="Persediaan - Flopac.id" icon='<i data-lucide="layers" class="me-3"></i> Persediaan'>
    <div class="container-fluid">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert" id="info-alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search Row -->
        <div class="row mb-3">
            <div class="col-md-6"></div>
            <div class="col-md-2"></div>
            <div class="col-md-4 text-end">
                <div class="custom-search-container">
                    <input type="text" id="customSearch" class="custom-search-input" placeholder="Search">
                    <i data-lucide="search" class="custom-search-icon" style="width: 18px; height: 18px;"></i>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px;">
            <div class="card-body" style="padding: 1.5rem;">
                <div class="table-responsive">
                    <table id="persediaanTable" class="table table-hover align-middle table-transparent">
                        <thead>
                            <tr>
                                <th rowspan="2" style="color: var(--color-foreground); font-weight: 600; vertical-align: middle;">No</th>
                                <th rowspan="2" style="color: var(--color-foreground); font-weight: 600; vertical-align: middle;">Nama Barang</th>
                                <th rowspan="2" style="color: var(--color-foreground); font-weight: 600; vertical-align: middle;">Warna</th>
                                <th rowspan="2" style="color: var(--color-foreground); font-weight: 600; vertical-align: middle;">Satuan</th>
                                <th colspan="3" style="color: var(--color-foreground); font-weight: 600; text-align: center; border-bottom: 1px solid #eee;">Persediaan</th>
                            </tr>
                            <tr>
                                <th style="color: var(--color-foreground); font-weight: 600; text-align: center;">Safety Stock</th>
                                <th style="color: var(--color-foreground); font-weight: 600; text-align: center;">Total Dipakai</th>
                                <th style="color: var(--color-foreground); font-weight: 600; text-align: center;">Sisa Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($persediaan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="font-weight: 500;">{{ $item->barang->nama_barang }}</td>
                                    <td>{{ $item->barang->warna }}</td>
                                    <td>{{ $item->barang->satuan }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-{{ $item->getSafetyStockColor() }}">
                                            {{ $item->safety_stock }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-secondary">
                                            {{ $item->dipakai ?? 0 }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-{{ $item->stock > $item->safety_stock ? 'success' : ($item->stock > 0 ? 'warning' : 'danger') }}">
                                            {{ $item->stock }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function() {
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');
            const infoAlert = document.getElementById('info-alert');
            
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }
            
            if (errorAlert) {
                errorAlert.style.transition = 'opacity 0.5s';
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 500);
            }
            
            if (infoAlert) {
                infoAlert.style.transition = 'opacity 0.5s';
                infoAlert.style.opacity = '0';
                setTimeout(() => infoAlert.remove(), 500);
            }
        }, 5000);

        // Initialize DataTable
        $(document).ready(function() {
            if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                try {
                    setTimeout(function() {
                        // Destroy existing DataTable if it exists
                        if ($.fn.DataTable.isDataTable('#persediaanTable')) {
                            $('#persediaanTable').DataTable().destroy();
                        }

                        var table = $('#persediaanTable').DataTable({
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
                    }, 100);
                } catch (error) {
                }
            }
        });
    </script>
</x-app-layout>
