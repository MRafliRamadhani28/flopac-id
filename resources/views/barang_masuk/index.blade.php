<x-app-layout title="Barang Masuk - Flopac.id" icon='<i data-lucide="notebook-pen" class="me-3"></i> Barang Masuk'>
    <div class="container-fluid">
        <!-- Search and Add Button Row -->
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{ route('barang_masuk.create') }}" class="btn btn-primary" style="background: var(--color-foreground); border: none;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah Barang Masuk
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
                    <table id="barangMasukTable" class="table table-hover align-middle table-transparent">
                        <thead>
                            <tr>
                                <th style="font-weight: 600; color: var(--color-foreground);">No. Barang Masuk</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Tanggal Masuk</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Keterangan</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Dibuat Oleh</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangMasuks as $barangMasuk)
                                <tr class="clickable-row" data-href="{{ route('barang_masuk.show', $barangMasuk->id) }}" style="cursor: pointer;">
                                    <td style="font-weight: 500;">{{ $barangMasuk->no_barang_masuk }}</td>
                                    <td>{{ $barangMasuk->tanggal_masuk->format('d/m/Y') }}</td>
                                    <td>{{ $barangMasuk->keterangan ?? '-' }}</td>
                                    <td>{{ $barangMasuk->creator->name }}</td>
                                    <td class="action-column">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-danger ajax-delete-btn"
                                                    data-url="{{ route('barang_masuk.destroy', $barangMasuk->id) }}"
                                                    data-name="{{ $barangMasuk->no_barang_masuk }}"
                                                    title="Hapus Barang Masuk">
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
            setTimeout(function() {
                if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                    try {
                        // Destroy existing DataTable if it exists
                        if ($.fn.DataTable.isDataTable('#barangMasukTable')) {
                            $('#barangMasukTable').DataTable().destroy();
                        }
                        
                        var table = $('#barangMasukTable').DataTable({
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
                                { orderable: false, targets: [4] }
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
                            text: 'Apakah Anda yakin ingin menghapus barang masuk ini?'
                        });

                    } catch (error) {
                        console.error('DataTable initialization error:', error);
                    }
                }
            }, 1000);

            setTimeout(function() {
                var alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
                
                var errorAlert = document.getElementById('error-alert');
                if (errorAlert) {
                    errorAlert.style.transition = 'opacity 0.5s';
                    errorAlert.style.opacity = '0';
                    setTimeout(() => errorAlert.remove(), 500);
                }
            }, 3000);
        });
    </script>
</x-app-layout>
