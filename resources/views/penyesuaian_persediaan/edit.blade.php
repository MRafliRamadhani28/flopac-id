<x-app-layout title="Edit Penyesuaian Persediaan - Flopac.id" icon='<i data-lucide="edit-3" class="me-3"></i> Edit Penyesuaian'>
    <x-form-styles />
    <x-ajax-handler />
    
    <div class="container-fluid">
        <!-- Header -->
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
                } <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Edit Penyesuaian Persediaan</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">{{ $penyesuaianPersediaan->no_penyesuaian_persediaan }}</p>
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
                <form id="penyesuaianForm" action="{{ route('penyesuaian_persediaan.update', $penyesuaianPersediaan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- No Penyesuaian -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control minimal-input" id="no_penyesuaian_persediaan" 
                                   value="{{ $penyesuaianPersediaan->no_penyesuaian_persediaan }}" readonly>
                            <label for="no_penyesuaian_persediaan">No Penyesuaian</label>
                        </div>

                        <!-- Tanggal Penyesuaian -->
                        <div class="form-floating mb-3">
                            <input type="date" 
                                   class="form-control minimal-input @error('tanggal_penyesuaian') is-invalid @enderror" 
                                   id="tanggal_penyesuaian" 
                                   name="tanggal_penyesuaian" 
                                   value="{{ old('tanggal_penyesuaian', $penyesuaianPersediaan->tanggal_penyesuaian->format('Y-m-d')) }}"
                                   required>
                            <label for="tanggal_penyesuaian">Tanggal Penyesuaian *</label>
                            @error('tanggal_penyesuaian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dibuat Oleh -->
                        <div class="form-floating">
                            <input type="text" class="form-control minimal-input" id="created_by" 
                                   value="{{ $penyesuaianPersediaan->creator->name }}" readonly>
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
                                          placeholder="Keterangan">{{ old('keterangan', $penyesuaianPersediaan->keterangan) }}</textarea>
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
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr style="background-color: rgba(74, 200, 234, 0.1);">
                                            <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600;">Nama Barang</th>
                                            <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600;">Warna</th>
                                            <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Stock Saat Ini</th>
                                            <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Jenis</th>
                                            <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Jumlah</th>
                                            <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Stock Akhir</th>
                                            <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="barangTableBody">
                                        <tr id="emptyRow" style="display: none;">
                                            <td colspan="7" style="border: none; padding: 32px; text-align: center; color: var(--color-muted);">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i data-lucide="package" style="width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                                    <p class="mb-0">Belum ada barang yang dipilih</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <button type="button" class="minimal-btn-secondary" onclick="window.history.back()">
                            <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                        </button>
                        <button type="submit" class="minimal-btn-primary">
                            <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Barang Modal -->
    <div class="modal fade" id="addBarangModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="background: linear-gradient(90deg, #4AC8EA 0%, #4AC8EA 100%); border: none; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title text-white" style="font-weight: 600;">
                        <i data-lucide="package" class="me-2" style="width: 20px; height: 20px;"></i>
                        Pilih Barang
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <div class="table-responsive">
                        <table id="barangModalTable" class="table table-hover align-middle">
                            <thead>
                                <tr style="background-color: rgba(74, 200, 234, 0.1);">
                                    <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600;">Nama Barang</th>
                                    <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600;">Warna</th>
                                    <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600;">Satuan</th>
                                    <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Stock Saat Ini</th>
                                    <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangs as $barang)
                                    <tr>
                                        <td style="border: none; padding: 16px; color: var(--color-foreground);">{{ $barang->nama_barang }}</td>
                                        <td style="border: none; padding: 16px; color: var(--color-foreground);">{{ $barang->warna }}</td>
                                        <td style="border: none; padding: 16px; color: var(--color-foreground);">{{ $barang->satuan }}</td>
                                        <td style="border: none; padding: 16px; color: var(--color-foreground); text-align: center;">
                                            <span class="badge bg-info">{{ $barang->persediaan->stock ?? 0 }}</span>
                                        </td>
                                        <td style="border: none; padding: 16px; text-align: center;">
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="selectBarang({{ $barang->id }}, '{{ $barang->nama_barang }}', '{{ $barang->warna }}', {{ $barang->persediaan->stock ?? 0 }})">
                                                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <x-table-styles />
        <style>
            .minimal-input {
                background: rgba(255, 255, 255, 0.8);
                border: 1px solid rgba(74, 200, 234, 0.3);
                border-radius: 12px;
                padding: 12px 16px;
                font-size: 14px;
                transition: all 0.3s ease;
            }
            .minimal-input:focus {
                background: rgba(255, 255, 255, 0.95);
                border-color: var(--color-primary);
                box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.25);
            }
            .form-floating > label {
                color: var(--color-muted);
                font-size: 14px;
            }
            .table th, .table td {
                vertical-align: middle;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            let selectedBarang = [];

            // Load existing data
            const existingDetails = @json($penyesuaianPersediaan->details);
            
            // Initialize with existing data
            existingDetails.forEach(detail => {
                selectedBarang.push({
                    id: detail.barang_id,
                    nama: detail.barang.nama_barang,
                    warna: detail.barang.warna,
                    stockSaatIni: detail.stock_sebelum,
                    stockPenyesuaian: detail.stock_penyesuaian,
                    jenisPenyesuaian: detail.jenis_penyesuaian
                });
            });

            function selectBarang(id, nama, warna, stockSaatIni) {
                // Check if barang already selected
                if (selectedBarang.find(item => item.id === id)) {
                    alert('Barang sudah dipilih!');
                    return;
                }

                // Add to selected barang
                selectedBarang.push({
                    id: id,
                    nama: nama,
                    warna: warna,
                    stockSaatIni: stockSaatIni
                });

                updateBarangTable();
                $('#addBarangModal').modal('hide');
            }

            function removeBarang(id) {
                selectedBarang = selectedBarang.filter(item => item.id !== id);
                updateBarangTable();
            }

            function updateBarangTable() {
                const tbody = document.getElementById('barangTableBody');
                const emptyRow = document.getElementById('emptyRow');

                if (selectedBarang.length === 0) {
                    emptyRow.style.display = 'table-row';
                    return;
                }

                emptyRow.style.display = 'none';

                // Clear existing rows except empty row
                const existingRows = tbody.querySelectorAll('tr:not(#emptyRow)');
                existingRows.forEach(row => row.remove());

                // Add rows for selected barang
                selectedBarang.forEach((barang, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td style="border: none; padding: 16px; color: var(--color-foreground);">${barang.nama}</td>
                        <td style="border: none; padding: 16px; color: var(--color-foreground);">${barang.warna}</td>
                        <td style="border: none; padding: 16px; color: var(--color-foreground); text-align: center;">
                            <span class="badge bg-info">${barang.stockSaatIni}</span>
                        </td>
                        <td style="border: none; padding: 16px; text-align: center;">
                            <select class="form-select form-select-sm" name="jenis_penyesuaian[]" onchange="calculateStock(${index})" required>
                                <option value="">Pilih</option>
                                <option value="penambahan" ${barang.jenisPenyesuaian === 'penambahan' ? 'selected' : ''}>Penambahan</option>
                                <option value="pengurangan" ${barang.jenisPenyesuaian === 'pengurangan' ? 'selected' : ''}>Pengurangan</option>
                            </select>
                        </td>
                        <td style="border: none; padding: 16px; text-align: center;">
                            <input type="number" class="form-control form-control-sm text-center" 
                                   name="stock_penyesuaian[]" min="0" value="${barang.stockPenyesuaian || ''}" 
                                   onchange="calculateStock(${index})" required>
                            <input type="hidden" name="barang_id[]" value="${barang.id}">
                        </td>
                        <td style="border: none; padding: 16px; text-align: center;">
                            <span class="badge bg-secondary" id="stockAkhir${index}">-</span>
                        </td>
                        <td style="border: none; padding: 16px; text-align: center;">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBarang(${barang.id})">
                                <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                    
                    // Calculate stock for existing data
                    if (barang.stockPenyesuaian && barang.jenisPenyesuaian) {
                        setTimeout(() => calculateStock(index), 100);
                    }
                });

                // Reinitialize lucide icons
                lucide.createIcons();
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

            // Setup AJAX form handler with custom validation
            setupAjaxForm('#penyesuaianForm', '{{ route("penyesuaian_persediaan.index") }}', function() {
                // Custom validation: check if at least one barang is selected
                if (selectedBarang.length === 0) {
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Harap pilih minimal satu barang!',
                        icon: 'warning',
                        confirmButtonColor: '#4AC8EA'
                    });
                    return false;
                }

                // Check if any stock would go negative
                const jenisSelects = document.querySelectorAll('select[name="jenis_penyesuaian[]"]');
                const jumlahInputs = document.querySelectorAll('input[name="stock_penyesuaian[]"]');
                
                for (let i = 0; i < selectedBarang.length; i++) {
                    const jenis = jenisSelects[i].value;
                    const jumlah = parseInt(jumlahInputs[i].value) || 0;
                    const stockSaatIni = selectedBarang[i].stockSaatIni;
                    
                    if (jenis === 'pengurangan' && jumlah > stockSaatIni) {
                        Swal.fire({
                            title: 'Perhatian!',
                            text: `Stock tidak boleh kurang dari 0 untuk barang ${selectedBarang[i].nama}!`,
                            icon: 'warning',
                            confirmButtonColor: '#4AC8EA'
                        });
                        return false;
                    }
                }
                return true;
            });

            $(document).ready(function() {
                // Initialize table with existing data
                updateBarangTable();
                
                // Initialize DataTable for modal
                $('#barangModalTable').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    "responsive": true,
                    "pageLength": 5
                });

                // Initialize Lucide icons
                lucide.createIcons();
            });
        </script>
    @endpush
</x-app-layout>
