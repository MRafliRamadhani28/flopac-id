<x-app-layout title="Pesanan - Flopac.id" icon='<i data-lucide="shopping-cart" class="me-3"></i> Pesanan'>
    <x-form-styles />
    <x-ajax-handler />
    
    <style>
        .clickable-row {
            transition: background-color 0.2s ease;
        }
        .clickable-row:hover {
            background-color: #f8fafc !important;
            cursor: pointer;
        }
        .table-hover-effect {
            background-color: #e3f2fd !important;
        }
    </style>
    
    <div class="container-fluid">
        <!-- Header and Search Row -->
        <div class="row my-3">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPesananModal" style="background: var(--color-foreground); border: none;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah Pesanan
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

        <!-- Main Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px;">
            <div class="card-body" style="padding: 1.5rem;">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-transparent" id="pesananTable">
                        <thead>
                            <tr>
                                <th style="font-weight: 600; color: var(--color-foreground);">No. Pesanan</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Nama Pelanggan</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Alamat</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Model</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Sumber</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Status</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Update Status</th>
                                <th style="font-weight: 600; color: var(--color-foreground);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanans as $pesanan)
                            <tr data-pesanan-id="{{ $pesanan->id }}" class="clickable-row" title="Klik untuk melihat detail pesanan">
                                <td>
                                    <strong style="font-weight: 500;">{{ $pesanan->no_pesanan }}</strong>
                                </td>
                                <td style="font-weight: 500;">{{ $pesanan->nama_pelanggan }}</td>
                                <td>{{ Str::limit($pesanan->alamat, 30) }}</td>
                                <td>{{ $pesanan->model }}</td>
                                <td>{{ $pesanan->sumber }}</td>
                                <td>
                                    <span class="badge bg-{{ $pesanan->status_color }}">
                                        {{ $pesanan->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if($pesanan->status === 'Pending')
                                        <button type="button" class="btn btn-info update-status-btn"
                                            data-url="{{ route('pesanan.update-status', $pesanan->id) }}" data-status="Diproses" title="Mulai Proses">
                                            <p class="d-flex align-items-center mb-0">
                                                <i data-lucide="refresh-cw" style="width: 16px; height: 16px; margin-right: 4px;"></i> Diproses
                                            </p>
                                        </button>
                                        @endif
                                        @if($pesanan->status === 'Diproses')
                                        <button type="button" class="btn btn-success update-status-btn"
                                            data-url="{{ route('pesanan.update-status', $pesanan->id) }}" data-status="Selesai" title="Selesaikan">
                                            <p class="d-flex align-items-center mb-0">
                                                <i data-lucide="check-check" style="width: 16px; height: 16px; margin-right: 4px;"></i> Selesai
                                            </p>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button"
                                               class="btn btn-warning edit-pesanan-btn"
                                               data-id="{{ $pesanan->id }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#editPesananModal"
                                               title="Edit Pesanan">
                                            <p class="d-flex align-items-center mb-0">
                                                <i data-lucide="edit" style="width: 16px; height: 16px;"></i>
                                            </p>
                                        </button>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger delete-pesanan-btn" data-url="{{ route('pesanan.destroy', $pesanan->id) }}"
                                            title="Hapus Pesanan">
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

    <!-- Create Pesanan Modal -->
    <div class="modal fade" id="createPesananModal" tabindex="-1" aria-labelledby="createPesananModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #e5e7eb; background: #ffffff; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem;">
                    <h5 class="modal-title" id="createPesananModalLabel" style="color: var(--color-foreground); font-weight: 600; font-size: 1.25rem;">
                        <i data-lucide="plus" class="me-2" style="color: var(--color-foreground);"></i> Tambah Pesanan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 2rem; background: #ffffff;">
                    <form id="createPesananForm">
                        @csrf
                        <div class="row">
                            <!-- No. Pesanan Preview -->
                            <div class="col-md-6 mb-3">
                                <label for="nextPesananNumber" class="form-label" style="color: #374151; font-weight: 500; margin-bottom: 0.5rem;">No. Pesanan</label>
                                <div class="form-control-plaintext" style="background: #f9fafb; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.75rem 1rem; color: #1f2937;">
                                    <strong id="nextPesananNumber">Loading...</strong>
                                    <small class="text-muted d-block" style="color: #6b7280; font-size: 0.875rem;">Nomor akan digenerate otomatis</small>
                                </div>
                            </div>

                            <!-- Nama Pelanggan -->
                            <div class="col-md-6 mb-3">
                                <label for="create_nama_pelanggan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Nama Pelanggan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="create_nama_pelanggan" name="nama_pelanggan" placeholder="Masukkan nama pelanggan" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Alamat -->
                            <div class="col-12 mb-3">
                                <label for="create_alamat" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control minimal-input" id="create_alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap pelanggan" required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Model -->
                            <div class="col-md-6 mb-3">
                                <label for="create_model" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Model <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="create_model" name="model" placeholder="Masukkan model produk" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Sumber -->
                            <div class="col-md-6 mb-3">
                                <label for="create_sumber" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Sumber <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="create_sumber" name="sumber" placeholder="Masukkan sumber pesanan" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Tanggal Pesanan -->
                            <div class="col-md-6 mb-3">
                                <label for="create_tanggal_pesanan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Tanggal Pesanan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control minimal-input" id="create_tanggal_pesanan" name="tanggal_pesanan" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Tenggat Pesanan -->
                            <div class="col-md-6 mb-3">
                                <label for="create_tenggat_pesanan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Tenggat Pesanan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control minimal-input" id="create_tenggat_pesanan" name="tenggat_pesanan" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Catatan -->
                            <div class="col-12 mb-3">
                                <label for="create_catatan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Catatan</label>
                                <textarea class="form-control minimal-input" id="create_catatan" name="catatan" rows="4" placeholder="Catatan tambahan untuk pesanan ini (opsional)"></textarea>
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
                        <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Pesanan Modal -->
    <div class="modal fade" id="editPesananModal" tabindex="-1" aria-labelledby="editPesananModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f3f4; background: #ffffff; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem;">
                    <h5 class="modal-title" id="editPesananModalLabel" style="color: var(--color-foreground); font-weight: 600; font-size: 1.25rem;">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="edit" class="me-2" style="color: var(--color-foreground);"></i> Edit Pesanan
                        </p>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 2rem; background: #ffffff;">
                    <form id="editPesananForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_pesanan_id" name="pesanan_id">
                        <div class="row">
                            <!-- No. Pesanan -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_no_pesanan" class="form-label" style="color: #374151; font-weight: 500; margin-bottom: 0.5rem;">No. Pesanan</label>
                                <div class="form-control-plaintext" style="background: #f9fafb; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.75rem 1rem; color: #1f2937;">
                                    <strong id="edit_no_pesanan">-</strong>
                                    <small class="text-muted d-block" style="color: #6b7280; font-size: 0.875rem;">Nomor tidak dapat diubah</small>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_status" class="form-label" style="color: #374151; font-weight: 500; margin-bottom: 0.5rem;">Status Saat Ini</label>
                                <div class="form-control-plaintext" style="background: #f9fafb; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.75rem 1rem; color: #1f2937;">
                                    <span id="edit_status_badge">-</span>
                                    <span id="edit_overdue_badge"></span>
                                </div>
                            </div>

                            <!-- Nama Pelanggan -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama_pelanggan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Nama Pelanggan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="edit_nama_pelanggan" name="nama_pelanggan" placeholder="Masukkan nama pelanggan" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Created By & Processed By Info -->
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Info Pesanan</label>
                                <div class="form-control-plaintext" style="background: #f9fafb; border: 1px solid #e9ecef; border-radius: 12px; padding: 1rem; color: var(--color-foreground);">
                                    <small class="text-muted" id="edit_info" style="color: #6c757d; font-size: 12px;">-</small>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="col-12 mb-3">
                                <label for="edit_alamat" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control minimal-input" id="edit_alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap pelanggan" required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Model -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_model" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Model <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="edit_model" name="model" placeholder="Masukkan model produk" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Sumber -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_sumber" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Sumber <span class="text-danger">*</span></label>
                                <input type="text" class="form-control minimal-input" id="edit_sumber" name="sumber" placeholder="Masukkan sumber pesanan" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Tanggal Pesanan -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_tanggal_pesanan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Tanggal Pesanan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control minimal-input" id="edit_tanggal_pesanan" name="tanggal_pesanan" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Tenggat Pesanan -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_tenggat_pesanan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Tenggat Pesanan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control minimal-input" id="edit_tenggat_pesanan" name="tenggat_pesanan" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Catatan -->
                            <div class="col-12 mb-3">
                                <label for="edit_catatan" class="form-label" style="color: var(--color-foreground); font-weight: 500; margin-bottom: 0.5rem; font-size: 14px;">Catatan</label>
                                <textarea class="form-control minimal-input" id="edit_catatan" name="catatan" rows="4" placeholder="Catatan tambahan untuk pesanan ini (opsional)"></textarea>
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
                        <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Update Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Management Modal -->
    <div class="modal fade" id="stockManagementModal" tabindex="-1" aria-labelledby="stockManagementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f3f4; background: #ffffff; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem;">
                    <h5 class="modal-title" id="stockManagementModalLabel" style="color: var(--color-foreground); font-weight: 600; font-size: 1.25rem;">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="package" class="me-2" style="color: var(--color-foreground);"></i> Kelola Stok Barang - <span id="pesananNumberStock">-</span>
                        </p>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 2rem; background: #ffffff;">
                    <div class="alert alert-info" style="border-left: 4px solid #4AC8EA; background: #f0f9ff; border-radius: 8px;">
                        <div class="d-flex align-items-center">
                            <i data-lucide="info" style="color: #4AC8EA; width: 20px; height: 20px; margin-right: 12px;"></i>
                            <div>
                                <strong>Informasi:</strong> Pilih barang yang akan digunakan untuk pesanan ini. Stok akan dikurangi dari persediaan.
                            </div>
                        </div>
                    </div>

                    <form id="stockManagementForm">
                        @csrf
                        <input type="hidden" id="stock_pesanan_id" name="pesanan_id">
                        <input type="hidden" id="stock_update_url" name="update_url">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="stockTable">
                                <thead style="background: #f8fafc; border-radius: 8px;">
                                    <tr>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Nama Barang</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Warna</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Satuan</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Stok Tersedia</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Jumlah Dipakai</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Sisa Stok</th>
                                    </tr>
                                </thead>
                                <tbody id="stockTableBody">
                                    <!-- Data will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 p-3" style="background: #f9fafb; border-radius: 8px; border-left: 4px solid #fbbf24;">
                            <div class="d-flex align-items-center">
                                <i data-lucide="alert-triangle" style="color: #f59e0b; width: 20px; height: 20px; margin-right: 12px;"></i>
                                <div>
                                    <strong>Catatan:</strong> 
                                    <ul class="mb-0 mt-2">
                                        <li>Pastikan jumlah yang dipakai tidak melebihi stok tersedia</li>
                                        <li>Stok yang dikurangi akan tercatat dalam kolom "dipakai" di tabel persediaan</li>
                                        <li>Status pesanan akan otomatis berubah menjadi "Diproses" setelah stok dikurangi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f3f4; background: #ffffff; border-radius: 0 0 16px 16px; padding: 1.5rem 2rem;">
                    <a href="#" class="btn minimal-btn-secondary" data-bs-dismiss="modal">
                        <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                    </a>
                    <button type="button" class="btn minimal-btn-primary" id="submitStockBtn">
                        <i data-lucide="check" style="margin-right: 8px; width: 20px; height: 20px;"></i> Proses & Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>

                    </div>
            </div>
        </div>
    </div>

    <!-- Production Usage Modal (for completing pesanan) -->
    <div class="modal fade" id="productionUsageModal" tabindex="-1" aria-labelledby="productionUsageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #f1f3f4; background: #ffffff; border-radius: 16px 16px 0 0; padding: 1.5rem 2rem;">
                    <h5 class="modal-title" id="productionUsageModalLabel" style="color: var(--color-foreground); font-weight: 600; font-size: 1.25rem;">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="settings" class="me-2" style="color: var(--color-foreground);"></i> Pemakaian Produksi - <span id="pesananNumberProduction">-</span>
                        </p>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 2rem; background: #ffffff;">
                    <div class="alert alert-info" style="border-left: 4px solid #4AC8EA; background: #f0f9ff; border-radius: 8px;">
                        <div class="d-flex align-items-center">
                            <i data-lucide="info" style="color: #4AC8EA; width: 20px; height: 20px; margin-right: 12px;"></i>
                            <div>
                                <strong>Informasi:</strong> Masukkan jumlah pemakaian produksi yang akan mengurangi stok dari kolom "dipakai". Sisa stok akan dikembalikan ke persediaan.
                            </div>
                        </div>
                    </div>

                    <form id="productionUsageForm">
                        @csrf
                        <input type="hidden" id="production_pesanan_id" name="pesanan_id">
                        <input type="hidden" id="production_update_url" name="update_url">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="productionTable">
                                <thead style="background: #f8fafc; border-radius: 8px;">
                                    <tr>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Nama Barang</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Warna</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Satuan</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Stok Dipakai</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Pemakaian Produksi</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); padding: 1rem;">Sisa Dikembalikan</th>
                                    </tr>
                                </thead>
                                <tbody id="productionTableBody">
                                    <!-- Data will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 p-3" style="background: #f9fafb; border-radius: 8px; border-left: 4px solid #10b981;">
                            <div class="d-flex align-items-center">
                                <i data-lucide="check-circle" style="color: #10b981; width: 20px; height: 20px; margin-right: 12px;"></i>
                                <div>
                                    <strong>Catatan:</strong> 
                                    <ul class="mb-0 mt-2">
                                        <li>Masukkan jumlah yang benar-benar digunakan untuk produksi</li>
                                        <li>Sisa stok akan dikembalikan ke persediaan (kolom "dipakai" akan dikurangi)</li>
                                        <li>Status pesanan akan otomatis berubah menjadi "Selesai"</li>
                                        <li>Jika tidak ada pemakaian produksi, biarkan kolom kosong (0)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f3f4; background: #ffffff; border-radius: 0 0 16px 16px; padding: 1.5rem 2rem;">
                    <a href="#" class="btn minimal-btn-secondary" data-bs-dismiss="modal">
                        <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                    </a>
                    <button type="button" class="btn minimal-btn-primary" id="submitProductionBtn">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="check-check" style="margin-right: 8px; width: 20px; height: 20px;"></i> Selesaikan Pesanan
                        </p>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    $(document).ready(function() {
        setTimeout(function() {
            if ($('#pesananTable').length) {
                if ($.fn.DataTable.isDataTable('#pesananTable')) {
                    $('#pesananTable').DataTable().destroy();
                }
                
                try {
                    const table = $('#pesananTable').DataTable({
                        responsive: true,
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
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
                            { orderable: false, targets: [6] }
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
            }
        }, 500);

        $('#createPesananModal').on('show.bs.modal', function() {
            loadNextPesananNumber();
            resetCreateForm();
            $('#create_tanggal_pesanan').val(new Date().toISOString().split('T')[0]);
        });

        $(document).on('click', '.edit-pesanan-btn', function() {
            const pesananId = $(this).data('id');
            loadPesananData(pesananId);
        });

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

        // Status update handler
        $(document).on('click', '.update-status-btn', function(e) {
            e.preventDefault();
            
            const url = $(this).data('url');
            const status = $(this).data('status');
            
            // If status is "Diproses", show modal for stock management
            if (status === 'Diproses') {
                const pesananId = url.split('/').slice(-2, -1)[0]; // Extract ID from URL
                showStockManagementModal(pesananId, url);
            } else if (status === 'Selesai') {
                // If status is "Selesai", show modal for production usage
                const pesananId = url.split('/').slice(-2, -1)[0]; // Extract ID from URL
                showProductionUsageModal(pesananId, url);
            } else {
                // For other status updates, show confirmation dialog
                Swal.fire({
                    title: 'Update Status',
                    text: `Ubah status pesanan menjadi ${status}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4AC8EA',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Update',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updatePesananStatus(url, status);
                    }
                });
            }
        });

        // Auto-set minimum date for tenggat_pesanan based on tanggal_pesanan
        $('#create_tanggal_pesanan, #edit_tanggal_pesanan').on('change', function() {
            const dateValue = $(this).val();
            const prefix = $(this).attr('id').includes('create') ? 'create' : 'edit';
            $(`#${prefix}_tenggat_pesanan`).attr('min', dateValue);
            
            // Clear tenggat if it's before tanggal
            const tenggatValue = $(`#${prefix}_tenggat_pesanan`).val();
            if (tenggatValue && tenggatValue < dateValue) {
                $(`#${prefix}_tenggat_pesanan`).val('');
                $(`#${prefix}_tenggat_pesanan`).removeClass('is-invalid');
                $(`#${prefix}_tenggat_pesanan`).siblings('.invalid-feedback').text('');
            }
        });

        // Stock management modal handler
        $('#submitStockBtn').on('click', function() {
            if (validateStockForm()) {
                submitStockManagement();
            }
        });

        // Production usage modal handler
        $('#submitProductionBtn').on('click', function() {
            if (validateProductionForm()) {
                submitProductionUsage();
            }
        });

        // Use consistent AJAX delete handler
        setupAjaxDelete('.delete-pesanan-btn', {
            title: 'Konfirmasi Hapus Pesanan',
            text: 'Apakah Anda yakin ingin menghapus pesanan ini?',
            onSuccess: function(response) {
                location.reload();
            }
        });

        // Functions
        function loadNextPesananNumber() {
            $.ajax({
                url: '{{ route("pesanan.next_number") }}',
                type: 'GET',
                success: function(response) {
                    $('#nextPesananNumber').text(response.next_number);
                },
                error: function() {
                    $('#nextPesananNumber').text('PSN-00001');
                }
            });
        }

        function loadPesananData(pesananId) {
            $.ajax({
                url: `/pesanan/${pesananId}/edit`,
                type: 'GET',
                success: function(data) {
                    populateEditForm(data);
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal memuat data pesanan',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }

        function populateEditForm(data) {
            $('#edit_pesanan_id').val(data.id);
            $('#edit_no_pesanan').text(data.no_pesanan);
            $('#edit_nama_pelanggan').val(data.nama_pelanggan);
            $('#edit_alamat').val(data.alamat);
            $('#edit_model').val(data.model);
            $('#edit_sumber').val(data.sumber);
            $('#edit_tanggal_pesanan').val(data.tanggal_pesanan);
            $('#edit_tenggat_pesanan').val(data.tenggat_pesanan);
            $('#edit_catatan').val(data.catatan);
            
            // Status badge
            const statusColors = {
                'Pending': 'warning',
                'Diproses': 'info',
                'Selesai': 'success'
            };
            $('#edit_status_badge').html(`<span class="badge bg-${statusColors[data.status]}">${data.status}</span>`);
            
            // Overdue badge
            if (data.is_overdue) {
                $('#edit_overdue_badge').html('<span class="badge bg-danger ms-2">Terlambat</span>');
            } else {
                $('#edit_overdue_badge').html('');
            }
            
            // Info pesanan
            let infoText = `Dibuat: ${data.creator_name} (${data.created_at})`;
            if (data.processor_name) {
                infoText += `<br>Diproses: ${data.processor_name}`;
            }
            $('#edit_info').html(infoText);
        }

        function resetCreateForm() {
            $('#createPesananForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        function validateCreateForm() {
            let isValid = true;
            
            // Reset all errors
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            // Validate required fields
            const requiredFields = ['create_nama_pelanggan', 'create_alamat', 'create_model', 'create_sumber', 'create_tanggal_pesanan', 'create_tenggat_pesanan'];
            requiredFields.forEach(fieldId => {
                const field = $(`#${fieldId}`);
                if (!field.val().trim()) {
                    field.addClass('is-invalid');
                    field.siblings('.invalid-feedback').text('Field ini wajib diisi');
                    isValid = false;
                }
            });
            
            // Validate dates
            const tanggalPesanan = new Date($('#create_tanggal_pesanan').val());
            const tenggatPesanan = new Date($('#create_tenggat_pesanan').val());
            const today = new Date();

            tanggalPesanan.setHours(0, 0, 0, 0);
            tenggatPesanan.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);
            
            if (tanggalPesanan > today) {
                $('#create_tanggal_pesanan').addClass('is-invalid');
                $('#create_tanggal_pesanan').siblings('.invalid-feedback').text('Tanggal pesanan tidak boleh di masa depan');
                isValid = false;
            }
            
            if (tenggatPesanan < tanggalPesanan) {
                $('#create_tenggat_pesanan').addClass('is-invalid');
                $('#create_tenggat_pesanan').siblings('.invalid-feedback').text('Tenggat pesanan tidak boleh sebelum tanggal pesanan');
                isValid = false;
            }
            
            return isValid;
        }

        function validateEditForm() {
            let isValid = true;
            
            // Reset all errors
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            // Validate required fields
            const requiredFields = ['edit_nama_pelanggan', 'edit_alamat', 'edit_model', 'edit_sumber', 'edit_tanggal_pesanan', 'edit_tenggat_pesanan'];
            requiredFields.forEach(fieldId => {
                const field = $(`#${fieldId}`);
                if (!field.val().trim()) {
                    field.addClass('is-invalid');
                    field.siblings('.invalid-feedback').text('Field ini wajib diisi');
                    isValid = false;
                }
            });
            
            // Validate dates
            const tanggalPesanan = new Date($('#edit_tanggal_pesanan').val());
            const tenggatPesanan = new Date($('#edit_tenggat_pesanan').val());
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (tanggalPesanan > today) {
                $('#edit_tanggal_pesanan').addClass('is-invalid');
                $('#edit_tanggal_pesanan').siblings('.invalid-feedback').text('Tanggal pesanan tidak boleh di masa depan');
                isValid = false;
            }
            
            if (tenggatPesanan < tanggalPesanan) {
                $('#edit_tenggat_pesanan').addClass('is-invalid');
                $('#edit_tenggat_pesanan').siblings('.invalid-feedback').text('Tenggat pesanan tidak boleh sebelum tanggal pesanan');
                isValid = false;
            }
            
            return isValid;
        }

        function submitCreateForm() {
            // Show loading
            Swal.fire({
                title: 'Menyimpan Pesanan...',
                text: 'Sedang memproses data pesanan baru',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const formData = $('#createPesananForm').serialize();
            
            $.ajax({
                url: '{{ route("pesanan.store") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: 'Pesanan Berhasil Dibuat!',
                        text: 'Pesanan baru telah berhasil disimpan ke sistem',
                        icon: 'success',
                        confirmButtonColor: '#4AC8EA'
                    }).then(() => {
                        $('#createPesananModal').modal('hide');
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat menyimpan pesanan';
                    
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
                title: 'Mengupdate Pesanan...',
                text: 'Sedang memproses perubahan data pesanan',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const pesananId = $('#edit_pesanan_id').val();
            const formData = $('#editPesananForm').serialize();
            
            $.ajax({
                url: `/pesanan/${pesananId}`,
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: 'Pesanan Berhasil Diupdate!',
                        text: 'Data pesanan telah berhasil diperbarui',
                        icon: 'success',
                        confirmButtonColor: '#4AC8EA'
                    }).then(() => {
                        $('#editPesananModal').modal('hide');
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat mengupdate pesanan';
                    
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

        function showStockManagementModal(pesananId, updateUrl) {
            // Set modal data
            $('#stock_pesanan_id').val(pesananId);
            $('#stock_update_url').val(updateUrl);
            
            // Load pesanan data for display
            $.ajax({
                url: `/pesanan/${pesananId}/edit`,
                type: 'GET',
                success: function(data) {
                    $('#pesananNumberStock').text(data.no_pesanan);
                },
                error: function() {
                    $('#pesananNumberStock').text('-');
                }
            });
            
            // Load stock data
            loadStockData();
            
            // Show modal
            $('#stockManagementModal').modal('show');
        }

        function loadStockData() {
            // Show loading in table
            $('#stockTableBody').html(`
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2">Memuat data stok...</div>
                    </td>
                </tr>
            `);
            
            $.ajax({
                url: '{{ route("api.persediaan.stock-data") }}',
                type: 'GET',
                success: function(data) {
                    populateStockTable(data);
                },
                error: function() {
                    $('#stockTableBody').html(`
                        <tr>
                            <td colspan="5" class="text-center py-4 text-danger">
                                <i data-lucide="alert-circle" style="width: 24px; height: 24px;"></i>
                                <div class="mt-2">Gagal memuat data stok</div>
                            </td>
                        </tr>
                    `);
                }
            });
        }

        function populateStockTable(stockData) {
            let tableHtml = '';
            
            if (stockData.length === 0) {
                tableHtml = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i data-lucide="package-x" style="width: 24px; height: 24px;"></i>
                            <div class="mt-2">Tidak ada data stok tersedia</div>
                        </td>
                    </tr>
                `;
            } else {
                stockData.forEach(function(item, index) {
                    const stokTersedia = item.stok_tersedia;
                    
                    tableHtml += `
                        <tr>
                            <td style="padding: 1rem;">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <strong style="color: var(--color-foreground);">${item.nama_barang}</strong>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 1rem;">
                                <span class="badge bg-secondary" style="font-size: 14px;">${item.warna}</span>
                            </td>
                            <td style="padding: 1rem;">
                                <span class="badge bg-secondary" style="font-size: 14px;">${item.satuan}</span>
                            </td>
                            <td style="padding: 1rem;">
                                <span class="badge ${stokTersedia > 0 ? 'bg-success' : 'bg-danger'}" style="font-size: 14px;">
                                    ${stokTersedia}
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                <input type="hidden" name="persediaan_ids[]" value="${item.id}">
                                <input type="number" 
                                       class="form-control minimal-input stock-input" 
                                       name="jumlah_dipakai[]" 
                                       id="stock_${item.id}"
                                       min="0" 
                                       max="${stokTersedia}" 
                                       data-available="${stokTersedia}"
                                       data-index="${index}"
                                       placeholder="0"
                                       style="width: 120px;">
                                <div class="invalid-feedback"></div>
                            </td>
                            <td style="padding: 1rem;">
                                <span class="badge bg-info sisa-stok" id="sisa_${item.id}" style="font-size: 14px;">
                                    ${stokTersedia}
                                </span>
                            </td>
                        </tr>
                    `;
                });
            }
            
            $('#stockTableBody').html(tableHtml);
            
            // Add event listeners for stock input changes
            $('.stock-input').on('input', function() {
                const availableStock = parseInt($(this).data('available'));
                // Handle empty value: convert to 0 for calculation
                const inputValue = parseInt($(this).val() || '0');
                const itemId = $(this).attr('id').replace('stock_', '');
                const sisaStok = availableStock - inputValue;
                
                // Update sisa stok display
                $(`#sisa_${itemId}`).text(sisaStok);
                $(`#sisa_${itemId}`).removeClass('bg-info bg-warning bg-danger');
                
                if (sisaStok < 0) {
                    $(`#sisa_${itemId}`).addClass('bg-danger');
                } else if (sisaStok === 0) {
                    $(`#sisa_${itemId}`).addClass('bg-warning');
                } else {
                    $(`#sisa_${itemId}`).addClass('bg-info');
                }
                
                // Validate input
                if (inputValue > availableStock) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text(`Maksimal ${availableStock}`);
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('');
                }
            });
            
            // Initialize Lucide icons for new content
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function validateStockForm() {
            let isValid = true;
            let hasAnyStock = false;
            
            $('.stock-input').each(function() {
                const availableStock = parseInt($(this).data('available'));
                // Handle empty value: convert to 0 for validation
                const inputValue = parseInt($(this).val() || '0');
                
                if (inputValue > 0) {
                    hasAnyStock = true;
                }
                
                if (inputValue > availableStock) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text(`Maksimal ${availableStock}`);
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('');
                }
            });
            
            if (!hasAnyStock) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Minimal pilih satu barang untuk diproses',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
                return false;
            }
            
            return isValid;
        }

        function submitStockManagement() {
            Swal.fire({
                title: 'Memproses Pesanan...',
                text: 'Sedang mengurangi stok dan mengupdate status pesanan',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Handle empty values - set to 0 if empty
            $('.stock-input').each(function() {
                if ($(this).val() === '' || $(this).val() === null) {
                    $(this).val('0');
                }
            });

            const formData = $('#stockManagementForm').serializeArray();
            
            $.ajax({
                url: '{{ route("pesanan.process-stock") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: 'Pesanan Berhasil Diproses!',
                        text: 'Stok berhasil dikurangi dan status pesanan telah diupdate',
                        icon: 'success',
                        confirmButtonColor: '#4AC8EA'
                    }).then(() => {
                        $('#stockManagementModal').modal('hide');
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat memproses pesanan';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
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

        function updatePesananStatus(url, status) {
            // Show loading
            Swal.fire({
                title: 'Mengupdate Status...',
                text: 'Sedang memproses perubahan status',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    '_method': 'PATCH',
                    'status': status
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Status Diupdate!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#4AC8EA'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat mengupdate status';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
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

        function showProductionUsageModal(pesananId, updateUrl) {
            // Set modal data
            $('#production_pesanan_id').val(pesananId);
            $('#production_update_url').val(updateUrl);
            
            // Load pesanan data for display
            $.ajax({
                url: `/pesanan/${pesananId}/edit`,
                type: 'GET',
                success: function(data) {
                    $('#pesananNumberProduction').text(data.no_pesanan);
                },
                error: function() {
                    $('#pesananNumberProduction').text('-');
                }
            });
            
            // Load production usage data
            loadProductionUsageData(pesananId);
            
            // Show modal
            $('#productionUsageModal').modal('show');
        }

        function loadProductionUsageData(pesananId) {
            // Show loading in table
            $('#productionTableBody').html(`
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2">Memuat data pemakaian...</div>
                    </td>
                </tr>
            `);
            
            $.ajax({
                url: '{{ route("pesanan.production-usage-data") }}',
                type: 'GET',
                data: { pesanan_id: pesananId },
                success: function(data) {
                    populateProductionTable(data);
                },
                error: function() {
                    $('#productionTableBody').html(`
                        <tr>
                            <td colspan="6" class="text-center py-4 text-danger">
                                <i data-lucide="alert-circle" style="width: 24px; height: 24px;"></i>
                                <div class="mt-2">Gagal memuat data pemakaian</div>
                            </td>
                        </tr>
                    `);
                }
            });
        }

        function populateProductionTable(usageData) {
            let tableHtml = '';
            
            if (usageData.length === 0) {
                tableHtml = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i data-lucide="package-x" style="width: 24px; height: 24px;"></i>
                            <div class="mt-2">Tidak ada data pemakaian untuk pesanan ini</div>
                        </td>
                    </tr>
                `;
            } else {
                usageData.forEach(function(item, index) {
                    const stokDipakai = item.jumlah_dipakai;
                    
                    tableHtml += `
                        <tr>
                            <td style="padding: 1rem;">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <strong style="color: var(--color-foreground);">${item.nama_barang}</strong>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 1rem;">
                                <span class="badge bg-secondary" style="font-size: 14px;">${item.warna}</span>
                            </td>
                            <td style="padding: 1rem;">
                                <span class="badge bg-secondary" style="font-size: 14px;">${item.satuan}</span>
                            </td>
                            <td style="padding: 1rem;">
                                <span class="badge bg-info" style="font-size: 14px;">
                                    ${stokDipakai}
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                <input type="hidden" name="persediaan_ids[]" value="${item.persediaan_id}">
                                <input type="number"
                                       class="form-control minimal-input production-input"
                                       name="jumlah_produksi[]"
                                       id="production_${item.persediaan_id}"
                                       min="0"
                                       max="${stokDipakai}"
                                       data-available="${stokDipakai}"
                                       data-index="${index}"
                                       placeholder="0"
                                       style="width: 120px;">
                                <div class="invalid-feedback"></div>
                            </td>
                            <td style="padding: 1rem;">
                                <span class="badge bg-success sisa-dikembalikan" id="sisa_${item.persediaan_id}" style="font-size: 14px;">
                                    ${stokDipakai}
                                </span>
                            </td>
                        </tr>
                    `;
                });
            }
            
            $('#productionTableBody').html(tableHtml);
            
            // Add event listeners for production input changes
            $('.production-input').on('input', function() {
                const availableStock = parseInt($(this).data('available'));
                // Handle empty value: convert to 0 for calculation
                const inputValue = parseInt($(this).val() || '0');
                const itemId = $(this).attr('id').replace('production_', '');
                const sisaDikembalikan = availableStock - inputValue;
                
                // Update sisa dikembalikan display
                $(`#sisa_${itemId}`).text(sisaDikembalikan);
                $(`#sisa_${itemId}`).removeClass('bg-success bg-warning bg-info');
                
                if (sisaDikembalikan === availableStock) {
                    $(`#sisa_${itemId}`).addClass('bg-success'); // All returned
                } else if (sisaDikembalikan > 0) {
                    $(`#sisa_${itemId}`).addClass('bg-info'); // Partial returned
                } else {
                    $(`#sisa_${itemId}`).addClass('bg-warning'); // All used
                }
                
                // Validate input
                if (inputValue > availableStock) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text(`Maksimal ${availableStock}`);
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('');
                }
            });
            
            // Initialize Lucide icons for new content
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function validateProductionForm() {
            let isValid = true;
            
            $('.production-input').each(function() {
                const availableStock = parseInt($(this).data('available'));
                // Handle empty value: convert to 0 for validation
                const inputValue = parseInt($(this).val() || '0');
                
                if (inputValue > availableStock) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text(`Maksimal ${availableStock}`);
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('');
                }
            });
            
            return isValid;
        }

        function submitProductionUsage() {
            Swal.fire({
                title: 'Menyelesaikan Pesanan...',
                text: 'Sedang memproses pemakaian produksi dan mengupdate status pesanan',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Handle empty values - set to 0 if empty
            $('.production-input').each(function() {
                if ($(this).val() === '' || $(this).val() === null) {
                    $(this).val('0');
                }
            });

            const formData = $('#productionUsageForm').serialize();
            
            $.ajax({
                url: '{{ route("pesanan.complete-production") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: 'Pesanan Berhasil Diselesaikan!',
                        text: 'Pemakaian produksi telah diproses dan status pesanan telah diupdate',
                        icon: 'success',
                        confirmButtonColor: '#4AC8EA'
                    }).then(() => {
                        $('#productionUsageModal').modal('hide');
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat menyelesaikan pesanan';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
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

        // Table row click handler for navigation to detail view
        $(document).on('click', '.clickable-row', function(e) {
            if ($(e.target).closest('.btn, button, .dropdown').length > 0) {
                return;
            }

            const pesananId = $(this).data('pesanan-id');
            
            if (pesananId) {
                const detailUrl = `{{ route('pesanan.index') }}/${pesananId}`;
                window.location.href = detailUrl;
            }
        });

        // Add hover effect to table rows
        $(document).on('mouseenter', '.clickable-row', function() {
            $(this).css('cursor', 'pointer');
            $(this).addClass('table-hover-effect');
        });

        $(document).on('mouseleave', '.clickable-row', function() {
            $(this).removeClass('table-hover-effect');
        });
    });
    </script>
    @endpush
</x-app-layout>
