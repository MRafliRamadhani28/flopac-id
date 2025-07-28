<x-app-layout title="Edit Barang - Flopac.id" icon='<i data-lucide="edit" class="me-3"></i> Edit Barang'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Edit Barang</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Edit data barang: {{ $barang->nama_barang }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm form-card">
            <div class="card-body">
                <form id="barangEditForm" action="{{ route('barang.update', $barang->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <!-- Nama Barang -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control minimal-input @error('nama_barang') is-invalid @enderror" 
                                       id="nama_barang" 
                                       name="nama_barang" 
                                       value="{{ old('nama_barang', $barang->nama_barang) }}"
                                       maxlength="100"
                                       placeholder="Nama Barang"
                                       required>
                                <label for="nama_barang">Nama Barang *</label>
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Warna -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control minimal-input @error('warna') is-invalid @enderror" 
                                       id="warna" 
                                       name="warna" 
                                       value="{{ old('warna', $barang->warna) }}"
                                       maxlength="50"
                                       placeholder="Warna">
                                <label for="warna">Warna (opsional)</label>
                                @error('warna')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Satuan -->
                        <div class="col-md-6">
                            <div class="minimal-select-container">
                                <label for="satuan" class="minimal-label">Satuan *</label>
                                <select class="form-select minimal-select @error('satuan') is-invalid @enderror" 
                                        id="satuan" 
                                        name="satuan" 
                                        required>
                                    <option value="">Pilih Satuan</option>
                                    <option value="pcs" {{ old('satuan', $barang->satuan) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="gram" {{ old('satuan', $barang->satuan) == 'gram' ? 'selected' : '' }}>Gram</option>
                                </select>
                                @error('satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <a href="{{ route('barang.index') }}" class="btn minimal-btn-secondary">
                            <i data-lucide="x"></i> Batal
                        </a>
                        <button type="submit" id="submitBtn" class="btn minimal-btn-primary">
                            <i data-lucide="save"></i> Update Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-form-styles />
    
    <script>
        $(document).ready(function() {
            // Setup AJAX form handler
            setupAjaxForm('#barangEditForm', {
                loadingTitle: 'Mengupdate Barang...',
                loadingText: 'Sedang memproses perubahan data barang',
                successTitle: 'Barang Berhasil Diupdate!',
                successText: 'Data barang telah diperbarui',
                redirectUrl: '{{ route("barang.index") }}'
            });
        });
    </script>
</x-app-layout>
