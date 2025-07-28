<x-app-layout title="Penyesuaian Persediaan - Flopac.id" icon='<i data-lucide="refresh-cw" class="me-3"></i> Penyesuaian Persediaan'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Data Penyesuaian Persediaan</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Kelola penyesuaian persediaan inventory</p>
            </div>
            <div class="col-md-6 text-end">
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search and Add Button Row -->
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{ route('penyesuaian_persediaan.create') }}" class="btn btn-primary" style="background: linear-gradient(90deg, #4AC8EA 0%, #4AC8EA 100%); border: none;">
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
                                <tr>
                                    <td style="font-weight: 500;">{{ $penyesuaian->no_penyesuaian_persediaan }}</td>
                                    <td>{{ $penyesuaian->tanggal_penyesuaian->format('d/m/Y') }}</td>
                                    <td>{{ $penyesuaian->keterangan ?? '-' }}</td>
                                    <td>{{ $penyesuaian->creator->name }}</td>
                                    <td>{{ $penyesuaian->details->count() }} item</td>
                                    <td>
                                        <div class="btn-group" aria-label="Penyesuaian Persediaan actions">
                                            <a href="{{ route('penyesuaian_persediaan.show', $penyesuaian->id) }}" class="btn btn-sm btn-info">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="eye" style="width: 20px; height: 20px;"></i>
                                                </p>
                                            </a>
                                            <a href="{{ route('penyesuaian_persediaan.edit', $penyesuaian->id) }}" class="btn btn-sm btn-warning">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="edit" style="width: 20px; height: 20px;"></i>
                                                </p>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger ajax-delete-btn"
                                                    data-url="{{ route('penyesuaian_persediaan.destroy', $penyesuaian->id) }}"
                                                    data-name="{{ $penyesuaian->no_penyesuaian_persediaan }}">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="trash-2" style="width: 20px; height: 20px;"></i>
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
    
    @push('styles')
        <x-table-styles />
    @endpush

    @push('scripts')
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

            // Setup AJAX delete functionality
            setupAjaxDelete('.ajax-delete-btn', {
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus penyesuaian persediaan ini?'
            });
        </script>
    @endpush
</x-app-layout>
