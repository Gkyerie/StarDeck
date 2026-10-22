@extends('layouts.app')
@section('title', 'Konfirmasi Pengembalian — StarDeck')

@section('content')
<div class="mb-4">
    <a href="{{ route('loans.active') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Pinjaman Aktif
    </a>
    <h1 class="h4 mb-0"><i class="bi bi-box-arrow-in-down-left me-2"></i>Konfirmasi Pengembalian Unit</h1>
</div>

@php
    $today = now()->toDateString();
    $isOverdue = $today > $loan->due_date;
    $lateDays = $isOverdue ? \Carbon\Carbon::parse($loan->due_date)->diffInDays($today) : 0;
    $fineAmount = $lateDays * 5000;
@endphp

<div class="card shadow-sm border-0" style="max-width: 750px;">
    <div class="card-body p-4">
        <h5 class="card-title mb-3 text-primary"><i class="bi bi-info-circle me-2"></i>Rincian Peminjaman</h5>

        <div class="table-responsive mb-4">
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <th class="bg-light" style="width: 35%;">Unit</th>
                        <td>
                            <span class="badge bg-secondary font-monospace">{{ $loan->unit->unit_code }}</span>
                            <strong class="ms-1">{{ $loan->unit->name }}</strong>
                            <span class="text-muted small">({{ $loan->unit->type === 'board_game' ? 'Board Game' : 'Console' }})</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Peminjam (Kru)</th>
                        <td>{{ $loan->member->name }} ({{ $loan->member->email }})</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Tanggal Peminjaman</th>
                        <td>{{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Jatuh Tempo</th>
                        <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Tanggal Pengembalian</th>
                        <td><strong>{{ now()->format('d F Y') }}</strong> (Hari Ini)</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Keterlambatan</th>
                        <td>
                            @if($lateDays > 0)
                                <span class="badge bg-danger">{{ $lateDays }} Hari</span>
                            @else
                                <span class="badge bg-success">0 Hari (Tepat Waktu)</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Denda Keterlambatan</th>
                        <td>
                            @if($fineAmount > 0)
                                <span class="fs-5 fw-bold text-danger">Rp{{ number_format($fineAmount, 0, ',', '.') }}</span>
                                <div class="text-muted small">Tarif denda: Rp5.000 / hari keterlambatan kalender</div>
                            @else
                                <span class="fs-5 fw-bold text-success">Rp0</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form action="{{ route('loans.process-return', $loan->id) }}" method="POST">
            @csrf
            <div class="alert alert-warning small mb-4">
                <i class="bi bi-exclamation-triangle me-1"></i> Setelah pengembalian diproses, status loan akan berubah menjadi <strong>returned</strong>, status unit kembali menjadi <strong>available</strong>, dan perhitungan denda final disimpan. Tindakan ini tidak dapat dibatalkan.
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('loans.active') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2-circle me-1"></i>Proses & Selesaikan Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
