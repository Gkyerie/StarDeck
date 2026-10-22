@extends('layouts.app')

@section('title', 'Dashboard — StarDeck')

@section('content')
<h1 class="h4 mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard Komandan</h1>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $totalUnits }}</div>
                    <div class="stat-label">Total Unit</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-box-seam"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $availableUnits }}</div>
                    <div class="stat-label">Unit Tersedia</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $activeLoans }}</div>
                    <div class="stat-label">Pinjaman Aktif</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-journal-arrow-up"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $overdueLoans }}</div>
                    <div class="stat-label">Terlambat</div>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-people me-1"></i> Anggota</h6>
                <div class="stat-value">{{ $totalMembers }}</div>
                <div class="stat-label mb-3">anggota terdaftar</div>
                <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-secondary">Kelola Anggota</a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-clock-history me-1"></i> Transaksi Terbaru</h6>
                <a href="{{ route('loans.active') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($recentLoans->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-inbox d-block"></i>
                        <p>Belum ada transaksi.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Anggota</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLoans as $loan)
                                    <tr>
                                        <td>{{ $loan->member->name ?? '-' }}</td>
                                        <td><code>{{ $loan->unit->unit_code ?? '-' }}</code> {{ $loan->unit->name ?? '' }}</td>
                                        <td>
                                            @if($loan->isReturned())
                                                <span class="badge badge-returned">Dikembalikan</span>
                                            @elseif($loan->isOverdue())
                                                <span class="badge badge-overdue">Terlambat</span>
                                            @else
                                                <span class="badge badge-borrowed">Dipinjam</span>
                                            @endif
                                        </td>
                                        <td class="small">{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
