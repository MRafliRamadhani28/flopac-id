<x-app-layout title="Detail Barang Masuk - Flopac.id" icon='<i data-lucide="notebook-pen" class="me-3"></i> Detail Barang Masuk'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-end">
                <a href="{{ route('barang_masuk.index') }}" class="btn btn-outline-secondary me-2">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
                <button type="button" class="btn btn-outline-danger ajax-delete-btn"
                    data-url="{{ route('barang_masuk.destroy', $barangMasuk->id) }}"
                    data-name="{{ $barangMasuk->no_barang_masuk }}"
                    data-redirect="{{ route('barang_masuk.index') }}">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="trash-2" style="margin-right: 8px; width: 20px; height: 20px;"></i> Hapus
                    </p>
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Info Card -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
                    <div class="card-body" style="padding: 2rem;">
                        <h6 class="mb-3" style="color: var(--color-foreground); font-weight: 600;">Informasi Barang Masuk</h6>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">No. Barang Masuk</small>
                            <span class="badge text-dark" style="background-color: rgba(74, 200, 234, 0.15); padding: 8px 12px; border-radius: 8px; font-size: 14px;">
                                {{ $barangMasuk->no_barang_masuk }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Tanggal Masuk</small>
                            <strong style="color: var(--color-foreground);">{{ $barangMasuk->tanggal_masuk->format('d F Y') }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Dibuat Oleh</small>
                            <strong style="color: var(--color-foreground);">{{ $barangMasuk->creator->name }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Total Item</small>
                            <span class="badge bg-info">{{ $barangMasuk->details->count() }} item</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Items -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
                    <div class="card-body" style="padding: 2rem;">
                        <h6 class="mb-3" style="color: var(--color-foreground); font-weight: 600;">Detail Barang</h6>
                        
                        @if($barangMasuk->details->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle table-transparent">
                                    <thead>
                                        <tr>
                                            <th style="font-weight: 600; color: var(--color-foreground); font-size: 14px;">#</th>
                                            <th style="font-weight: 600; color: var(--color-foreground); font-size: 14px;">Nama Barang</th>
                                            <th style="font-weight: 600; color: var(--color-foreground); font-size: 14px;">Warna</th>
                                            <th style="font-weight: 600; color: var(--color-foreground); font-size: 14px; text-align: center;">Jumlah</th>
                                            <th style="font-weight: 600; color: var(--color-foreground); font-size: 14px;">Satuan</th>
                                            <th style="font-weight: 600; color: var(--color-foreground); font-size: 14px;">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($barangMasuk->details as $detail)
                                            <tr>
                                                <td style="font-size: 13px; color: var(--color-foreground);">{{ $loop->iteration }}</td>
                                                <td style="font-size: 13px; color: var(--color-foreground);"><strong>{{ $detail->barang->nama_barang }}</strong></td>
                                                <td style="font-size: 13px; color: var(--color-foreground);">{{ $detail->barang->warna ?: '-' }}</td>
                                                <td style="font-size: 13px; text-align: center;"><span class="badge bg-primary" style="font-size: 11px;">{{ number_format($detail->qty) }}</span></td>
                                                <td style="font-size: 13px; color: var(--color-foreground);">{{ $detail->barang->satuan }}</td>
                                                <td style="font-size: 13px; color: var(--color-foreground);">{{ $detail->keterangan ?: '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i data-lucide="package" class="mb-3" style="width: 64px; height: 64px; color: #6c757d;"></i>
                                <h5 class="text-muted">Tidak Ada Detail Barang</h5>
                                <p class="text-muted">Barang masuk ini tidak memiliki detail barang</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .badge {
                font-size: 11px;
                padding: 4px 8px;
            }
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
        </style>
    @endpush

    <x-ajax-handler />
    <x-form-styles />

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Lucide icons
                lucide.createIcons();
                
                // Setup AJAX delete functionality
                setupAjaxDelete('.ajax-delete-btn', {
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus barang masuk ini?',
                    successRedirectUrl: "{{ route('barang_masuk.index') }}"
                });
            });
        </script>
    @endpush
</x-app-layout>
