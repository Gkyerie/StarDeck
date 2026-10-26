<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StarDeck — Pusat Rekreasi Orbital')</title>
    <meta name="description" content="StarDeck — Sistem Pengelolaan Peminjaman Board Game & Console Stasiun Luar Angkasa">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-cyan-500/30 selection:text-cyan-200">
    <!-- Starfield & Radial glow -->
    <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_75%_15%,rgba(34,211,238,.055),transparent_27%),radial-gradient(circle_at_30%_80%,rgba(139,92,246,.045),transparent_25%)]"></div>
    <div class="stars"></div>
    <div class="orbit orbit-a"></div>
    <div class="orbit orbit-b"></div>

    <!-- Mobile Drawer Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-slate-950/70 backdrop-blur-sm lg:hidden hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar Navigation -->
    <aside id="main-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 border-r border-slate-800 bg-slate-950/95 p-5 backdrop-blur-xl transition-transform duration-200 -translate-x-full lg:translate-x-0 flex flex-col justify-between">
        <div>
            <!-- Header / Logo -->
            <div class="mb-8 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 no-underline">
                    <div class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-cyan-300/30 bg-cyan-300/10 text-cyan-300 shadow-[0_0_25px_rgba(34,211,238,.15)]">
                        <div class="absolute h-6 w-6 rotate-45 rounded-sm border border-current opacity-50"></div>
                        <span class="text-lg font-bold font-heading">S</span>
                    </div>
                    <div>
                        <div class="text-lg font-bold tracking-tight text-white font-heading">
                            Star<span class="text-cyan-300">Deck</span>
                        </div>
                        <div class="text-[10px] uppercase tracking-[.22em] text-slate-500">
                            Pusat Rekreasi Orbital
                        </div>
                    </div>
                </a>
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 hover:bg-white/5 hover:text-white lg:hidden" onclick="toggleSidebar()">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>

            <!-- Role Badge / Title -->
            <div class="mb-3 px-3 text-[10px] font-semibold uppercase tracking-[.2em] text-slate-500">
                {{ auth()->user()->isAdmin() ? 'Pusat Komando' : 'Portal Astronot' }}
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5">
                @if(auth()->user()->isAdmin())
                    <!-- Admin (Komandan) Navigation -->
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="2" /><rect x="14" y="3" width="7" height="7" rx="2" /><rect x="3" y="14" width="7" height="7" rx="2" /><rect x="14" y="14" width="7" height="7" rx="2" /></svg>
                        <span>Dasbor</span>
                    </a>

                    <a href="{{ route('units.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('units.*') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m4 7 8-4 8 4-8 4-8-4Z" /><path d="m4 7 8 4 8-4v10l-8 4-8-4V7Z" /><path d="M12 11v10" /></svg>
                        <span>Unit & Inventaris</span>
                    </a>

                    <a href="{{ route('categories.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('categories.*') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13 13 20l-9-9V4h7l9 9Z M8.5 8.5h.01" /></svg>
                        <span>Kategori</span>
                    </a>

                    <a href="{{ route('members.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('members.*') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                        <span>Data Astronot</span>
                    </a>

                    <a href="{{ route('loans.active') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('loans.active') || request()->routeIs('loans.create') || request()->routeIs('loans.return') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg>
                        <span>Pinjaman Aktif</span>
                    </a>

                    <a href="{{ route('loans.history-all') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('loans.history-all') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8" /><path d="M3 3v5h5" /><path d="M12 7v5l3 2" /></svg>
                        <span>Riwayat Semua Pinjaman</span>
                    </a>

                    <a href="{{ route('catalog.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('catalog.*') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" /></svg>
                        <span>Katalog Publik</span>
                    </a>
                @else
                    <!-- Member (Astronot) Navigation -->
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="2" /><rect x="14" y="3" width="7" height="7" rx="2" /><rect x="3" y="14" width="7" height="7" rx="2" /><rect x="14" y="14" width="7" height="7" rx="2" /></svg>
                        <span>Beranda</span>
                    </a>

                    <a href="{{ route('catalog.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('catalog.*') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" /></svg>
                        <span>Katalog Unit</span>
                    </a>

                    <a href="{{ route('loans.my') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('loans.my') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg>
                        <span>Pinjaman Saya</span>
                    </a>

                    <a href="{{ route('profile.show') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all {{ request()->routeIs('profile.*') ? 'bg-cyan-300/10 text-cyan-300 shadow-[inset_3px_0_0_#67e8f9]' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>
                        <span>Profil Astronot</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- User Profile Card & Logout -->
        <div class="pt-4 border-t border-slate-800/80">
            <div class="mb-3 flex items-center gap-3 rounded-xl border border-slate-800 bg-slate-900/60 p-3">
                @if(auth()->user()->memberProfile && auth()->user()->memberProfile->photo)
                    <img src="{{ asset('storage/' . auth()->user()->memberProfile->photo) }}" alt="{{ auth()->user()->name }}" class="h-9 w-9 rounded-lg object-cover">
                @else
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-400/10 font-bold text-violet-300 font-heading">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <div class="truncate text-xs font-semibold text-white">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="truncate text-[10px] text-slate-500">
                        {{ auth()->user()->isAdmin() ? 'Komandan Stasiun' : (auth()->user()->memberProfile->rank_or_position ?? 'Astronot Kru') }}
                    </div>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-400 hover:bg-rose-400/10 hover:text-rose-300 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><path d="m16 17 5-5-5-5M21 12H9" /></svg>
                    <span>Keluar dari Sesi</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="relative lg:pl-64 flex flex-col min-h-screen">
        <!-- Top Header -->
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-800 bg-slate-950/80 px-4 backdrop-blur-xl sm:px-7">
            <div class="flex items-center gap-3">
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 hover:bg-white/5 hover:text-white lg:hidden" onclick="toggleSidebar()">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <div class="text-xs text-slate-500 flex items-center gap-2">
                    <span>Stasiun Andromeda</span>
                    <span class="text-slate-700">/</span>
                    <span class="text-slate-300 font-medium">@yield('page_label', 'Rekreasi Orbital')</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full border border-emerald-400/20 bg-emerald-400/10 text-emerald-300 text-xs font-medium">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Normal
                </div>
            </div>
        </header>

        <!-- Flash Messages Container -->
        <div class="mx-auto w-full max-w-[1500px] px-4 pt-4 sm:px-7 sm:pt-6 lg:px-9">
            @if(session('success'))
                <div class="mb-4 flex items-center gap-3 rounded-2xl border border-emerald-400/25 bg-emerald-400/10 p-4 text-sm text-emerald-200 backdrop-blur-md">
                    <svg class="h-5 w-5 shrink-0 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 flex items-center gap-3 rounded-2xl border border-rose-400/25 bg-rose-400/10 p-4 text-sm text-rose-200 backdrop-blur-md">
                    <svg class="h-5 w-5 shrink-0 text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.7 2.4 18a2 2 0 0 0 1.7 3h15.8a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="mx-auto w-full max-w-[1500px] flex-1 p-4 sm:p-7 lg:p-9">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
