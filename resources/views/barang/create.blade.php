<x-app-layout title="Tambah Barang - Flopac.id" icon='<i data-lucide="plus" class="me-3"></i> Tambah Barang'>
    <x-form-styles />
    <x-ajax-handler />
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6"></div>
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
                <form id="barangForm" action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Nama Barang -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control minimal-input @error('nama_barang') is-invalid @enderror" 
                                       id="nama_barang" 
                                       name="nama_barang" 
                                       value="{{ old('nama_barang') }}"
                                       maxlength="100"
                                       placeholder="Nama Barang"
                                       required>
                                <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
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
                                       value="{{ old('warna') }}"
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
                                <label for="satuan" class="minimal-label">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select minimal-select @error('satuan') is-invalid @enderror" 
                                        id="satuan" 
                                        name="satuan" 
                                        required>
                                    <option value="">Pilih Satuan</option>
                                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>Gram</option>
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
                            <i data-lucide="save"></i> Simpan Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
$(document).ready(function() {
    // Setup AJAX form handler
    setupAjaxForm('#barangForm', {
        loadingTitle: 'Menyimpan Barang...',
        loadingText: 'Sedang memproses data barang',
        successTitle: 'Barang Berhasil Disimpan!',
        successText: 'Data barang telah berhasil disimpan',
        redirectUrl: '{{ route("barang.index") }}',
    });
});
</script>
@endpush
</x-app-layout>