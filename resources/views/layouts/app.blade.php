@props(['title' => 'Dashboard - Flopac.id', 'icon' => '<i data-lucide="layout-dashboard" class="me-3" style="width: 32px; height: 32px;"></i> Dashboard'])
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- jQuery (must be loaded first) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <style>
        .sidebar {
            height: 100vh;
            background-color: #E9F2FF;
            color: var(--color-foreground);
            padding-top: 2rem;
            position: fixed;
            width: 320px;
        }

        .sidebar .nav-link {
            color: var(--color-foreground);
            border-radius: 15px;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: var(--color-foreground);
            border-radius: 15px;
            color: var(--color-background);
        }

        .sidebar .active {
            background-color: var(--color-foreground);
            color: var(--color-background);
            border-radius: 15px;
        }

        .main-content {
            margin-left: 290px;
            padding: 2rem;
        }

        .logo {
            width: 280px;
            margin-bottom: 2rem;
        }

        /* Navbar icon styling */
        .navbar-icon i[data-lucide] {
            width: 28px !important;
            height: 28px !important;
        }

        /* Dashboard title icon styling */
        h5 i[data-lucide] {
            width: 32px !important;
            height: 32px !important;
        }

        /* Global Poppins Font */
        body, .form-control, .form-select, .btn, .card, .nav-link, .modal, .dropdown-menu {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column align-items-center">
            <img src="{{ asset('assets/logo-flopac.png') }}" alt="Logo" class="logo">

            <ul class="nav flex-column w-100 px-3">
                @if(Auth::check())
                    <li class="nav-item my-0.5">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <p class="d-flex"><i data-lucide="layout-dashboard" class="me-3"></i> Dashboard</p>
                        </a>
                    </li>

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Persediaan'))
                        <li class="nav-item my-0.5">
                            <a href="{{ route('barang.index') }}" class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="box" class="me-3"></i> Barang</p>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Persediaan') || Auth::user()->hasRole('Produksi'))
                        <li class="nav-item my-0.5">
                            <a href="{{ route('persediaan.index') }}" class="nav-link {{ request()->routeIs('persediaan.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="layers" class="me-3"></i> Persediaan</p>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Persediaan'))
                        <li class="nav-item my-0.5">
                            <a href="{{ route('penyesuaian_persediaan.index') }}" class="nav-link {{ request()->routeIs('penyesuaian_persediaan.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="file-pen" class="me-3"></i>Penyesuaian Persediaan</p>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Persediaan'))
                        <li class="nav-item my-0.5">
                            <a href="{{ route('barang_masuk.index') }}" class="nav-link {{ request()->routeIs('barang_masuk.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="notebook-pen" class="me-3"></i>Barang Masuk</p>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Produksi'))
                        <li class="nav-item my-0.5">
                            <a href="{{ route('pesanan.index') }}" class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="shopping-cart" class="me-3"></i>Pesanan</p>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('Owner'))
                        <li class="nav-item my-0.5">
                            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="file-chart-column" class="me-3"></i>Laporan</p>
                            </a>
                        </li>

                        <li class="nav-item my-0.5">
                            <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="users" class="me-3"></i>User</p>
                            </a>
                        </li>
                    @endif
                @endif

                <li class="nav-item my-0.5 mt-4">
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button type="button" class="btn w-100 nav-link text-start" onclick="confirmLogout()">
                        <p class="d-flex"><i data-lucide="log-out" class="me-3"></i>Logout</p>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content w-100">
            <!-- Navbar Top -->
            <div class="d-flex justify-content-between align-items-center px-4" style="color: var(--color-foreground)">
                <h5 class="mb-0">
                    <p class="d-flex align-items-center" style="font-size: 1.25rem;">{!! $icon !!}</p>
                </h5>

                <div class="d-flex align-items-center gap-8">
                    <i data-lucide="bell" style="width: 28px; height: 28px; cursor: pointer;"></i>
                    <i data-lucide="circle-user-round" style="width: 32px; height: 32px; cursor: pointer;"></i>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-4">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>

    <!-- AJAX Form Handler Component -->
    @include('components.ajax-handler')

    <!-- Initialize Lucide Icons -->
    <script>
        // Initialize Lucide icons when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>