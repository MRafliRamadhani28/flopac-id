<x-app-layout title="Penyesuaian Persediaan - Flopac.id" icon='<i data-lucide="file-pen" class="me-3"></i> Penyesuaian Persediaan'>
    <div class="container-fluid">
        <!-- Search and Add Button Row -->
        <div class="row my-3">
            <div class="col-md-6">
                <a href="{{ route('penyesuaian_persediaan.create') }}" class="btn btn-primary" style="background: var(--color-foreground); border: none;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah Penyesuaian
                    </p>
                </a>
            </div>
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
                    <table id="penyesuaianTable" class="table table-hover align-middle table-transparent">
                        <thead>
                            <tr>
                                <th style="font-weight: 600; color: var(--color-foreground);">No. Penyesuaian</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Tanggal Penyesuaian</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Keterangan</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Dibuat Oleh</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Total Item</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penyesuaianPersediaans as $penyesuaian)
                                <tr class="clickable-row" data-href="{{ route('penyesuaian_persediaan.show', $penyesuaian->id) }}" style="cursor: pointer;">
                                    <td style="font-weight: 500;">{{ $penyesuaian->no_penyesuaian_persediaan }}</td>
                                    <td>{{ $penyesuaian->tanggal_penyesuaian->format('d/m/Y') }}</td>
                                    <td>{{ $penyesuaian->keterangan ?? '-' }}</td>
                                    <td>{{ $penyesuaian->creator->name }}</td>
                                    <td>{{ $penyesuaian->details->count() }} item</td>
                                    <td class="action-column">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-danger ajax-delete-btn"
                                                data-url="{{ route('penyesuaian_persediaan.destroy', $penyesuaian->id) }}"
                                                data-name="{{ $penyesuaian->no_penyesuaian_persediaan }}"
                                                title="Hapus Penyesuaian">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                                </p>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-ajax-handler />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for all scripts to load
            setTimeout(function() {
                if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                    try {
                        // Auto-hide alerts after 5 seconds
                        setTimeout(function() {
                            $('.alert').fadeOut('slow');
                        }, 5000);

                        // Destroy existing DataTable if it exists
                        if ($.fn.DataTable.isDataTable('#penyesuaianTable')) {
                            $('#penyesuaianTable').DataTable().destroy();
                        }
                        
                        var table = $('#penyesuaianTable').DataTable({
                            responsive: true,
                            pageLength: 10,
                            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                            searching: true,
                            dom: 'lrtip', // Hide default search box
                            language: {
                                processing: "Sedang memproses...",
                                search: "Cari:",
                                lengthMenu: "Tampilkan _MENU_ entri",
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                                infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                                infoPostFix: "",
                                loadingRecords: "Sedang memuat...",
                                zeroRecords: "Tidak ditemukan data yang sesuai",
                                emptyTable: "Tidak ada data yang tersedia pada tabel ini",
                                paginate: {
                                    first: "Pertama",
                                    previous: "Sebelumnya",
                                    next: "Selanjutnya",
                                    last: "Terakhir"
                                }
                            },
                            columnDefs: [
                                { orderable: false, targets: [5] }
                            ],
                            order: [[0, 'desc']],
                            initComplete: function() {
                                // Style the length menu
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

                        // Search icon click functionality
                        $('.custom-search-icon').on('click', function() {
                            $('#customSearch').focus();
                        });

                        // Row click functionality (exclude action column)
                        $(document).on('click', '.clickable-row', function(e) {
                            // Don't navigate if click is on action column or its children
                            if ($(e.target).closest('.action-column').length === 0) {
                                window.location = $(this).data('href');
                            }
                        });

                        // Setup AJAX delete functionality
                        setupAjaxDelete('.ajax-delete-btn', {
                            title: 'Konfirmasi Hapus',
                            text: 'Apakah Anda yakin ingin menghapus penyesuaian persediaan ini?'
                        });

                    } catch (error) {
                        console.error('DataTable initialization error:', error);
                    }
                } else {
                    console.error('jQuery or DataTables not loaded');
                }
            }, 500); // Increased delay to ensure all resources are loaded

            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</x-app-layout>
