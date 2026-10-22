<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Peminjaman — {{ $member->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 13px;
            color: #111;
            background: #fff;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body class="p-4">
    <div class="no-print mb-4 d-flex justify-content-between align-items-center border-bottom pb-3">
        <div>
            <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Cetak Halaman (A4)</button>
            <button onclick="window.close()" class="btn btn-outline-secondary ms-2">Tutup</button>
        </div>
        <div class="text-muted small">
            Tip: Navigasi dan tombol ini akan otomatis disembunyikan saat mencetak.
        </div>
    </div>

    <!-- Header Dokumen -->
    <div class="row mb-4 border-bottom pb-3">
        <div class="col-8">
            <h2 class="h4 mb-0 fw-bold">STARDECK STATION LOG</h2>
            <p class="text-muted small mb-0">Sistem Pengelolaan Peminjaman Board Game & Console Stasiun Antariksa</p>
        </div>
        <div class="col-4 text-end">
            <div class="small fw-semibold">Tanggal Cetak:</div>
            <div class="text-muted small">{{ now()->format('d F Y, H:i') }} WIB</div>
        </div>
    </div>

    <!-- Identitas Anggota -->
    <div class="card mb-4 border">
        <div class="card-body p-3">
            <h5 class="card-title h6 fw-bold mb-3">DATA ANGGOTA KRU</h5>
            <div class="row g-2">
                <div class="col-6">
                    <span class="text-muted small d-block">Nama Lengkap:</span>
                    <strong>{{ $member->name }}</strong>
                </div>
                <div class="col-6">
                    <span class="text-muted small d-block">Email:</span>
                    <strong>{{ $member->email }}</strong>
                </div>
                <div class="col-6">
                    <span class="text-muted small d-block">Pangkat / Posisi:</span>
                    <span>{{ $member->memberProfile->rank_or_position ?? '-' }}</span>
                </div>
                <div class="col-6">
                    <span class="text-muted small d-block">Modul Stasiun:</span>
                    <span>{{ $member->memberProfile->station_module ?? '-' }}</span>
                </div>
                <div class="col-6">
                    <span class="text-muted small d-block">Nomor Kontak:</span>
                    <span>{{ $member->memberProfile->phone ?? '-' }}</span>
                </div>
                <div class="col-6">
                    <span class="text-muted small d-block">Kamar / Quarters:</span>
                    <span>{{ $member->memberProfile->address ?: '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <h5 class="h6 fw-bold mb-2">CATATAN RIWAYAT PEMINJAMAN</h5>
    <table class="table table-bordered table-sm mb-4">
        <thead class="table-light">
            <tr>
                <th style="width: 5%;">No</th>
                <th>Kode Unit</th>
                <th>Nama Unit</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Terlambat</th>
                <th>Denda</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $index => $loan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace">{{ $loan->unit->unit_code }}</td>
                    <td>{{ $loan->unit->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                    <td>{{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($loan->status === 'returned')
                            Selesai (Kembali)
                        @else
                            Dipinjam
                        @endif
                    </td>
                    <td>{{ $loan->late_days > 0 ? $loan->late_days . ' hari' : '-' }}</td>
                    <td>{{ $loan->fine_amount > 0 ? 'Rp' . number_format($loan->fine_amount, 0, ',', '.') : 'Rp0' }}</td>
                    <td>{{ $loan->processedBy->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-3 text-muted">Belum ada riwayat peminjaman untuk anggota ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Validasi -->
    <div class="row mt-5 pt-4">
        <div class="col-6 text-center">
            <p class="mb-5 small">Anggota Kru Peminjam,</p>
            <p class="fw-bold mb-0">({{ $member->name }})</p>
        </div>
        <div class="col-6 text-center">
            <p class="mb-5 small">Komandan Stasiun / Petugas Pengawas,</p>
            <p class="fw-bold mb-0">({{ auth()->user()->name }})</p>
        </div>
    </div>
</body>
</html>
