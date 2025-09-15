<x-app-layout title="Manajemen User - Flopac.id" icon='<i data-lucide="users" class="me-3"></i> Manajemen User'>
    <x-form-styles />
    <x-ajax-handler />
    
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal" style="background: linear-gradient(90deg, #4AC8EA 0%, #4AC8EA 100%); border: none;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah User
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
                    <table id="usersTable" class="table table-hover align-middle table-transparent">
                        <thead>
                            <tr>
                                <th style="font-weight: 600; color: var(--color-foreground);">ID</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Nama</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Username</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Email</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Role</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td style="font-weight: 500;">{{ $user->id }}</td>
                                    <td style="font-weight: 500;">{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->roles->first())
                                            @php
                                                $role = $user->roles->first();
                                                $badgeClass = match($role->name) {
                                                    'Owner' => 'bg-success',
                                                    'Persediaan' => 'bg-info',
                                                    'Produksi' => 'bg-warning',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $role->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">No Role</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" aria-label="User actions">
                                            <button type="button"
                                                   class="btn btn-sm btn-warning edit-user-btn"
                                                   data-id="{{ $user->id }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#editUserModal"
                                                   title="Edit User">
                                                <p class="d-flex align-items-center mb-0">
                                                    <i data-lucide="edit" style="width: 20px; height: 20px;"></i>
                                                </p>
                                            </button>
                                        </div>
                                        <div class="btn-group" aria-label="User actions">
                                            <button type="button"
                                                    class="btn btn-sm btn-danger delete-user-btn"
                                                    data-url="{{ route('user.destroy', $user->id) }}"
                                                    data-name="{{ $user->name }}"
                                                    title="Hapus User">
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

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #e5e7eb; background: #ffffff; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem;">
                    <h5 class="modal-title" id="createUserModalLabel" style="color: var(--color-foreground); font-weight: 600; font-size: 1.25rem;">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="plus" class="me-2" style="color: var(--color-foreground);"></i> Tambah User
                        </p>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 2rem; background: #ffffff;">
                    <form id="createUserForm">
                        @csrf
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="create_name" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="create_name" name="name" placeholder="Masukkan nama lengkap" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label for="create_username" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="create_username" name="username" placeholder="Masukkan username" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="create_email" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control minimal-input" id="create_email" name="email" placeholder="Masukkan email" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label for="create_role" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Role <span class="text-danger">*</span></label>
                                <select class="form-select minimal-input" id="create_role" name="role" required>
                                    <option value="">Pilih role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="create_password" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control minimal-input" id="create_password" name="password" placeholder="Masukkan password" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="col-md-6 mb-3">
                                <label for="create_password_confirmation" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control minimal-input" id="create_password_confirmation" name="password_confirmation" placeholder="Konfirmasi password" required>
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
                        <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f3f4; background: #ffffff; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem;">
                    <h5 class="modal-title" id="editUserModalLabel" style="color: var(--color-foreground); font-weight: 600; font-size: 1.25rem;">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="edit" class="me-2" style="color: var(--color-foreground);"></i> Edit User
                        </p>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 2rem; background: #ffffff;">
                    <form id="editUserForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_user_id" name="user_id">
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_name" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="edit_name" name="name" placeholder="Masukkan nama lengkap" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_username" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="edit_username" name="username" placeholder="Masukkan username" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_email" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control minimal-input" id="edit_email" name="email" placeholder="Masukkan email" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_role" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Role <span class="text-danger">*</span></label>
                                <select class="form-select minimal-input" id="edit_role" name="role" required>
                                    <option value="">Pilih role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Password (Optional for edit) -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_password" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Password Baru</label>
                                <input type="password" class="form-control minimal-input" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_password_confirmation" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control minimal-input" id="edit_password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru">
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
                        <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Update User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for all scripts to load
            setTimeout(function() {
                if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
                    try {
                        // Destroy existing DataTable if it exists
                        if ($.fn.DataTable.isDataTable('#usersTable')) {
                            $('#usersTable').DataTable().destroy();
                        }
                        
                        var table = $('#usersTable').DataTable({
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
                            order: [[0, 'asc']],
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
                }
            }, 1000);

            // Modal event handlers
            $('#createUserModal').on('show.bs.modal', function() {
                resetCreateForm();
            });

            $('#editUserModal').on('show.bs.modal', function() {
                resetEditForm();
            });

            // Edit button click handler
            $(document).on('click', '.edit-user-btn', function() {
                const userId = $(this).data('id');
                loadUserData(userId);
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
            $('#createUserModal, #editUserModal').on('input change', '.form-control, .form-select', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('');
            });

            // Auto-hide alert
            setTimeout(function() {
                var alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 3000);

            // Setup AJAX delete handler for users
            setupAjaxDelete('.delete-user-btn', {
                title: 'Konfirmasi Hapus User',
                text: 'Apakah Anda yakin ingin menghapus user ini?',
                onSuccess: function(response) {
                    location.reload();
                }
            });

            // Functions
            function resetCreateForm() {
                $('#createUserForm')[0].reset();
                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            function resetEditForm() {
                $('#editUserForm')[0].reset();
                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            function loadUserData(userId) {
                $.ajax({
                    url: `{{ url('user') }}/${userId}/edit-data`,
                    type: 'GET',
                    success: function(data) {
                        populateEditForm(data);
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memuat data user',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }

            function populateEditForm(data) {
                $('#edit_user_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_username').val(data.username);
                $('#edit_email').val(data.email);
                $('#edit_role').val(data.role);
            }

            function validateCreateForm() {
                let isValid = true;
                
                // Reset all errors
                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                // Validate required fields
                const requiredFields = ['create_name', 'create_username', 'create_email', 'create_role', 'create_password', 'create_password_confirmation'];
                requiredFields.forEach(fieldId => {
                    const field = $(`#${fieldId}`);
                    if (!field.val().trim()) {
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text('Field ini wajib diisi');
                        isValid = false;
                    }
                });

                // Validate password confirmation
                const password = $('#create_password').val();
                const passwordConfirmation = $('#create_password_confirmation').val();
                if (password !== passwordConfirmation) {
                    $('#create_password_confirmation').addClass('is-invalid');
                    $('#create_password_confirmation').siblings('.invalid-feedback').text('Konfirmasi password tidak cocok');
                    isValid = false;
                }
                
                return isValid;
            }

            function validateEditForm() {
                let isValid = true;
                
                // Reset all errors
                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                // Validate required fields (password is optional for edit)
                const requiredFields = ['edit_name', 'edit_username', 'edit_email', 'edit_role'];
                requiredFields.forEach(fieldId => {
                    const field = $(`#${fieldId}`);
                    if (!field.val().trim()) {
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text('Field ini wajib diisi');
                        isValid = false;
                    }
                });

                // Validate password confirmation if password is provided
                const password = $('#edit_password').val();
                const passwordConfirmation = $('#edit_password_confirmation').val();
                if (password && password !== passwordConfirmation) {
                    $('#edit_password_confirmation').addClass('is-invalid');
                    $('#edit_password_confirmation').siblings('.invalid-feedback').text('Konfirmasi password tidak cocok');
                    isValid = false;
                }
                
                return isValid;
            }

            function submitCreateForm() {
                // Show loading
                Swal.fire({
                    title: 'Menyimpan User...',
                    text: 'Sedang memproses data user baru',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const formData = $('#createUserForm').serialize();
                
                $.ajax({
                    url: '{{ route("user.store") }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'User Berhasil Dibuat!',
                            text: 'User baru telah berhasil disimpan ke sistem',
                            icon: 'success',
                            confirmButtonColor: '#4AC8EA'
                        }).then(() => {
                            $('#createUserModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat menyimpan user';
                        
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
                    title: 'Mengupdate User...',
                    text: 'Sedang memproses perubahan data user',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const userId = $('#edit_user_id').val();
                const formData = $('#editUserForm').serialize();
                
                $.ajax({
                    url: `{{ url('user') }}/${userId}`,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'User Berhasil Diupdate!',
                            text: 'Data user telah berhasil diperbarui',
                            icon: 'success',
                            confirmButtonColor: '#4AC8EA'
                        }).then(() => {
                            $('#editUserModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat mengupdate user';
                        
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
