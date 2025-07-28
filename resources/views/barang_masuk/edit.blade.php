<x-app-layout title="Edit Barang Masuk - Flopac.id" icon='<i data-lucide="package-plus" class="me-3"></i> Edit Barang Masuk'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Edit Barang Masuk</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Edit data barang masuk: {{ $barangMasuk->no_barang_masuk }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('barang_masuk.index') }}" class="minimal-btn-secondary">
                    <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm form-card">
            <div class="card-body">
                <form id="barangMasukForm" action="{{ route('barang_masuk.update', $barangMasuk->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- No Barang Masuk -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control minimal-input" id="no_barang_masuk" 
                                   value="{{ $barangMasuk->no_barang_masuk }}" readonly>
                            <label for="no_barang_masuk">No Barang Masuk</label>
                        </div>

                        <!-- Tanggal Masuk -->
                        <div class="form-floating mb-3">
                            <input type="date" 
                                   class="form-control minimal-input @error('tanggal_masuk') is-invalid @enderror" 
                                   id="tanggal_masuk" 
                                   name="tanggal_masuk" 
                                   value="{{ old('tanggal_masuk', $barangMasuk->tanggal_masuk ? \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('Y-m-d') : '') }}"
                                   required>
                            <label for="tanggal_masuk">Tanggal Masuk *</label>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dibuat Oleh -->
                        <div class="form-floating">
                            <input type="text" class="form-control minimal-input" id="created_by" 
                                   value="{{ $barangMasuk->creator->name }}" readonly>
                            <label for="created_by">Dibuat Oleh</label>
                        </div>
                    </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <!-- Keterangan -->
                            <div class="form-floating">
                                <textarea class="form-control minimal-input @error('keterangan') is-invalid @enderror" 
                                          id="keterangan" 
                                          name="keterangan" 
                                          style="height: 140px"
                                          placeholder="Keterangan">{{ old('keterangan', $barangMasuk->keterangan) }}</textarea>
                                <label for="keterangan">Keterangan</label>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Barang Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Daftar Barang</h6>
                                <button type="button" class="minimal-btn-primary" data-bs-toggle="modal" data-bs-target="#addBarangModal">
                                    <i data-lucide="plus" style="margin-right: 8px; width: 20px; height: 20px;"></i> Tambah Barang
                                </button>
                            </div>

                            <!-- Barang Table -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle table-transparent" id="barangTable">
                                    <thead>
                                        <tr>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Nama Barang</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Warna</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Stock</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Satuan</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Stock Masuk</th>
                                            <th style="font-weight: 600; color: var(--color-foreground); display: none;" id="actionColumn">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="barangTableBody">
                                        <tr id="emptyRow" style="display: none;">
                                            <td colspan="6" class="text-center text-muted">Belum ada barang yang dipilih</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Edit Button (Hidden initially) -->
                            <div class="mt-3" id="editButtonContainer" style="display: none;">
                                <button type="button" class="minimal-btn-secondary" onclick="enableEditMode()">
                                    <i data-lucide="edit" style="margin-right: 8px; width: 20px; height: 20px;"></i> Edit Barang
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <a href="{{ route('barang_masuk.index') }}" class="minimal-btn-secondary">
                            <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                        </a>
                        <button type="submit" id="submitBtn" class="minimal-btn-primary">
                            <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Update Barang Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-form-styles />
    <x-ajax-handler />

<!-- Add Barang Modal -->
<div class="modal fade" id="addBarangModal" tabindex="-1" aria-labelledby="addBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBarangModalLabel">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Bar -->
                <div class="search-container mb-3">
                    <div class="search-input">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" id="searchBarang" class="form-control" placeholder="Cari barang...">
                    </div>
                </div>

                <!-- Barang List -->
                <div class="table-responsive">
                    <table class="table table-hover" id="modalBarangTable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Warna</th>
                                <th>Stock</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="modalBarangTableBody">
                            <!-- Dynamic content -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="pagination-info">
                        <small class="text-muted">Menampilkan <span id="showingStart">0</span> - <span id="showingEnd">0</span> dari <span id="totalItems">0</span> item</small>
                    </div>
                    <nav aria-label="Pagination">
                        <ul class="pagination pagination-sm mb-0" id="paginationControls">
                            <!-- Pagination buttons will be generated by JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

    <style>
        .table-transparent {
            background-color: transparent !important;
        }
        
        .table-transparent th,
        .table-transparent td {
            background-color: transparent !important;
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .table-transparent tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            width: 20px;
            height: 20px;
            color: #6c757d;
            z-index: 1;
        }

        .search-input input {
            padding-left: 44px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .search-input input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Load existing data
    loadExistingData();
    
    // Initialize
    updateTableDisplay();
    loadModalBarang();
    
    // Search functionality
    document.getElementById('searchBarang').addEventListener('input', function() {
        filterModalBarang(this.value);
    });
    
    // Setup AJAX form handler with custom validation
    setupAjaxForm('#barangMasukForm', {
        redirectUrl: '{{ route("barang_masuk.index") }}',
        customValidation: function() {
            // Custom validation: check if at least one barang is selected
            if (selectedBarang.length === 0) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Harap pilih minimal satu barang!',
                    icon: 'warning',
                    confirmButtonColor: '#4AC8EA'
                });
                return false;
            }
            return true;
        },
        customDataPreparation: function(form, formData) {
            // Custom form data preparation
            selectedBarang.forEach((item, index) => {
                formData.append(`barang_id[]`, item.id);
                formData.append(`qty[]`, item.stock_masuk);
            });
        }
    });
});

let selectedBarang = [];
let originalBarang = []; // Store original data for cancel functionality
let editMode = false;
let allBarangs = []; // Store all barang data
let filteredBarangs = []; // Store filtered barang data
let currentPage = 1;
const itemsPerPage = 10; // Show 10 items per page

function loadExistingData() {
    // Load existing details from backend
    const existingDetails = @json($barangMasuk->details ?? []);
    const barangs = @json($barangs ?? []);
    
    existingDetails.forEach(detail => {
        const barang = barangs.find(b => b.id == detail.barang_id);
        if (barang) {
            selectedBarang.push({
                id: barang.id,
                nama: barang.nama_barang,
                warna: barang.warna || '-',
                stock: barang.stock || 0,
                satuan: barang.satuan || 'Pcs',
                stock_masuk: detail.qty
            });
        }
    });
}

function updateTableDisplay() {
    const tableBody = document.getElementById('barangTableBody');
    const editButtonContainer = document.getElementById('editButtonContainer');
    const actionColumn = document.getElementById('actionColumn');
    
    if (selectedBarang.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Belum ada barang yang dipilih</td></tr>';
        editButtonContainer.style.display = 'none';
        actionColumn.style.display = 'none';
    } else {
        let html = '';
        selectedBarang.forEach((item, index) => {
            html += `
                <tr>
                    <td>${item.nama}</td>
                    <td>${item.warna}</td>
                    <td>${item.stock}</td>
                    <td>${item.satuan}</td>
                    <td>
                        ${editMode ? 
                            `<input type="number" class="form-control minimal-input" value="${item.stock_masuk}" onchange="updateStock(${index}, this.value)" min="1">` : 
                            item.stock_masuk
                        }
                    </td>
                    <td style="display: ${editMode ? 'table-cell' : 'none'};">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBarang(${index})">
                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        tableBody.innerHTML = html;
        editButtonContainer.style.display = 'block';
        
        // Show/hide action column based on edit mode
        actionColumn.style.display = editMode ? 'table-cell' : 'none';
        
        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
}

function loadModalBarang() {
    // Use barangs data from backend
    allBarangs = @json($barangs ?? []);
    filteredBarangs = [...allBarangs]; // Initially show all items
    currentPage = 1; // Reset to first page
    displayModalBarang();
}

function displayModalBarang() {
    const tableBody = document.getElementById('modalBarangTableBody');
    
    // Calculate pagination
    const totalItems = filteredBarangs.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
    const currentItems = filteredBarangs.slice(startIndex, endIndex);
    
    // Update table content
    let html = '';
    if (currentItems.length === 0) {
        html = '<tr><td colspan="5" class="text-center text-muted">Tidak ada barang ditemukan</td></tr>';
    } else {
        currentItems.forEach(item => {
            const isSelected = selectedBarang.some(selected => selected.id === item.id);
            html += `
                <tr>
                    <td>${item.nama_barang}</td>
                    <td>${item.warna || '-'}</td>
                    <td>${item.stock || 0}</td>
                    <td>${item.satuan || 'Pcs'}</td>
                    <td>
                        <button type="button" class="minimal-btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;" onclick="selectBarang(${item.id}, '${item.nama_barang}', '${item.warna || ''}', ${item.stock || 0}, '${item.satuan || 'Pcs'}')" ${isSelected ? 'disabled' : ''}>
                            ${isSelected ? 'Dipilih' : 'Pilih'}
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    tableBody.innerHTML = html;
    
    // Update pagination info
    updatePaginationInfo(startIndex + 1, endIndex, totalItems);
    
    // Update pagination controls
    updatePaginationControls(currentPage, totalPages);
}

function updatePaginationInfo(start, end, total) {
    document.getElementById('showingStart').textContent = total > 0 ? start : 0;
    document.getElementById('showingEnd').textContent = end;
    document.getElementById('totalItems').textContent = total;
}

function updatePaginationControls(current, total) {
    const paginationControls = document.getElementById('paginationControls');
    let html = '';
    
    // Previous button
    html += `
        <li class="page-item ${current === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${current - 1}); return false;" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;
    
    // Page numbers
    const maxVisible = 5;
    let startPage = Math.max(1, current - Math.floor(maxVisible / 2));
    let endPage = Math.min(total, startPage + maxVisible - 1);
    
    // Adjust startPage if we're near the end
    if (endPage - startPage + 1 < maxVisible) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }
    
    // First page and ellipsis
    if (startPage > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1); return false;">1</a></li>`;
        if (startPage > 2) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    // Page numbers
    for (let i = startPage; i <= endPage; i++) {
        html += `
            <li class="page-item ${i === current ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
            </li>
        `;
    }
    
    // Last page and ellipsis
    if (endPage < total) {
        if (endPage < total - 1) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${total}); return false;">${total}</a></li>`;
    }
    
    // Next button
    html += `
        <li class="page-item ${current === total ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${current + 1}); return false;" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    `;
    
    paginationControls.innerHTML = html;
}

function changePage(page) {
    const totalPages = Math.ceil(filteredBarangs.length / itemsPerPage);
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        displayModalBarang();
    }
}

function filterModalBarang(searchTerm) {
    // Filter barang based on search term
    if (searchTerm.trim() === '') {
        filteredBarangs = [...allBarangs];
    } else {
        filteredBarangs = allBarangs.filter(item => {
            const searchFields = [
                item.nama_barang?.toLowerCase() || '',
                item.warna?.toLowerCase() || '',
                item.satuan?.toLowerCase() || ''
            ];
            return searchFields.some(field => field.includes(searchTerm.toLowerCase()));
        });
    }
    
    // Reset to first page when filtering
    currentPage = 1;
    displayModalBarang();
}

function selectBarang(id, nama, warna, stock, satuan) {
    // Add barang with default stock masuk of 1
    selectedBarang.push({
        id: id,
        nama: nama,
        warna: warna,
        stock: stock,
        satuan: satuan,
        stock_masuk: 1 // Default stock masuk
    });
    
    updateTableDisplay();
    displayModalBarang(); // Refresh modal to update button states with pagination
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addBarangModal'));
    modal.hide();
}

function removeBarang(index) {
    if (confirm('Yakin ingin menghapus barang ini?')) {
        if (index >= 0 && index < selectedBarang.length) {
            selectedBarang.splice(index, 1);
            updateTableDisplay();
            displayModalBarang(); // Refresh modal to update button states with pagination
        }
    }
}

function enableEditMode() {
    // Backup original data before enabling edit mode
    originalBarang = JSON.parse(JSON.stringify(selectedBarang));
    
    editMode = true;
    updateTableDisplay();
    
    // Change button to save mode
    const editButtonContainer = document.getElementById('editButtonContainer');
    editButtonContainer.innerHTML = `
        <button type="button" class="minimal-btn-primary" onclick="saveEditMode()">
            <i data-lucide="check" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan Perubahan
        </button>
        <button type="button" class="minimal-btn-secondary ms-2" onclick="cancelEditMode()">
            <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
        </button>
    `;
    
    // Re-initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function saveEditMode() {
    editMode = false;
    updateTableDisplay();
    
    // Reset button
    const editButtonContainer = document.getElementById('editButtonContainer');
    editButtonContainer.innerHTML = `
        <button type="button" class="minimal-btn-secondary" onclick="enableEditMode()">
            <i data-lucide="edit" style="margin-right: 8px; width: 20px; height: 20px;"></i> Edit Barang
        </button>
    `;
    
    // Re-initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function cancelEditMode() {
    // Restore original data from backup
    selectedBarang = JSON.parse(JSON.stringify(originalBarang));
    
    editMode = false;
    updateTableDisplay();
    displayModalBarang(); // Refresh modal to sync button states
    
    // Reset button
    const editButtonContainer = document.getElementById('editButtonContainer');
    editButtonContainer.innerHTML = `
        <button type="button" class="minimal-btn-secondary" onclick="enableEditMode()">
            <i data-lucide="edit" style="margin-right: 8px; width: 20px; height: 20px;"></i> Edit Barang
        </button>
    `;
    
    // Re-initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function updateStock(index, newStock) {
    if (index >= 0 && index < selectedBarang.length && parseInt(newStock) > 0) {
        selectedBarang[index].stock_masuk = parseInt(newStock);
    }
}
    </script>
</x-app-layout>
