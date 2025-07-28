<x-app-layout title="Barang - Flopac.id" icon='<i data-lucide="box" class="me-3"></i> Barang'>
    <div class="container-fluid">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search and Add Button Row -->
        <div class="row my-3">
            <div class="col-md-6">
                <a href="{{ route('barang.create') }}" class="btn btn-primary" style="background: var(--color-foreground); border: none;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah Barang
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
                    <table id="barangTable" class="table table-hover align-middle table-transparent">
                        <thead>
                            <tr>
                                <th style="font-weight: 600; color: var(--color-foreground);">ID</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Nama Barang</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Warna</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Satuan</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $barang)
                                <tr>
                                    <td style="font-weight: 500;">{{ $loop->iteration }}</td>
                                    <td style="font-weight: 500;">{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->warna ?? '-' }}</td>
                                    <td>{{ $barang->satuan }}</td>
                                    <td>
                                        <div class="btn-group" aria-label="Barang actions">
                                            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-warning">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="edit" style="width: 20px; height: 20px;"></i>
                                                </p>
                                            </a>
                                        </div>
                                        <div class="btn-group" aria-label="Barang actions">
                                            <button type="button"
                                                    class="btn btn-sm btn-danger delete-barang-btn"
                                                    data-url="{{ route('barang.destroy', $barang->id) }}"
                                                    data-name="{{ $barang->nama_barang }}">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            try {
                if ($.fn.DataTable.isDataTable('#barangTable')) {
                    $('#barangTable').DataTable().destroy();
                }
                
                var table = $('#barangTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    searching: true,
                    dom: 'lrtip',
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
                    order: [[0, 'asc']],
                    initComplete: function() {
                        $('.dataTables_length select').addClass('form-select form-select-sm');
                        $('.dataTables_length').addClass('mb-3');
                        
                        $('.dataTables_paginate').addClass('mt-3');
                        $('.dataTables_info').addClass('mt-3');
                    }
                });

                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });

                $('.custom-search-icon').on('click', function() {
                    $('#customSearch').focus();
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
    }, 3000);

    setupAjaxDelete('.delete-barang-btn', {
        title: 'Konfirmasi Hapus Barang',
        text: 'Apakah Anda yakin ingin menghapus barang ini?'
    });
});
</script>

@include('components.table-styles')
</x-app-layout>
