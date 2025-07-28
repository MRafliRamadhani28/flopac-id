<x-app-layout title="Tambah Penyesuaian Persediaan - Flopac.id" icon='<i data-lucide="file-pen" class="me-3"></i> Tambah Penyesuaian Persediaan'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Tambah Penyesuaian Persediaan</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Tambah data penyesuaian persediaan baru</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('penyesuaian_persediaan.index') }}" class="minimal-btn-secondary">
                    <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm form-card">
            <div class="card-body">
                <form id="penyesuaianForm" action="{{ route('penyesuaian_persediaan.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- No Penyesuaian -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control minimal-input" id="no_penyesuaian_persediaan" 
                                       value="{{ $noPenyesuaian }}" readonly>
                                <label for="no_penyesuaian_persediaan">No Penyesuaian</label>
                            </div>

                            <!-- Tanggal Penyesuaian -->
                            <div class="form-floating mb-3">
                                <input type="date" 
                                       class="form-control minimal-input @error('tanggal_penyesuaian') is-invalid @enderror" 
                                       id="tanggal_penyesuaian" 
                                       name="tanggal_penyesuaian" 
                                       value="{{ old('tanggal_penyesuaian', date('Y-m-d')) }}"
                                       required>
                                <label for="tanggal_penyesuaian">Tanggal Penyesuaian *</label>
                                @error('tanggal_penyesuaian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dibuat Oleh -->
                            <div class="form-floating">
                                <input type="text" class="form-control minimal-input" id="created_by" 
                                       value="{{ Auth::user()->name }}" readonly>
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
                                          placeholder="Keterangan">{{ old('keterangan') }}</textarea>
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
                                <h6 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Daftar Barang Penyesuaian</h6>
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
                                            <th style="font-weight: 600; color: var(--color-foreground);">Stock Saat Ini</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Jenis</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Jumlah</th>
                                            <th style="font-weight: 600; color: var(--color-foreground);">Stock Akhir</th>
                                            <th style="font-weight: 600; color: var(--color-foreground); display: none;" id="actionColumn">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="barangTableBody">
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada barang yang dipilih</td>
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
                        <a href="{{ route('penyesuaian_persediaan.index') }}" class="minimal-btn-secondary">
                            <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                        </a>
                        <button type="submit" class="minimal-btn-primary">
                            <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan Penyesuaian Persediaan
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
                            <input type="text" id="searchBarang" class="form-control minimal-input" placeholder="Cari barang..." style="padding-left: 44px;">
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
            border: 1px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .search-input input:focus {
            border-color: #4AC8EA;
            box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.15);
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>

    @push('scripts')
        <script>
            let selectedBarang = [];
            let editMode = false;
            let allBarangs = [];
            let filteredBarangs = [];
            let currentPage = 1;
            const itemsPerPage = 10;

            // Initialize
            document.addEventListener('DOMContentLoaded', function() {
                loadModalBarang();
                
                // Search functionality
                document.getElementById('searchBarang').addEventListener('input', function() {
                    filterModalBarang(this.value);
                });
                
                // Setup AJAX form handler with custom validation
                setupAjaxForm('#penyesuaianForm', {
                    loadingTitle: 'Menyimpan Penyesuaian Persediaan...',
                    loadingText: 'Sedang memproses data penyesuaian persediaan',
                    successTitle: 'Penyesuaian Persediaan Berhasil Disimpan!',
                    successText: 'Data penyesuaian persediaan telah berhasil disimpan',
                    redirectUrl: '{{ route("penyesuaian_persediaan.index") }}',
                    customValidation: function(form) {
                        // Validate that at least one item is selected
                        if (selectedBarang.length === 0) {
                            Swal.fire({
                                title: 'Perhatian!',
                                text: 'Minimal pilih satu barang!',
                                icon: 'warning',
                                confirmButtonColor: '#4AC8EA'
                            });
                            return false;
                        }
                        
                        // Add selected barang data to form
                        const formElement = form[0];
                        
                        // Remove existing hidden inputs
                        $(formElement).find('input[name="barang_id[]"], input[name="jenis[]"], input[name="jumlah[]"]').remove();
                        
                        // Add current selected barang data
                        selectedBarang.forEach(function(item) {
                            $('<input>').attr({
                                type: 'hidden',
                                name: 'barang_id[]',
                                value: item.id
                            }).appendTo(formElement);
                            
                            $('<input>').attr({
                                type: 'hidden',
                                name: 'jenis[]',
                                value: item.jenis
                            }).appendTo(formElement);
                            
                            $('<input>').attr({
                                type: 'hidden',
                                name: 'jumlah[]',
                                value: item.jumlah
                            }).appendTo(formElement);
                        });
                        
                        return true;
                    }
                });
                
                // Initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });

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
                        const stock = item.persediaan ? item.persediaan.stock || 0 : 0;
                        html += `
                            <tr>
                                <td>${item.nama_barang}</td>
                                <td>${item.warna || '-'}</td>
                                <td>${stock}</td>
                                <td>${item.satuan || 'Pcs'}</td>
                                <td>
                                    <button type="button" class="minimal-btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;" onclick="selectBarang(${item.id}, '${item.nama_barang}', '${item.warna || ''}', ${stock}, '${item.satuan || 'Pcs'}')" ${isSelected ? 'disabled' : ''}>
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

            function selectBarang(id, nama, warna, stockSaatIni, satuan) {
                // Check if barang already selected
                if (selectedBarang.find(item => item.id === id)) {
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Barang sudah dipilih!',
                        icon: 'warning',
                        confirmButtonColor: '#4AC8EA'
                    });
                    return;
                }

                // Add to selected barang
                selectedBarang.push({
                    id: id,
                    nama: nama,
                    warna: warna,
                    stockSaatIni: stockSaatIni,
                    satuan: satuan
                });

                updateBarangTable();
                displayModalBarang(); // Refresh modal to update button states
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addBarangModal'));
                modal.hide();
            }

            function removeBarang(id) {
                if (confirm('Yakin ingin menghapus barang ini?')) {
                    selectedBarang = selectedBarang.filter(item => item.id !== id);
                    updateBarangTable();
                    displayModalBarang(); // Refresh modal to update button states
                }
            }

            function enableEditMode() {
                editMode = true;
                updateBarangTable();
                document.getElementById('editButtonContainer').style.display = 'none';
            }

            function updateBarangTable() {
                const tbody = document.getElementById('barangTableBody');
                const actionColumn = document.getElementById('actionColumn');
                const editButtonContainer = document.getElementById('editButtonContainer');

                // Clear existing rows
                tbody.innerHTML = '';

                if (selectedBarang.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Belum ada barang yang dipilih</td></tr>';
                    actionColumn.style.display = 'none';
                    editButtonContainer.style.display = 'none';
                    return;
                }

                // Show action column and edit button if there are items
                actionColumn.style.display = editMode ? 'table-cell' : 'none';
                editButtonContainer.style.display = editMode ? 'none' : 'block';

                // Add rows for selected barang
                selectedBarang.forEach((barang, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${barang.nama}</td>
                        <td>${barang.warna}</td>
                        <td>${barang.stockSaatIni}</td>
                        <td>
                            <select class="form-select minimal-select" name="jenis_penyesuaian[]" onchange="calculateStock(${index})" required>
                                <option value="">Pilih</option>
                                <option value="penambahan">Penambahan</option>
                                <option value="pengurangan">Pengurangan</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control minimal-input text-center" 
                                   name="stock_penyesuaian[]" min="0" onchange="calculateStock(${index})" required>
                            <input type="hidden" name="barang_id[]" value="${barang.id}">
                        </td>
                        <td>
                            <span class="badge bg-secondary" id="stockAkhir${index}">-</span>
                        </td>
                        <td style="display: ${editMode ? 'table-cell' : 'none'};">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeBarang(${barang.id})">
                                <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // Reinitialize lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            function calculateStock(index) {
                const barang = selectedBarang[index];
                const jenisSelect = document.querySelectorAll('select[name="jenis_penyesuaian[]"]')[index];
                const jumlahInput = document.querySelectorAll('input[name="stock_penyesuaian[]"]')[index];
                const stockAkhirSpan = document.getElementById(`stockAkhir${index}`);

                const jenis = jenisSelect.value;
                const jumlah = parseInt(jumlahInput.value) || 0;
                const stockSaatIni = barang.stockSaatIni;

                if (jenis && jumlah > 0) {
                    let stockAkhir;
                    if (jenis === 'penambahan') {
                        stockAkhir = stockSaatIni + jumlah;
                        stockAkhirSpan.className = 'badge bg-success';
                    } else {
                        stockAkhir = stockSaatIni - jumlah;
                        stockAkhirSpan.className = stockAkhir < 0 ? 'badge bg-danger' : 'badge bg-warning';
                    }
                    stockAkhirSpan.textContent = stockAkhir;
                } else {
                    stockAkhirSpan.className = 'badge bg-secondary';
                    stockAkhirSpan.textContent = '-';
                }
            }

            // Form validation
            document.getElementById('penyesuaianForm').addEventListener('submit', function(e) {
                if (selectedBarang.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Harap pilih minimal satu barang!',
                        icon: 'warning',
                        confirmButtonColor: '#4AC8EA'
                    });
                    return;
                }

                // Check if any stock would go negative
                const jenisSelects = document.querySelectorAll('select[name="jenis_penyesuaian[]"]');
                const jumlahInputs = document.querySelectorAll('input[name="stock_penyesuaian[]"]');
                
                for (let i = 0; i < selectedBarang.length; i++) {
                    const jenis = jenisSelects[i].value;
                    const jumlah = parseInt(jumlahInputs[i].value) || 0;
                    const stockSaatIni = selectedBarang[i].stockSaatIni;
                    
                    if (jenis === 'pengurangan' && jumlah > stockSaatIni) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Perhatian!',
                            text: `Stock tidak boleh kurang dari 0 untuk barang ${selectedBarang[i].nama}!`,
                            icon: 'warning',
                            confirmButtonColor: '#4AC8EA'
                        });
                        return;
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
