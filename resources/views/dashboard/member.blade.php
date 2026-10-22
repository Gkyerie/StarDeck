@extends('layouts.app')

@section('title', 'Dashboard — StarDeck')

@section('content')
<h1 class="h4 mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard Astronot</h1>

<div class="row g-3 mb-4">
    <div class="col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $activeLoans->count() }}</div>
                    <div class="stat-label">Pinjaman Aktif</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-journal-arrow-up"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ 2 - $activeLoans->count() }}</div>
                    <div class="stat-label">Sisa Kuota Pinjaman</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-plus-circle"></i></div>
            </div>
        </div>
    </div>
</div>

@if($activeLoans->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header bg-transparent">
            <h6 class="mb-0"><i class="bi bi-journal-arrow-up me-1"></i> Pinjaman Aktif Anda</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Unit</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Estimasi Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeLoans as $loan)
                            <tr>
                                <td><code>{{ $loan->unit->unit_code ?? '-' }}</code></td>
                                <td>{{ $loan->unit->name ?? '-' }}</td>
                                <td>{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                                <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($loan->isOverdue())
                                        <span class="badge badge-overdue">Terlambat</span>
                                    @else
                                        <span class="badge badge-borrowed">Dipinjam</span>
                                    @endif
                                </td>
                                <td>
                                    @if($loan->isOverdue())
                                        <span class="text-danger">Rp{{ number_format($loan->estimatedFine(), 0, ',', '.') }}</span>
                                        <small class="text-muted">(estimasi)</small>
                                    @else
                                        <span class="text-success">Rp0</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-clock-history me-1"></i> Riwayat Terbaru</h6>
        <a href="{{ route('loans.my') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        @if($recentLoans->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox d-block"></i>
                <p>Belum ada riwayat pinjaman.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Unit</th>
                            <th>Pinjam</th>
                            <th>Kembali</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLoans as $loan)
                            <tr>
                                <td><code>{{ $loan->unit->unit_code ?? '-' }}</code></td>
                                <td>{{ $loan->unit->name ?? '-' }}</td>
                                <td>{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                                <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($loan->isReturned())
                                        Rp{{ number_format($loan->fine_amount, 0, ',', '.') }}
                                    @elseif($loan->isOverdue())
                                        <span class="text-danger">~Rp{{ number_format($loan->estimatedFine(), 0, ',', '.') }}</span>
                                        <small class="text-muted">(estimasi)</small>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
