<x-app-layout title="Detail Laporan - Flopac.id" icon='<i data-lucide="file-text" class="me-3"></i> Detail Laporan'>
    <div class="container-fluid">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header Row -->
        <div class="row mb-3">
            <div class="col-md-6">
                <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">Detail Laporan Pemakaian Stock</h4>
                <p class="text-muted mb-0" style="font-size: 14px;">Detail pemakaian {{ $barang->nama_barang }} pada {{ $tanggal->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                        Kembali
                    </p>
                </a>
            </div>
        </div>

        <!-- Summary Info Card -->
        <div class="card border-0 shadow-sm mb-4" style="background: var(--color-background); border-radius: 12px;">
            <div class="card-header" style="background: transparent; border-bottom: 1px solid #f1f3f4; padding: 1.5rem;">
                <h5 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="info" class="me-2" style="width: 20px; height: 20px;"></i>
                        Ringkasan Pemakaian
                    </p>
                </h5>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-1" style="color: var(--color-foreground);">{{ $tanggal->format('d/m/Y') }}</h4>
                            <small class="text-muted">Tanggal</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-1" style="color: var(--color-foreground);">{{ $barang->nama_barang }}</h4>
                            <small class="text-muted">Nama Barang</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-danger text-white rounded">
                            <h4 class="mb-1">{{ $totalStockUsed }}</h4>
                            <small>Total Stock Dihabiskan</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-info text-white rounded">
                            <h4 class="mb-1">{{ $totalOrders }}</h4>
                            <small>Total Pesanan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Usage Detail Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px;">
            <div class="card-header" style="background: transparent; border-bottom: 1px solid #f1f3f4; padding: 1.5rem;">
                <h5 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="package" class="me-2" style="width: 20px; height: 20px;"></i>
                        Detail Pesanan yang Menggunakan Stock
                    </p>
                </h5>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-transparent">
                        <thead>
                            <tr>
                                <th style="color: var(--color-foreground); font-weight: 600;">No</th>
                                <th style="color: var(--color-foreground); font-weight: 600;">No. Pesanan</th>
                                <th style="color: var(--color-foreground); font-weight: 600;">Pelanggan</th>
                                <th style="color: var(--color-foreground); font-weight: 600;">Model</th>
                                <th style="color: var(--color-foreground); font-weight: 600; text-align: center;">Jumlah Dipakai</th>
                                <th style="color: var(--color-foreground); font-weight: 600; text-align: center;">Status</th>
                                <th style="color: var(--color-foreground); font-weight: 600; text-align: center;">Waktu Pemakaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usageRecords as $index => $usage)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="font-weight: 500;">{{ $usage->pesanan->no_pesanan }}</td>
                                    <td>{{ $usage->pesanan->nama_pelanggan }}</td>
                                    <td>{{ $usage->pesanan->model }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-danger">{{ $usage->jumlah_dipakai }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-{{ $usage->pesanan->status == 'Selesai' ? 'success' : ($usage->pesanan->status == 'Diproses' ? 'info' : 'warning') }}">
                                            {{ $usage->pesanan->status }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">{{ $usage->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i data-lucide="package-x" style="width: 48px; height: 48px; color: #6b7280; margin-bottom: 1rem;"></i>
                                        <div style="color: #6b7280;">Tidak ada data pemakaian stock</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Auto hide alerts
            setTimeout(function() {
                $('#success-alert').fadeOut('slow');
            }, 5000);
        });

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
    @endpush
</x-app-layout>
