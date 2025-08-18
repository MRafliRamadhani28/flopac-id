<x-app-layout title="Detail Pesanan - {{ $pesanan->no_pesanan }}" icon='<i data-lucide="eye" class="me-3"></i> Detail Pesanan'>
    <div class="container-fluid">
        <!-- Back Button -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary">
                    <p class="d-flex align-items-center mb-0">
                        <i data-lucide="arrow-left" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                        Kembali ke Daftar Pesanan
                    </p>
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px;">
            <div class="card-header" style="background: var(--color-background); border-bottom: 1px solid #f1f3f4; border-radius: 12px 12px 0 0; padding: 1.5rem 2rem;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0" style="color: var(--color-foreground); font-weight: 600;">
                        <p class="d-flex align-items-center mb-0">
                            <i data-lucide="shopping-cart" class="me-2" style="width: 24px; height: 24px;"></i>
                            Detail Pesanan - {{ $pesanan->no_pesanan }}
                        </p>
                    </h4>
                    <span class="badge bg-{{ $pesanan->status_color }} fs-6">
                        {{ $pesanan->status }}
                    </span>
                </div>
            </div>
            <div class="card-body" style="padding: 2rem;">
                <div class="row">
                    <!-- Informasi Pesanan -->
                    <div class="col-md-8">
                        <h5 style="color: var(--color-foreground); font-weight: 600; margin-bottom: 1.5rem;">
                            <p class="d-flex align-items-center mb-0">
                                <i data-lucide="info" class="me-2" style="width: 20px; height: 20px;"></i>
                                Informasi Pesanan
                            </p>
                        </h5>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">No. Pesanan</label>
                                    <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                        <strong>{{ $pesanan->no_pesanan }}</strong>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Nama Pelanggan</label>
                                    <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                        {{ $pesanan->nama_pelanggan }}
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Model</label>
                                    <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                        {{ $pesanan->model }}
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Sumber</label>
                                    <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                        {{ $pesanan->sumber }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Tanggal Pesanan</label>
                                    <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                        {{ $pesanan->tanggal_pesanan->format('d/m/Y') }}
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Tenggat Pesanan</label>
                                    <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                        {{ $pesanan->tenggat_pesanan->format('d/m/Y') }}
                                        @if($pesanan->is_overdue)
                                            <span class="badge bg-danger ms-2">Terlambat</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Status</label>
                                    <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                        <span class="badge bg-{{ $pesanan->status_color }} fs-6">{{ $pesanan->status }}</span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Dibuat</label>
                                    <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                        {{ $pesanan->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Alamat</label>
                            <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                {{ $pesanan->alamat }}
                            </div>
                        </div>
                        
                        @if($pesanan->catatan)
                        <div class="mb-4">
                            <label class="form-label" style="color: var(--color-foreground); font-weight: 500;">Catatan</label>
                            <div class="form-control-plaintext" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                                {{ $pesanan->catatan }}
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Informasi Status & Pengguna -->
                    <div class="col-md-4">
                        <h5 style="color: var(--color-foreground); font-weight: 600; margin-bottom: 1.5rem;">
                            <p class="d-flex align-items-center mb-0">
                                <i data-lucide="users" class="me-2" style="width: 20px; height: 20px;"></i>
                                Informasi Status
                            </p>
                        </h5>
                        
                        <div class="card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label small" style="color: #6b7280; font-weight: 500;">Dibuat Oleh</label>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i data-lucide="user" style="width: 16px; height: 16px; color: white;"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 500; color: var(--color-foreground);">{{ $pesanan->creator->name }}</div>
                                            <small class="text-muted">{{ $pesanan->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($pesanan->processor)
                                <div class="mb-3">
                                    <label class="form-label small" style="color: #6b7280; font-weight: 500;">Diproses Oleh</label>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i data-lucide="settings" style="width: 16px; height: 16px; color: white;"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 500; color: var(--color-foreground);">{{ $pesanan->processor->name }}</div>
                                            <small class="text-muted">Processor</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small" style="color: #6b7280;">Status Saat Ini</span>
                                        <span class="badge bg-{{ $pesanan->status_color }}">{{ $pesanan->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-4">
                            <h6 style="color: var(--color-foreground); font-weight: 600; margin-bottom: 1rem;">Aksi</h6>
                            
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-danger delete-pesanan-btn"
                                    data-url="{{ route('pesanan.destroy', $pesanan->id) }}">
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="trash-2" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                                        Hapus Pesanan
                                    </p>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Riwayat Penggunaan Stok -->
                @if($pesanan->persediaanUsage->count() > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 style="color: var(--color-foreground); font-weight: 600; margin-bottom: 1.5rem;">
                            <p class="d-flex align-items-center mb-0">
                                <i data-lucide="package" class="me-2" style="width: 20px; height: 20px;"></i>
                                Riwayat Penggunaan Stok
                            </p>
                        </h5>
                        
                        <div class="card" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead style="background: #f8fafc;">
                                            <tr>
                                                <th style="font-weight: 600; color: var(--color-foreground);">No</th>
                                                <th style="font-weight: 600; color: var(--color-foreground);">Nama Barang</th>
                                                <th style="font-weight: 600; color: var(--color-foreground);">Warna</th>
                                                <th style="font-weight: 600; color: var(--color-foreground);">Satuan</th>
                                                <th style="font-weight: 600; color: var(--color-foreground);">Jumlah Dipakai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pesanan->persediaanUsage as $index => $usage)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td style="font-weight: 500;">{{ $usage->persediaan->barang->nama_barang }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $usage->persediaan->barang->warna }}</span>
                                                </td>
                                                <td>{{ $usage->persediaan->barang->satuan }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $usage->jumlah_dipakai }}</span>
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
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Delete handler
            $(document).on('click', '.delete-pesanan-btn', function(e) {
                e.preventDefault();
                
                const url = $(this).data('url');
                
                Swal.fire({
                    title: 'Konfirmasi Hapus Pesanan',
                    text: 'Apakah Anda yakin ingin menghapus pesanan ini? Stok yang telah dipakai akan dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deletePesanan(url);
                    }
                });
            });

            function showStockManagementModal(pesananId, updateUrl) {
                $('#stock_pesanan_id').val(pesananId);
                $('#stock_update_url').val(updateUrl);

                $.ajax({
                    url: `/pesanan/${pesananId}/edit`,
                    type: 'GET',
                    success: function(data) {
                        $('#pesananNumberStock').text(data.no_pesanan);
                    },
                    error: function() {
                        $('#pesananNumberStock').text('-');
                    }
                });
                
                loadStockData(pesananId);
                $('#stockManagementModal').modal('show');
            }

            function loadStockData(pesananId) {
                // Show loading
                $('#stockTableBody').html(`
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="mt-2">Memuat data stok...</div>
                        </td>
                    </tr>
                `);
                
                $.ajax({
                    url: '{{ route("api.persediaan.stock-data") }}',
                    type: 'GET',
                    success: function(data) {
                        let tableBody = '';
                        
                        if (data.length === 0) {
                            tableBody = `
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i data-lucide="package-x" style="width: 48px; height: 48px; color: #6b7280; margin-bottom: 1rem;"></i>
                                        <div style="color: #6b7280;">Tidak ada data persediaan</div>
                                    </td>
                                </tr>
                            `;
                        } else {
                            data.forEach(function(item) {
                                const availableStock = item.stok - item.dipakai;
                                tableBody += `
                                    <tr data-persediaan-id="${item.id}">
                                        <td style="font-weight: 500;">${item.barang.nama_barang}</td>
                                        <td><span class="badge bg-secondary">${item.barang.warna}</span></td>
                                        <td>${item.barang.satuan}</td>
                                        <td>
                                            <span class="badge bg-info">${availableStock}</span>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control jumlah-dipakai" 
                                                   name="jumlah_dipakai[${item.id}]" 
                                                   min="0" max="${availableStock}" value="0" 
                                                   data-available="${availableStock}"
                                                   style="width: 100px;">
                                        </td>
                                        <td>
                                            <span class="badge bg-success sisa-stok">${availableStock}</span>
                                        </td>
                                    </tr>
                                `;
                            });
                        }
                        
                        $('#stockTableBody').html(tableBody);
                        
                        // Initialize event handlers for input changes
                        $('.jumlah-dipakai').on('input', function() {
                            updateSisaStok($(this));
                        });
                        
                        // Re-initialize Lucide icons
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    },
                    error: function() {
                        $('#stockTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i data-lucide="alert-triangle" style="width: 48px; height: 48px; color: #ef4444; margin-bottom: 1rem;"></i>
                                    <div style="color: #ef4444;">Gagal memuat data stok</div>
                                </td>
                            </tr>
                        `);
                    }
                });
            }

            function updateSisaStok($input) {
                const available = parseInt($input.data('available'));
                const used = parseInt($input.val()) || 0;
                const remaining = available - used;
                
                const $sisaStokBadge = $input.closest('tr').find('.sisa-stok');
                $sisaStokBadge.text(remaining);
                
                // Update badge color based on remaining stock
                $sisaStokBadge.removeClass('bg-success bg-warning bg-danger');
                if (remaining > 10) {
                    $sisaStokBadge.addClass('bg-success');
                } else if (remaining > 0) {
                    $sisaStokBadge.addClass('bg-warning');
                } else {
                    $sisaStokBadge.addClass('bg-danger');
                }
                
                // Validate input
                if (used > available) {
                    $input.addClass('is-invalid');
                } else {
                    $input.removeClass('is-invalid');
                }
            }

            // Process stock button handler
            $(document).on('click', '#processStockBtn', function() {
                processStock();
            });

            function processStock() {
                const formData = $('#stockManagementForm').serialize();
                const updateUrl = $('#stock_update_url').val();
                
                // Validate inputs
                let isValid = true;
                $('.jumlah-dipakai').each(function() {
                    const available = parseInt($(this).data('available'));
                    const used = parseInt($(this).val()) || 0;
                    
                    if (used > available) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    }
                });
                
                if (!isValid) {
                    Swal.fire({
                        title: 'Validasi Error!',
                        text: 'Jumlah yang dipakai tidak boleh melebihi stok tersedia',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }
                
                // Show loading
                Swal.fire({
                    title: 'Memproses Stok...',
                    text: 'Sedang mengupdate stok dan status pesanan',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: '{{ route("pesanan.process-stock") }}',
                    type: 'POST',
                    data: formData + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
                    success: function(response) {
                        updatePesananStatus(updateUrl, 'Diproses');
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat memproses stok';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Error!',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }

            function updatePesananStatus(url, status) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        '_method': 'PATCH',
                        'status': status
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Status Diupdate!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#4AC8EA'
                        }).then(() => {
                            $('#stockManagementModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat mengupdate status';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Error!',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }

            function deletePesanan(url) {
                Swal.fire({
                    title: 'Menghapus Pesanan...',
                    text: 'Sedang memproses penghapusan pesanan',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        '_method': 'DELETE'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Pesanan Dihapus!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#4AC8EA'
                        }).then(() => {
                            window.location.href = '{{ route("pesanan.index") }}';
                        });
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat menghapus pesanan';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Error!',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @endpush
</x-app-layout>
