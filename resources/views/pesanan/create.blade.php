<x-app-layout title="Tambah Pesanan - Flopac.id" icon='<i data-lucide="shopping-cart" class="me-3"></i> Tambah Pesanan'>
    <x-form-styles />
    <x-ajax-handler />
    
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Tambah Pesanan</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Buat pesanan produksi baru</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('pesanan.index') }}" class="minimal-btn-secondary">
                    <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
            <div class="card-body" style="padding: 2rem;">
                <form id="createPesananForm" method="POST" action="{{ route('pesanan.store') }}">
                    @csrf
                    <div class="row">
                        <!-- No. Pesanan Preview -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Pesanan</label>
                            <div class="form-control-plaintext bg-light rounded px-3 py-2" style="border: 1px solid #dee2e6;">
                                <strong>{{ $nextPesananNumber }}</strong>
                                <small class="text-muted d-block">Nomor akan digenerate otomatis</small>
                            </div>
                        </div>

                        <!-- Nama Pelanggan -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_pelanggan" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nama_pelanggan" 
                                   name="nama_pelanggan" 
                                   value="{{ old('nama_pelanggan') }}"
                                   placeholder="Masukkan nama pelanggan"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Alamat -->
                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" 
                                      id="alamat" 
                                      name="alamat" 
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap pelanggan"
                                      required>{{ old('alamat') }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Model -->
                        <div class="col-md-6 mb-3">
                            <label for="model" class="form-label">Model <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="model" 
                                   name="model" 
                                   value="{{ old('model') }}"
                                   placeholder="Masukkan model produk"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Sumber -->
                        <div class="col-md-6 mb-3">
                            <label for="sumber" class="form-label">Sumber <span class="text-danger">*</span></label>
                            <select class="form-control" id="sumber" name="sumber" required>
                                <option value="">Pilih sumber pesanan</option>
                                <option value="Online" {{ old('sumber') == 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Offline" {{ old('sumber') == 'Offline' ? 'selected' : '' }}>Offline</option>
                                <option value="Referral" {{ old('sumber') == 'Referral' ? 'selected' : '' }}>Referral</option>
                                <option value="Repeat Customer" {{ old('sumber') == 'Repeat Customer' ? 'selected' : '' }}>Repeat Customer</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Pesanan -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_pesanan" class="form-label">Tanggal Pesanan <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control" 
                                   id="tanggal_pesanan" 
                                   name="tanggal_pesanan" 
                                   value="{{ old('tanggal_pesanan', now()->format('Y-m-d')) }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Tenggat Pesanan -->
                        <div class="col-md-6 mb-3">
                            <label for="tenggat_pesanan" class="form-label">Tenggat Pesanan <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control" 
                                   id="tenggat_pesanan" 
                                   name="tenggat_pesanan" 
                                   value="{{ old('tenggat_pesanan') }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Catatan -->
                        <div class="col-12 mb-4">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" 
                                      id="catatan" 
                                      name="catatan" 
                                      rows="4"
                                      placeholder="Catatan tambahan untuk pesanan ini (opsional)">{{ old('catatan') }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="minimal-btn-primary" id="submitBtn">
                                    <i data-lucide="save" style="margin-right: 8px; width: 20px; height: 20px;"></i>
                                    Simpan Pesanan
                                </button>
                                <a href="{{ route('pesanan.index') }}" class="minimal-btn-secondary">
                                    <i data-lucide="x" style="margin-right: 8px; width: 20px; height: 20px;"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    $(document).ready(function() {
        // Setup form validation
        setupAjaxForm('#createPesananForm', {
            successTitle: 'Pesanan Berhasil Dibuat!',
            successText: 'Pesanan baru telah berhasil disimpan ke sistem',
            redirectUrl: '{{ route("pesanan.index") }}',
            customValidation: function(form) {
                let isValid = true;
                
                // Validate tanggal pesanan tidak boleh di masa depan
                const tanggalPesanan = new Date($('#tanggal_pesanan').val());
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (tanggalPesanan > today) {
                    showFieldError('#tanggal_pesanan', 'Tanggal pesanan tidak boleh di masa depan');
                    isValid = false;
                }
                
                // Validate tenggat pesanan tidak boleh sebelum tanggal pesanan
                const tenggatPesanan = new Date($('#tenggat_pesanan').val());
                
                if (tenggatPesanan < tanggalPesanan) {
                    showFieldError('#tenggat_pesanan', 'Tenggat pesanan tidak boleh sebelum tanggal pesanan');
                    isValid = false;
                }
                
                return isValid;
            }
        });

        // Auto-set minimum date for tenggat_pesanan based on tanggal_pesanan
        $('#tanggal_pesanan').on('change', function() {
            const tanggalPesanan = $(this).val();
            $('#tenggat_pesanan').attr('min', tanggalPesanan);
            
            // Clear tenggat_pesanan if it's before tanggal_pesanan
            const tenggatPesanan = $('#tenggat_pesanan').val();
            if (tenggatPesanan && tenggatPesanan < tanggalPesanan) {
                $('#tenggat_pesanan').val('');
                $('#tenggat_pesanan').removeClass('is-invalid');
                $('#tenggat_pesanan').siblings('.invalid-feedback').text('');
            }
        });

        // Set initial min date for tenggat_pesanan
        const initialTanggal = $('#tanggal_pesanan').val();
        if (initialTanggal) {
            $('#tenggat_pesanan').attr('min', initialTanggal);
        }

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    </script>
    @endpush
</x-app-layout>
