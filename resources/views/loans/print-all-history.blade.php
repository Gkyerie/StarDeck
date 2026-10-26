<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Semua Riwayat Pinjaman — StarDeck</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: #0f172a !important; padding: 0 !important; }
            @page { size: A4 landscape; margin: 1.2cm; }
        }
    </style>
</head>
<body class="bg-slate-950 p-6 sm:p-10 text-slate-100 font-sans">
    <div class="no-print mx-auto mb-6 flex max-w-6xl items-center justify-between">
        <p class="text-sm text-slate-400">Pratinjau laporan seluruh transaksi peminjaman.</p>
        <div class="flex gap-2">
            <button onclick="window.print()" class="rounded-xl bg-cyan-300 px-4 py-2 text-sm font-semibold text-slate-950">Cetak</button>
            <button onclick="window.close()" class="rounded-xl border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-300">Tutup</button>
        </div>
    </div>

    <main class="print-report mx-auto max-w-6xl rounded-2xl border border-slate-700 bg-slate-900 p-8">
        <header class="mb-6 flex items-start justify-between border-b border-slate-700 pb-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Laporan Semua Riwayat Pinjaman</h1>
                <p class="mt-1 text-sm text-slate-400">StarDeck — Pusat Rekreasi Orbital</p>
            </div>
            <div class="text-right text-xs text-slate-400">
                <div>Dicetak: {{ now()->format('d M Y H:i') }} WIB</div>
                <div>Total transaksi: {{ $loans->count() }}</div>
            </div>
        </header>

        @if(($filters['borrowed_at'] ?? null) || ($filters['returned_at'] ?? null) || ($filters['status'] ?? null))
            <p class="mb-4 text-xs text-slate-400">
                Filter:
                @if($filters['borrowed_at'] ?? null) tanggal pinjam {{ \Carbon\Carbon::parse($filters['borrowed_at'])->format('d M Y') }} @endif
                @if($filters['returned_at'] ?? null) • tanggal kembali {{ \Carbon\Carbon::parse($filters['returned_at'])->format('d M Y') }} @endif
                @if($filters['status'] ?? null) • status {{ $filters['status'] === 'returned' ? 'dikembalikan' : 'aktif' }} @endif
            </p>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-600 text-[10px] uppercase text-slate-400">
                        <th class="p-2">Peminjam</th>
                        <th class="p-2">Unit</th>
                        <th class="p-2">Tanggal Pinjam</th>
                        <th class="p-2">Jatuh Tempo</th>
                        <th class="p-2">Tanggal Kembali</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Terlambat</th>
                        <th class="p-2">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr class="border-b border-slate-800">
                            <td class="p-2">{{ $loan->member->name }}</td>
                            <td class="p-2">{{ $loan->unit->unit_code }} — {{ $loan->unit->name }}</td>
                            <td class="p-2">{{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d/m/Y') }}</td>
                            <td class="p-2">{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                            <td class="p-2">{{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d/m/Y') : '—' }}</td>
                            <td class="p-2">{{ $loan->status === 'returned' ? 'Dikembalikan' : 'Aktif' }}</td>
                            <td class="p-2">{{ $loan->status === 'returned' ? $loan->late_days . ' hari' : ($loan->isOverdue() ? $loan->estimatedLateDays() . ' hari' : '—') }}</td>
                            <td class="p-2">Rp {{ number_format($loan->status === 'returned' ? $loan->fine_amount : $loan->estimatedFine(), 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-4 text-center">Tidak ada transaksi sesuai filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
