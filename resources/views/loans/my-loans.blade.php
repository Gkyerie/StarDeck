@extends('layouts.app')
@section('title', 'Pinjaman Saya — StarDeck')

@section('content')
<div class="mb-4">
    <h1 class="h4 mb-1"><i class="bi bi-collection me-2"></i>Pinjaman Saya</h1>
    <p class="text-muted small mb-0">Riwayat dan status peminjaman unit board game / console Anda.</p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        @if($loans->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted display-4 d-block mb-3"></i>
                <h5 class="text-muted">Belum ada riwayat peminjaman</h5>
                <p class="text-muted small">Kunjungi katalog unit untuk melihat board game dan console yang tersedia di stasiun.</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary btn-sm">Buka Katalog</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Unit</th>
                            <th>Tipe</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Tanggal Kembali</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                            @php
                                $isOverdue = $loan->status === 'borrowed' && $loan->due_date < now()->toDateString();
                                $daysOverdue = $isOverdue ? \Carbon\Carbon::parse($loan->due_date)->diffInDays(now()->toDateString()) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-secondary font-monospace">{{ $loan->unit->unit_code }}</span>
                                    <div class="fw-semibold">{{ $loan->unit->name }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $loan->unit->type === 'board_game' ? 'Board Game' : 'Console' }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                                <td>
                                    @if($loan->status === 'borrowed')
                                        @if($isOverdue)
                                            <span class="badge bg-danger">Sedang Dipinjam (Terlambat)</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Sedang Dipinjam</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    @if($loan->status === 'returned')
                                        @if($loan->fine_amount > 0)
                                            <span class="text-danger fw-semibold">Rp{{ number_format($loan->fine_amount, 0, ',', '.') }}</span>
                                            <div class="small text-muted">({{ $loan->late_days }} hari)</div>
                                        @else
                                            <span class="text-success">Rp0</span>
                                        @endif
                                    @else
                                        @if($isOverdue)
                                            <span class="text-danger small fw-semibold">Est. Rp{{ number_format($daysOverdue * 5000, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    @endif
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
