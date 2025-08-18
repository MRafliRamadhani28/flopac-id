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

        /* Notification Dropdown Styling */
        .notification-dropdown {
            min-width: 350px !important;
            max-width: 500px !important;
            width: max-content !important;
        }

        .notification-item {
            max-width: none !important;
            white-space: normal !important;
            word-wrap: break-word !important;
        }

        .notification-item:hover {
            background-color: #f8f9fa !important;
        }

        .notification-item.unread {
            background-color: #f8f9fa;
            border-left: 3px solid #0d6efd;
        }

        .notification-item.read {
            background-color: transparent;
        }

        /* Responsive notification dropdown */
        @media (max-width: 768px) {
            .notification-dropdown {
                width: 90vw !important;
                right: 5vw !important;
                left: auto !important;
            }
        }

        /* User Dropdown Styling */
        .user-dropdown {
            min-width: 200px;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            background: white;
        }

        .user-dropdown .dropdown-item {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: var(--color-foreground);
            border: none;
            transition: background-color 0.2s ease;
        }

        .user-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--color-foreground);
        }

        .user-dropdown .dropdown-divider {
            margin: 0.5rem 0;
            border-color: #e9ecef;
        }

        .user-info {
            background-color: #f8f9fa;
            border-radius: 8px 8px 0 0;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--color-foreground), #4AC8EA);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Responsive user dropdown */
        @media (max-width: 768px) {
            .user-dropdown {
                width: 90vw !important;
                right: 5vw !important;
                left: auto !important;
            }
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
                    <!-- Notification Dropdown -->
                    <div class="dropdown position-relative">
                        <button class="btn btn-link p-0 border-0 position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-lucide="bell" style="width: 28px; height: 28px; cursor: pointer; color: var(--color-foreground);"></i>
                            <!-- Badge for unread count -->
                            <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.75rem;">
                                0
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" style="max-height: 400px; overflow-y: auto;">
                            <div class="dropdown-header d-flex justify-content-between align-items-center" style="padding: 0.75rem 1rem; border-bottom: 1px solid #dee2e6;">
                                <h6 class="mb-0" style="font-weight: 600;">Notifikasi</h6>
                                <button class="btn btn-sm btn-outline-primary" id="markAllAsRead" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                    Tandai semua dibaca
                                </button>
                            </div>
                            <div id="notificationList">
                                <!-- Notifications will be loaded here -->
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="dropdown position-relative">
                        <button class="btn btn-link p-0 border-0" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-lucide="circle-user-round" style="width: 32px; height: 32px; cursor: pointer; color: var(--color-foreground);"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end user-dropdown" aria-labelledby="userDropdown">
                            <div class="user-info">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="color: var(--color-foreground); font-size: 0.875rem;">
                                            {{ Auth::user()->name ?? 'User' }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            {{ Auth::user()->email ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); confirmLogout();">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="log-out" class="me-2" style="width: 16px; height: 16px;"></i>
                                    Logout
                                </div>
                            </a>
                        </div>
                    </div>
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

        // Notification System
        class NotificationManager {
            constructor() {
                this.notificationBadge = document.getElementById('notificationBadge');
                this.notificationList = document.getElementById('notificationList');
                this.markAllBtn = document.getElementById('markAllAsRead');
                this.init();
            }

            init() {
                // Load notifications when dropdown is opened
                document.getElementById('notificationDropdown').addEventListener('click', () => {
                    this.loadNotifications();
                });

                // Mark all as read
                this.markAllBtn.addEventListener('click', () => {
                    this.markAllAsRead();
                });

                // Load unread count on page load
                this.loadUnreadCount();

                // Auto refresh every 30 seconds
                setInterval(() => {
                    this.loadUnreadCount();
                }, 30000);
            }

            async loadNotifications() {
                try {
                    this.notificationList.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm" role="status"></div></div>';
                    
                    const response = await fetch('/api/notifications');
                    const data = await response.json();
                    
                    this.renderNotifications(data.notifications);
                    this.updateBadge(data.unread_count);
                } catch (error) {
                    console.error('Error loading notifications:', error);
                    this.notificationList.innerHTML = '<div class="text-center py-3 text-muted">Gagal memuat notifikasi</div>';
                }
            }

            async loadUnreadCount() {
                try {
                    const response = await fetch('/api/notifications/unread-count');
                    const data = await response.json();
                    this.updateBadge(data.count);
                } catch (error) {
                    console.error('Error loading unread count:', error);
                }
            }

            renderNotifications(notifications) {
                if (notifications.length === 0) {
                    this.notificationList.innerHTML = '<div class="text-center py-3 text-muted">Tidak ada notifikasi</div>';
                    return;
                }

                const html = notifications.map(notification => `
                    <div class="notification-item ${notification.is_read ? 'read' : 'unread'}" 
                         data-id="${notification.id}" 
                         style="cursor: pointer; padding: 0.75rem 1rem; border-left: 3px solid ${this.getColorHex(notification.color)}; transition: background-color 0.2s ease;">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <i data-lucide="${notification.icon}" style="width: 20px; height: 20px; color: ${this.getColorHex(notification.color)};"></i>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0; overflow: hidden;">
                                <h6 class="mb-1" style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.25rem; line-height: 1.2;">${notification.title}</h6>
                                <p class="mb-1" style="font-size: 0.8rem; color: #6c757d; margin-bottom: 0.5rem; line-height: 1.4; word-wrap: break-word;">${notification.message}</p>
                                <small class="text-muted" style="font-size: 0.75rem;">${notification.time_ago}</small>
                            </div>
                            ${!notification.is_read ? '<div class="flex-shrink-0 ms-3"><span class="badge bg-primary rounded-pill" style="width: 8px; height: 8px; padding: 0; margin-top: 2px;"></span></div>' : ''}
                        </div>
                    </div>
                `).join('');
                
                this.notificationList.innerHTML = html;
                
                // Re-initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                // Add click handlers
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', (e) => {
                        const id = e.currentTarget.dataset.id;
                        this.markAsRead(id, e.currentTarget);
                    });
                });
            }

            async markAsRead(id, element) {
                try {
                    const response = await fetch(`/api/notifications/${id}/mark-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        // Update UI
                        element.classList.remove('unread');
                        element.classList.add('read');
                        element.style.backgroundColor = '';
                        
                        // Remove unread indicator
                        const unreadIndicator = element.querySelector('.badge');
                        if (unreadIndicator) {
                            unreadIndicator.remove();
                        }

                        this.updateBadge(data.unread_count);
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            }

            async markAllAsRead() {
                try {
                    const response = await fetch('/api/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        // Reload notifications
                        this.loadNotifications();
                        this.updateBadge(0);
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } catch (error) {
                    console.error('Error marking all as read:', error);
                }
            }

            updateBadge(count) {
                if (count > 0) {
                    this.notificationBadge.textContent = count > 99 ? '99+' : count;
                    this.notificationBadge.style.display = 'block';
                } else {
                    this.notificationBadge.style.display = 'none';
                }
            }

            getColorHex(color) {
                const colorMap = {
                    'warning': '#ffc107',
                    'danger': '#dc3545',
                    'info': '#0dcaf0',
                    'success': '#198754'
                };
                return colorMap[color] || '#6c757d';
            }
        }

        // Initialize notification manager when page loads
        document.addEventListener('DOMContentLoaded', function() {
            new NotificationManager();
        });
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