<x-app-layout title="Barang - Flopac.id" icon='<i data-lucide="box" class="me-3"></i> Barang'>
    <x-form-styles />
    <x-ajax-handler />
    
    <div class="container-fluid">
        <!-- Search and Add Button Row -->
        <div class="row my-3">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBarangModal" style="background: var(--color-foreground); border: none;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah Barang
                    </p>
                </button>
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
                                <th style="font-weight: 600; color: var(--color-foreground);">No.</th>
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
                                            <button type="button"
                                                   class="btn btn-sm btn-warning edit-barang-btn"
                                                   data-id="{{ $barang->id }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#editBarangModal"
                                                   title="Edit Barang">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="edit" style="width: 20px; height: 20px;"></i>
                                                </p>
                                            </button>
                                        </div>
                                        <div class="btn-group" aria-label="Barang actions">
                                            <button type="button"
                                                    class="btn btn-sm btn-danger delete-barang-btn"
                                                    data-url="{{ route('barang.destroy', $barang->id) }}"
                                                    data-name="{{ $barang->nama_barang }}"
                                                    title="Hapus Barang">
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

    <!-- Create Barang Modal -->
    <div class="modal fade" id="createBarangModal" tabindex="-1" aria-labelledby="createBarangModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #e5e7eb; background: #ffffff; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem;">
                    <h5 class="modal-title" id="createBarangModalLabel" style="color: var(--color-foreground); font-weight: 600; font-size: 1.25rem;">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="plus" class="me-2" style="color: var(--color-foreground);"></i> Tambah Barang
                        </p>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 2rem; background: #ffffff;">
                    <form id="createBarangForm">
                        @csrf
                        <div class="row">
                            <!-- Nama Barang -->
                            <div class="col-md-6 mb-3">
                                <label for="create_nama_barang" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="create_nama_barang" name="nama_barang" placeholder="Masukkan nama barang" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Warna -->
                            <div class="col-md-6 mb-3">
                                <label for="create_warna" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Warna</label>
                                <input type="text" class="form-control minimal-input" id="create_warna" name="warna" placeholder="Masukkan warna barang">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Satuan -->
                            <div class="col-md-6 mb-3">
                                <label for="create_satuan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select minimal-input" id="create_satuan" name="satuan" required>
                                    <option value="">Pilih satuan</option>
                                    <option value="pcs">pcs</option>
                                    <option value="gram">gram</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f3f4; background: #ffffff; border-radius: 0 0 16px 16px; padding: 1.5rem 2rem;">
                    <a href="#" class="btn minimal-btn-secondary" data-bs-dismiss="modal">
                        <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                    </a>
                    <button type="button" class="btn minimal-btn-primary" id="submitCreateBtn">
                        <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan Barang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Barang Modal -->
    <div class="modal fade" id="editBarangModal" tabindex="-1" aria-labelledby="editBarangModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f3f4; background: #ffffff; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem;">
                    <h5 class="modal-title" id="editBarangModalLabel" style="color: var(--color-foreground); font-weight: 600; font-size: 1.25rem;">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="edit" class="me-2" style="color: var(--color-foreground);"></i> Edit Barang
                        </p>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 2rem; background: #ffffff;">
                    <form id="editBarangForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_barang_id" name="barang_id">
                        <div class="row">
                            <!-- Nama Barang -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama_barang" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="edit_nama_barang" name="nama_barang" placeholder="Masukkan nama barang" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Warna -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_warna" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Warna</label>
                                <input type="text" class="form-control minimal-input" id="edit_warna" name="warna" placeholder="Masukkan warna barang">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Satuan -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_satuan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select minimal-input" id="edit_satuan" name="satuan" required>
                                    <option value="">Pilih satuan</option>
                                    <option value="pcs">pcs</option>
                                    <option value="gram">gram</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f3f4; background: #ffffff; border-radius: 0 0 16px 16px; padding: 1.5rem 2rem;">
                    <a href="#" class="btn minimal-btn-secondary" data-bs-dismiss="modal">
                        <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                    </a>
                    <button type="button" class="btn minimal-btn-primary" id="submitEditBtn">
                        <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Update Barang
                    </button>
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

    // Modal event handlers
    $('#createBarangModal').on('show.bs.modal', function() {
        resetCreateForm();
    });

    $('#editBarangModal').on('show.bs.modal', function() {
        resetEditForm();
    });

    // Edit button click handler
    $(document).on('click', '.edit-barang-btn', function() {
        const barangId = $(this).data('id');
        loadBarangData(barangId);
    });

    // Form submission handlers
    $('#submitCreateBtn').on('click', function() {
        if (validateCreateForm()) {
            submitCreateForm();
        }
    });

    $('#submitEditBtn').on('click', function() {
        if (validateEditForm()) {
            submitEditForm();
        }
    });

    // Clear validation errors when typing
    $('#createBarangModal, #editBarangModal').on('input change', '.form-control, .form-select', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // Auto-hide alerts
    setTimeout(function() {
        var alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);

    // Delete handler
    setupAjaxDelete('.delete-barang-btn', {
        title: 'Konfirmasi Hapus Barang',
        text: 'Apakah Anda yakin ingin menghapus barang ini?',
        onSuccess: function(response) {
            location.reload();
        }
    });

    // Functions
    function resetCreateForm() {
        $('#createBarangForm')[0].reset();
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    function resetEditForm() {
        $('#editBarangForm')[0].reset();
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    function loadBarangData(barangId) {
        $.ajax({
            url: `{{ url('barang') }}/${barangId}/edit-data`,
            type: 'GET',
            success: function(data) {
                populateEditForm(data);
            },
            error: function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal memuat data barang',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

    function populateEditForm(data) {
        $('#edit_barang_id').val(data.id);
        $('#edit_nama_barang').val(data.nama_barang);
        $('#edit_warna').val(data.warna);
        $('#edit_satuan').val(data.satuan);
    }

    function validateCreateForm() {
        let isValid = true;
        
        // Reset all errors
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Validate required fields
        const requiredFields = ['create_nama_barang', 'create_satuan'];
        requiredFields.forEach(fieldId => {
            const field = $(`#${fieldId}`);
            if (!field.val().trim()) {
                field.addClass('is-invalid');
                field.siblings('.invalid-feedback').text('Field ini wajib diisi');
                isValid = false;
            }
        });
        
        return isValid;
    }

    function validateEditForm() {
        let isValid = true;
        
        // Reset all errors
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Validate required fields
        const requiredFields = ['edit_nama_barang', 'edit_satuan'];
        requiredFields.forEach(fieldId => {
            const field = $(`#${fieldId}`);
            if (!field.val().trim()) {
                field.addClass('is-invalid');
                field.siblings('.invalid-feedback').text('Field ini wajib diisi');
                isValid = false;
            }
        });
        
        return isValid;
    }

    function submitCreateForm() {
        // Show loading
        Swal.fire({
            title: 'Menyimpan Barang...',
            text: 'Sedang memproses data barang baru',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const formData = $('#createBarangForm').serialize();
        
        $.ajax({
            url: '{{ route("barang.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    title: 'Barang Berhasil Dibuat!',
                    text: 'Barang baru telah berhasil disimpan ke sistem',
                    icon: 'success',
                    confirmButtonColor: '#4AC8EA'
                }).then(() => {
                    $('#createBarangModal').modal('hide');
                    location.reload();
                });
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat menyimpan barang';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        const fieldId = `create_${field}`;
                        $(`#${fieldId}`).addClass('is-invalid');
                        $(`#${fieldId}`).siblings('.invalid-feedback').text(errors[field][0]);
                    });
                    message = 'Mohon periksa kembali data yang diisi';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

    function submitEditForm() {
        // Show loading
        Swal.fire({
            title: 'Mengupdate Barang...',
            text: 'Sedang memproses perubahan data barang',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const barangId = $('#edit_barang_id').val();
        const formData = $('#editBarangForm').serialize();
        
        $.ajax({
            url: `{{ url('barang') }}/${barangId}`,
            type: 'POST',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                Swal.fire({
                    title: 'Barang Berhasil Diupdate!',
                    text: 'Data barang telah berhasil diperbarui',
                    icon: 'success',
                    confirmButtonColor: '#4AC8EA'
                }).then(() => {
                    $('#editBarangModal').modal('hide');
                    location.reload();
                });
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat mengupdate barang';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        const fieldId = `edit_${field}`;
                        $(`#${fieldId}`).addClass('is-invalid');
                        $(`#${fieldId}`).siblings('.invalid-feedback').text(errors[field][0]);
                    });
                    message = 'Mohon periksa kembali data yang diisi';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
</x-app-layout>
