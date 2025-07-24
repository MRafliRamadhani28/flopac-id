<x-app-layout title="Detail Penyesuaian Persediaan - Flopac.id" icon='<i data-lucide="eye" class="me-3"></i> Detail Penyesuaian'>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Detail Penyesuaian Persediaan</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">{{ $penyesuaianPersediaan->no_penyesuaian_persediaan }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('penyesuaian_persediaan.index') }}" class="btn btn-outline-secondary me-2">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="margin-right: 8px; width: 20px; height: 20px;"></i> Kembali
                    </p>
                </a>
                <a href="{{ route('penyesuaian_persediaan.edit', $penyesuaianPersediaan->id) }}" class="btn btn-warning">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="edit-3" style="margin-right: 8px; width: 20px; height: 20px;"></i> Edit
                    </p>
                </a>
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
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr style="background-color: rgba(74, 200, 234, 0.1);">
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600;">Nama Barang</th>
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600;">Warna</th>
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Stock Sebelum</th>
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Jenis</th>
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Penyesuaian</th>
                                        <th style="border: none; padding: 16px; color: var(--color-primary); font-weight: 600; text-align: center;">Stock Sesudah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penyesuaianPersediaan->details as $detail)
                                        <tr>
                                            <td style="border: none; padding: 16px; color: var(--color-foreground);">
                                                {{ $detail->barang->nama_barang }}
                                            </td>
                                            <td style="border: none; padding: 16px; color: var(--color-foreground);">
                                                {{ $detail->barang->warna }}
                                            </td>
                                            <td style="border: none; padding: 16px; color: var(--color-foreground); text-align: center;">
                                                <span class="badge bg-secondary">{{ number_format($detail->stock_sebelum) }}</span>
                                            </td>
                                            <td style="border: none; padding: 16px; text-align: center;">
                                                @if($detail->jenis_penyesuaian === 'penambahan')
                                                    <span class="badge bg-success">
                                                        <i data-lucide="plus" style="width: 12px; height: 12px; margin-right: 4px;"></i>
                                                        Penambahan
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i data-lucide="minus" style="width: 12px; height: 12px; margin-right: 4px;"></i>
                                                        Pengurangan
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="border: none; padding: 16px; color: var(--color-foreground); text-align: center;">
                                                <span class="badge {{ $detail->jenis_penyesuaian === 'penambahan' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $detail->jenis_penyesuaian === 'penambahan' ? '+' : '-' }}{{ number_format($detail->stock_penyesuaian) }}
                                                </span>
                                            </td>
                                            <td style="border: none; padding: 16px; color: var(--color-foreground); text-align: center;">
                                                <span class="badge bg-primary">{{ number_format($detail->stock_sesudah) }}</span>
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
                font-size: 12px;
                padding: 6px 10px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Lucide icons
                lucide.createIcons();
            });
        </script>
    @endpush
</x-app-layout>
