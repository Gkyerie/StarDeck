<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — StarDeck Pusat Rekreasi Orbital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 p-5 font-sans text-slate-100">
    <div class="orbit orbit-a"></div>
    <div class="orbit orbit-b"></div>
    <div class="stars"></div>

    <div class="relative z-10 w-full max-w-md">
        <!-- Logo -->
        <div class="mb-8 flex justify-center">
            <div class="flex items-center gap-3">
                <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-cyan-300/30 bg-cyan-300/10 text-cyan-300 shadow-[0_0_35px_rgba(34,211,238,.2)]">
                    <div class="absolute h-7 w-7 rotate-45 rounded-sm border border-current opacity-50"></div>
                    <span class="text-xl font-bold font-heading">S</span>
                </div>
                <div>
                    <div class="text-2xl font-bold tracking-tight text-white font-heading">
                        Star<span class="text-cyan-300">Deck</span>
                    </div>
                    <div class="text-[11px] uppercase tracking-[.25em] text-slate-500 font-semibold">
                        Pusat Rekreasi Orbital
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Container -->
        <div class="rounded-3xl border border-slate-800/80 bg-slate-900/60 p-7 sm:p-9 backdrop-blur-xl shadow-2xl">
            <div class="mb-7">
                <h1 class="text-2xl font-bold text-white font-heading">Selamat datang kembali</h1>
                <p class="mt-2 text-sm leading-6 text-slate-400">
                    Masuk untuk mengelola koleksi dan pinjaman unit rekreasi stasiun luar angkasa.
                </p>
            </div>

            <!-- General Error Message (Per PRD generic failure) -->
            @if($errors->has('email') || $errors->has('password') || session('error'))
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-rose-400/25 bg-rose-400/10 p-3.5 text-sm text-rose-200">
                    <svg class="h-5 w-5 shrink-0 text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.7 2.4 18a2 2 0 0 0 1.7 3h15.8a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
                    <span>{{ $errors->first('email') ?: ($errors->first('password') ?: session('error')) }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-slate-300">Alamat email stasiun</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', 'admin@stardeck.local') }}"
                        required
                        autofocus
                        placeholder="kru@stardeck.local"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300/60 focus:ring-4 focus:ring-cyan-300/10"
                    >
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-slate-300">Kata sandi</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        value="password"
                        required
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-slate-700/80 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-300/60 focus:ring-4 focus:ring-cyan-300/10"
                    >
                </div>

                <button
                    type="submit"
                    class="inline-flex w-full min-h-11 items-center justify-center gap-2 rounded-xl bg-cyan-300 px-4 text-sm font-semibold text-slate-950 shadow-[0_8px_24px_rgba(34,211,238,.18)] transition-all hover:bg-cyan-200 active:scale-[.98]"
                >
                    <span>Masuk ke StarDeck</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <span class="text-slate-500">Stasiun Andromeda Orbital</span>
                <span class="text-cyan-400 font-mono text-[11px]">Akses Kru Terverifikasi</span>
            </div>
        </div>

        <!-- Demo Accounts Hint Quick Fill -->
        <div class="mt-5 grid grid-cols-2 gap-3">
            <button
                type="button"
                onclick="document.getElementById('email').value='admin@stardeck.local'; document.getElementById('password').value='password';"
                class="rounded-xl border border-slate-800 bg-slate-900/80 px-3 py-2.5 text-xs font-semibold text-slate-300 transition hover:border-cyan-300/40 hover:text-cyan-200 active:bg-slate-800"
            >
                Akun Komandan (Admin)
            </button>
            <button
                type="button"
                onclick="document.getElementById('email').value='member1@stardeck.local'; document.getElementById('password').value='password';"
                class="rounded-xl border border-slate-800 bg-slate-900/80 px-3 py-2.5 text-xs font-semibold text-slate-300 transition hover:border-cyan-300/40 hover:text-cyan-200 active:bg-slate-800"
            >
                Akun Astronot (Member)
            </button>
        </div>
    </div>
</body>
</html>
