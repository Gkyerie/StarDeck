<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Peminjaman — {{ $member->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: #0f172a !important;
                padding: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body class="bg-slate-950 p-6 sm:p-10 text-slate-100 font-sans">
    <!-- Action header for browser preview (hidden on print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between border-b border-slate-800 pb-4">
        <div class="text-xs text-slate-400">
            Pratinjau Dokumen Laporan Cetak (Format A4)
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-cyan-300 px-4 py-2 text-xs font-semibold text-slate-950 hover:bg-cyan-200 transition">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Cetak Dokumen Sekarang</span>
            </button>
            <button onclick="window.close()" class="rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-semibold text-slate-300 hover:text-white transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Printable Paper Container -->
    <div class="print-report max-w-4xl mx-auto rounded-3xl border border-slate-700/80 bg-slate-900/90 p-8 sm:p-12 shadow-2xl">
        <!-- Document Header / Station Badge -->
        <div class="flex items-start justify-between border-b border-slate-700/80 pb-6 mb-6">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-cyan-300/30 bg-cyan-300/10 text-cyan-300 font-bold font-heading text-lg">
                    S
                </div>
                <div>
                    <div class="text-xl font-bold tracking-tight text-white font-heading">
                        Star<span class="text-cyan-300">Deck</span>
                    </div>
                    <div class="text-[10px] uppercase tracking-[.25em] text-slate-400 font-semibold">
                        Pusat Rekreasi Orbital
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-xs font-bold text-white font-mono uppercase tracking-wider">
                    LAPORAN LOG KRU: #{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}
                </div>
                <div class="mt-1 text-[11px] text-slate-400">
                    Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB
                </div>
            </div>
        </div>

        <!-- Title & Subtitle -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white font-heading">Riwayat Peminjaman Kru Stasiun</h1>
            <p class="text-xs text-slate-400 mt-1">Stasiun Luar Angkasa Andromeda • Modul Rekreasi Orbital StarDeck</p>
        </div>

        <!-- Member Profile Capsule -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 p-4 rounded-2xl border border-slate-800 bg-slate-950/60 text-xs">
            <div>
                <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Nama Kru</span>
                <strong class="text-white text-sm">{{ $member->name }}</strong>
            </div>
            <div>
                <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Pangkat / Posisi</span>
                <span class="text-slate-300">{{ $member->memberProfile->rank_or_position ?? 'Astronot' }}</span>
            </div>
            <div>
                <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Modul Penugasan</span>
                <span class="text-slate-300">{{ $member->memberProfile->station_module ?? '-' }}</span>
            </div>
            <div>
                <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Total Pinjaman</span>
                <span class="text-cyan-300 font-semibold">{{ $loans->count() }} Transaksi</span>
            </div>
        </div>

        <!-- Loans Table -->
        <div class="overflow-x-auto mb-8">
            <table class="w-full text-left text-xs border border-slate-800">
                <thead class="bg-slate-950/80 text-[10px] uppercase tracking-wider text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Kode Unit</th>
                        <th class="p-3">Nama Unit</th>
                        <th class="p-3">Tgl Pinjam</th>
                        <th class="p-3">Jatuh Tempo</th>
                        <th class="p-3">Tgl Kembali</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($loans as $index => $loan)
                        <tr>
                            <td class="p-3 font-mono text-slate-500">{{ $index + 1 }}</td>
                            <td class="p-3 font-mono text-cyan-300">{{ $loan->unit->unit_code }}</td>
                            <td class="p-3 font-semibold text-white">{{ $loan->unit->name }}</td>
                            <td class="p-3 text-slate-400 font-mono">{{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d/m/Y') }}</td>
                            <td class="p-3 text-slate-400 font-mono">{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                            <td class="p-3 text-slate-300 font-mono">{{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d/m/Y') : '—' }}</td>
                            <td class="p-3">
                                @if($loan->status === 'returned')
                                    <span class="text-emerald-400 font-medium">Selesai</span>
                                @else
                                    <span class="text-amber-400 font-medium">Dipinjam</span>
                                @endif
                            </td>
                            <td class="p-3 font-mono font-semibold {{ $loan->fine_amount > 0 ? 'text-rose-400' : 'text-slate-300' }}">
                                Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-4 text-center text-slate-500">Belum ada riwayat transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs pt-8 border-t border-slate-800">
            <div>
                <p class="text-slate-400 mb-14">Anggota Kru Peminjam,</p>
                <p class="font-bold text-white uppercase tracking-wider">({{ $member->name }})</p>
                <p class="text-[10px] text-slate-500">{{ $member->memberProfile->rank_or_position ?? 'Astronot' }}</p>
            </div>
            <div>
                <p class="text-slate-400 mb-14">Komandan Stasiun / Petugas Log,</p>
                <p class="font-bold text-white uppercase tracking-wider">({{ auth()->user()->name }})</p>
                <p class="text-[10px] text-slate-500">Komandan Stasiun Andromeda</p>
            </div>
        </div>
    </div>
</body>
</html>
