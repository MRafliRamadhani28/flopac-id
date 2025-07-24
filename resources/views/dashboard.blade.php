<x-app-layout title="Dashboard - Flopac.id" icon='<i data-lucide="layout-dashboard" class="me-3"></i> Dashboard'>
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4 mt-4">
            <div class="col-12">
                <div class="card bg-light border-0" style="color: var(--color-foreground);">
                    <div class="card-body">
                        <h1 class="mb-2" style="font-size: 1.5rem;">Hi, {{ ucfirst(Auth::user()->name) ?? 'User' }}</h1>
                        <p class="mb-0">Welcome to inventory Flopac.id</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Pesanan Belum di Proses -->
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px; min-height: 120px;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i data-lucide="shopping-cart" style="width: 28px; height: 28px; color: var(--color-foreground);"></i>
                            </div>
                            <div class="text-white">
                                <h6 class="card-title mb-1 small" style="color: var(--color-foreground);">Pesanan Belum di Proses</h6>
                                <h2 class="mb-0 fw-bold" style="color: var(--color-foreground);">{{ $pesananBelumProses ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Sedang di Proses -->
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px; min-height: 120px;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i data-lucide="clock" style="width: 28px; height: 28px; color: var(--color-foreground);"></i>
                            </div>
                            <div class="text-white">
                                <h6 class="card-title mb-1 small" style="color: var(--color-foreground);">Pesanan Sedang di Proses</h6>
                                <h2 class="mb-0 fw-bold" style="color: var(--color-foreground);">{{ $pesananSedangProses ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pesanan -->
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px; min-height: 120px;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i data-lucide="shopping-bag" style="width: 28px; height: 28px; color: var(--color-foreground);"></i>
                            </div>
                            <div class="text-white">
                                <h6 class="card-title mb-1 small" style="color: var(--color-foreground);">Total Pesanan</h6>
                                <h2 class="mb-0 fw-bold" style="color: var(--color-foreground);">{{ $totalPesanan ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row Statistics -->
        <div class="row mb-4">
            <!-- Total Barang -->
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px; min-height: 120px;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i data-lucide="package" style="width: 28px; height: 28px; color: var(--color-foreground);"></i>
                            </div>
                            <div class="text-white">
                                <h6 class="card-title mb-1 small" style="color: var(--color-foreground);">Total Barang</h6>
                                <h2 class="mb-0 fw-bold" style="color: var(--color-foreground);">{{ $totalBarang ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Persediaan Barang Menipis -->
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm" style="background: var(--color-background); border-radius: 12px; min-height: 120px;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i data-lucide="alert-triangle" style="width: 28px; height: 28px; color: var(--color-foreground);"></i>
                            </div>
                            <div class="text-white">
                                <h6 class="card-title mb-1 small" style="color: var(--color-foreground);">Total Persediaan Barang Menipis</h6>
                                <h2 class="mb-0 fw-bold" style="color: var(--color-foreground);">{{ $barangMenipis ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0" style="background: var(--color-background);">
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h5 class="mb-0">
                                <p class="d-flex" style="color: var(--color-foreground);"><i data-lucide="bar-chart-3" class="me-2" style="width: 20px; height: 20px;"></i> Grafik Pesanan</p>
                            </h5>
                            <span style="color: var(--color-foreground);">Januari-Desember</span>
                        </div>
                    </div>
                    <div class="card-body" style="background: var(--color-background);">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="orderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('orderChart').getContext('2d');
        const orderChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Pesanan',
                    data: [
                        {{ $chartData[0] ?? 12 }},
                        {{ $chartData[1] ?? 15 }},
                        {{ $chartData[2] ?? 18 }},
                        {{ $chartData[3] ?? 20 }},
                        {{ $chartData[4] ?? 16 }},
                        {{ $chartData[5] ?? 22 }},
                        {{ $chartData[6] ?? 25 }},
                        {{ $chartData[7] ?? 23 }},
                        {{ $chartData[8] ?? 19 }},
                        {{ $chartData[9] ?? 21 }},
                        {{ $chartData[10] ?? 24 }},
                        {{ $chartData[11] ?? 28 }}
                    ],
                    borderColor: 'var(--color-foreground)',
                    backgroundColor: 'rgba(1, 108, 137, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>