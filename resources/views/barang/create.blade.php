<x-app-layout title="Tambah Barang - Flopac.id" icon='<i data-lucide="plus" class="me-3"></i> Tambah Barang'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Tambah Barang Baru</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Tambah data barang ke dalam inventory</p>
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
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
            <div class="card-body" style="padding: 2rem;">
                <form action="{{ route('barang.store') }}" method="POST">
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
                                <label for="satuan" class="minimal-label">Satuan *</label>
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
                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('barang.index') }}" class="btn btn-light minimal-btn-secondary">
                            <p class="d-flex align-items-center mb-0">
                                <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i> Batal
                            </p>
                        </a>
                        <button type="submit" class="btn btn-primary minimal-btn-primary">
                            <p class="d-flex align-items-center mb-0">
                                <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i> Simpan Barang
                            </p>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Minimal Form Styling */
        .minimal-input {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .minimal-input:focus {
            border-color: #4AC8EA;
            box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.15);
            background: #ffffff;
        }

        .form-floating > .minimal-input {
            padding: 1.625rem 1rem 0.625rem;
        }

        .form-floating > label {
            padding: 1rem;
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        .minimal-select-container {
            position: relative;
        }

        .minimal-label {
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 0.5rem;
            display: block;
        }

        .minimal-select {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 14px;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        .minimal-select:focus {
            border-color: #4AC8EA;
            box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.15);
        }

        .minimal-btn-primary {
            background: linear-gradient(135deg, #4AC8EA 0%, #4AC8EA 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .minimal-btn-primary:hover {
            background: linear-gradient(135deg, #39b8d6 0%, #39b8d6 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 200, 234, 0.3);
        }

        .minimal-btn-secondary {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            font-size: 14px;
            color: #6c757d;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        .minimal-btn-secondary:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #4AC8EA;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }
    </style>
</x-app-layout>