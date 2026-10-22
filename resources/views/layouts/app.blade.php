<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="StarDeck — Sistem Pengelolaan Peminjaman Board Game & Console">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'StarDeck')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sd-navy: #1b2a4a;
            --sd-navy-light: #243556;
            --sd-teal: #2dd4bf;
            --sd-teal-dark: #14b8a6;
            --sd-bg: #f0f4f8;
            --sd-card-bg: #ffffff;
            --sd-text: #1e293b;
            --sd-text-muted: #64748b;
            --sd-border: #e2e8f0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--sd-bg);
            color: var(--sd-text);
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--sd-navy);
            color: #cbd5e1;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s;
        }
        .sidebar .brand {
            padding: 1.25rem 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--sd-teal);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-left: 3px solid transparent;
            transition: all 0.15s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--sd-navy-light);
            color: #fff;
            border-left-color: var(--sd-teal);
        }
        .sidebar .nav-section {
            padding: 0.75rem 1.5rem 0.35rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            font-weight: 600;
        }
        .main-content {
            margin-left: 260px;
            padding: 0;
            min-height: 100vh;
        }
        .top-bar {
            background: var(--sd-card-bg);
            border-bottom: 1px solid var(--sd-border);
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-area {
            padding: 1.5rem;
        }
        .stat-card {
            background: var(--sd-card-bg);
            border: 1px solid var(--sd-border);
            border-radius: 0.5rem;
            padding: 1.25rem;
        }
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--sd-text);
        }
        .stat-card .stat-label {
            font-size: 0.8rem;
            color: var(--sd-text-muted);
            margin-top: 0.25rem;
        }
        .stat-card .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .card {
            border: 1px solid var(--sd-border);
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        .table > thead {
            background: #f8fafc;
        }
        .badge-borrowed {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-available {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-returned {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-overdue {
            background: #fee2e2;
            color: #991b1b;
        }
        .btn-teal {
            background: var(--sd-teal-dark);
            border-color: var(--sd-teal-dark);
            color: #fff;
        }
        .btn-teal:hover {
            background: var(--sd-teal);
            border-color: var(--sd-teal);
            color: #fff;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--sd-text-muted);
        }
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
        @media print {
            .sidebar, .top-bar, .no-print {
                display: none !important;
            }
            .main-content {
                margin-left: 0 !important;
            }
            .content-area {
                padding: 0 !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <i class="bi bi-stars"></i> StarDeck
        </div>
        <div class="py-2">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('catalog.index') }}" class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap"></i> Katalog
            </a>

            @if(auth()->user()->isMember())
                <div class="nav-section">Astronot</div>
                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i> Profil Saya
                </a>
                <a href="{{ route('loans.my') }}" class="nav-link {{ request()->routeIs('loans.my') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Pinjaman Saya
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <div class="nav-section">Manajemen</div>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Kategori
                </a>
                <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Unit
                </a>
                <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Anggota
                </a>

                <div class="nav-section">Peminjaman</div>
                <a href="{{ route('loans.create') }}" class="nav-link {{ request()->routeIs('loans.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i> Pinjam Baru
                </a>
                <a href="{{ route('loans.active') }}" class="nav-link {{ request()->routeIs('loans.active') ? 'active' : '' }}">
                    <i class="bi bi-journal-arrow-up"></i> Pinjaman Aktif
                </a>
            @endif
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="top-bar no-print">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">{{ auth()->user()->name }}</span>
                <span class="badge bg-secondary">{{ auth()->user()->isAdmin() ? 'Admin' : 'Member' }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>

        <div class="content-area">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
