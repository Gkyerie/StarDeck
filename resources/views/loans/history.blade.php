@extends('layouts.app')
@section('title', 'Riwayat Pinjaman: ' . $member->name . ' — StarDeck')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Anggota
        </a>
        <h1 class="h4 mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Pinjaman: {{ $member->name }}</h1>
        <div class="text-muted small">{{ $member->email }} • {{ $member->memberProfile->rank_or_position ?? 'Astronot' }}</div>
    </div>
    <a href="{{ route('loans.print-history', $member->id) }}" target="_blank" class="btn btn-outline-primary">
        <i class="bi bi-printer me-1"></i>Cetak Riwayat (A4)
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        @if($loans->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted display-4 d-block mb-3"></i>
                <h5 class="text-muted">Anggota ini belum pernah meminjam unit</h5>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Unit</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Keterlambatan</th>
                            <th>Denda</th>
                            <th>Petugas Pinjam</th>
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
                                <td>{{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                                <td>{{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d M Y') : '-' }}</td>
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
                                    @if($loan->status === 'returned')
                                        {{ $loan->late_days > 0 ? $loan->late_days . ' hari' : '-' }}
                                    @else
                                        {{ $isOverdue ? $daysOverdue . ' hari (berjalan)' : '-' }}
                                    @endif
                                </td>
                                <td>
                                    @if($loan->status === 'returned')
                                        @if($loan->fine_amount > 0)
                                            <span class="text-danger fw-semibold">Rp{{ number_format($loan->fine_amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-success">Rp0</span>
                                        @endif
                                    @else
                                        @if($isOverdue)
                                            <span class="text-danger small">Est. Rp{{ number_format($daysOverdue * 5000, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    @endif
                                </td>
                                <td><span class="text-muted small">{{ $loan->processedBy->name ?? '-' }}</span></td>
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
