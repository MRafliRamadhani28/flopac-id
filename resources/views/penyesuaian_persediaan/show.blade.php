<x-app-layout title="Detail Penyesuaian Persediaan - Flopac.id" icon='<i data-lucide="eye" class="me-3"></i> Detail Penyesuaian'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-end">
                <a href="{{ route('penyesuaian_persediaan.index') }}" class="btn btn-outline-secondary me-2">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
                <button type="button" class="btn btn-outline-danger ajax-delete-btn"
                    data-url="{{ route('penyesuaian_persediaan.destroy', $penyesuaianPersediaan->id) }}"
                    data-name="{{ $penyesuaianPersediaan->no_penyesuaian_persediaan }}"
                    data-redirect="{{ route('penyesuaian_persediaan.index') }}">
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
                        <h6 class="mb-3" style="color: var(--color-foreground); font-weight: 600;">Informasi Penyesuaian</h6>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">No. Penyesuaian</small>
                            <span class="badge text-dark" style="background-color: rgba(74, 200, 234, 0.15); padding: 8px 12px; border-radius: 8px; font-size: 14px;">
                                {{ $penyesuaianPersediaan->no_penyesuaian_persediaan }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Tanggal Penyesuaian</small>
                            <strong style="color: var(--color-foreground);">{{ $penyesuaianPersediaan->tanggal_penyesuaian->format('d F Y') }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Dibuat Oleh</small>
                            <strong style="color: var(--color-foreground);">{{ $penyesuaianPersediaan->creator->name }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Total Item</small>
                            <span class="badge bg-info">{{ $penyesuaianPersediaan->details->count() }} item</span>
                        </div>

                        @if($penyesuaianPersediaan->keterangan)
                        <div>
                            <small class="text-muted d-block">Keterangan</small>
                            <p style="color: var(--color-foreground); margin-bottom: 0;">{{ $penyesuaianPersediaan->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Items -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 16px;">
                    <div class="card-body" style="padding: 2rem;">
                        <h6 class="mb-3" style="color: var(--color-foreground); font-weight: 600;">Detail Barang</h6>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-transparent">
                                <thead>
                                    <tr>
                                        <th style="font-weight: 600; color: var(--color-foreground); font-size: 13px;">#</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); font-size: 13px;">Nama Barang</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); font-size: 13px;">Warna</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); font-size: 13px; text-align: center;">Stock Sebelum</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); font-size: 13px; text-align: center;">Jenis</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); font-size: 13px; text-align: center;">Penyesuaian</th>
                                        <th style="font-weight: 600; color: var(--color-foreground); font-size: 13px; text-align: center;">Stock Sesudah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penyesuaianPersediaan->details as $detail)
                                        <tr>
                                            <td style="font-size: 12px; color: var(--color-foreground);">{{ $loop->iteration }}</td>
                                            <td style="font-size: 12px; color: var(--color-foreground);">
                                                <strong>{{ $detail->barang->nama_barang }}</strong>
                                            </td>
                                            <td style="font-size: 12px; color: var(--color-foreground);">
                                                {{ $detail->barang->warna ?: '-' }}
                                            </td>
                                            <td style="font-size: 12px; text-align: center;">
                                                <span class="badge bg-secondary" style="font-size: 11px;">{{ number_format($detail->stock_sebelum) }}</span>
                                            </td>
                                            <td style="font-size: 12px; text-align: center;">
                                                @if($detail->jenis_penyesuaian === 'penambahan')
                                                    <span class="badge bg-success" style="font-size: 11px;">
                                                        <p class="d-flex align-items-center mb-0">
                                                            <i data-lucide="plus" style="width: 12px; height: 12px; margin-right: 4px;"></i>
                                                            Penambahan
                                                        </p>
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger" style="font-size: 11px;">
                                                        <p class="d-flex align-items-center mb-0">
                                                            <i data-lucide="minus" style="width: 12px; height: 12px; margin-right: 4px;"></i>
                                                            Pengurangan
                                                        </p>
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="font-size: 12px; text-align: center;">
                                                <span class="badge {{ $detail->jenis_penyesuaian === 'penambahan' ? 'bg-success' : 'bg-danger' }}" style="font-size: 11px;">
                                                    {{ $detail->jenis_penyesuaian === 'penambahan' ? '+' : '-' }}{{ number_format($detail->stock_penyesuaian) }}
                                                </span>
                                            </td>
                                            <td style="font-size: 12px; text-align: center;">
                                                <span class="badge bg-primary" style="font-size: 11px;">{{ number_format($detail->stock_sesudah) }}</span>
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
    </div>

    @push('styles')
        <style>
            .badge {
                font-size: 11px;
                padding: 4px 8px;
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
                    text: 'Apakah Anda yakin ingin menghapus penyesuaian persediaan ini?',
                    successRedirectUrl: "{{ route('penyesuaian_persediaan.index') }}"
                });
            });
        </script>
    @endpush
</x-app-layout>
