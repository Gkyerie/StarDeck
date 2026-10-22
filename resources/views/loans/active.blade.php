@extends('layouts.app')
@section('title', 'Pinjaman Aktif — StarDeck')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1"><i class="bi bi-clock-history me-2"></i>Daftar Pinjaman Aktif</h1>
        <p class="text-muted small mb-0">Pantau seluruh unit yang sedang berada di tangan kru stasiun.</p>
    </div>
    <a href="{{ route('loans.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Peminjaman Baru
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        @if($loans->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-check2-circle text-success display-4 d-block mb-3"></i>
                <h5 class="text-muted">Tidak ada pinjaman aktif saat ini</h5>
                <p class="text-muted small">Semua unit yang dipinjam telah dikembalikan ke stasiun.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Unit</th>
                            <th>Peminjam (Kru)</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status Keterlambatan</th>
                            <th>Petugas Catat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                            @php
                                $isOverdue = $loan->due_date < now()->toDateString();
                                $daysOverdue = $isOverdue ? \Carbon\Carbon::parse($loan->due_date)->diffInDays(now()->toDateString()) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-secondary font-monospace">{{ $loan->unit->unit_code }}</span>
                                    <div class="fw-semibold">{{ $loan->unit->name }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $loan->member->name }}</div>
                                    <div class="text-muted small">{{ $loan->member->email }}</div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                                <td>
                                    @if($isOverdue)
                                        <span class="badge bg-danger">Terlambat {{ $daysOverdue }} hari</span>
                                        <div class="small text-danger fw-semibold mt-1">
                                            Est. Denda: Rp{{ number_format($daysOverdue * 5000, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <span class="badge bg-success">Tepat Waktu</span>
                                    @endif
                                </td>
                                <td><span class="text-muted small">{{ $loan->processedBy->name ?? '-' }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('loans.return', $loan->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-box-arrow-in-down-left me-1"></i>Proses Kembali
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
