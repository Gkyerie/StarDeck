@extends('layouts.app')
@section('title', 'Catat Peminjaman — StarDeck')

@section('content')
<div class="mb-4">
    <h1 class="h4 mb-1"><i class="bi bi-box-arrow-up-right me-2"></i>Catat Peminjaman Unit</h1>
    <p class="text-muted small mb-0">Pilih astronot/anggota stasiun dan unit yang tersedia. Durasi peminjaman standar adalah 5 hari.</p>
</div>

<div class="card shadow-sm border-0" style="max-width: 750px;">
    <div class="card-body p-4">
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="member_id" class="form-label">Anggota Kru (Peminjam) <span class="text-danger">*</span></label>
                <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ old('member_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->name }} ({{ $m->email }}) — {{ $m->memberProfile->rank_or_position ?? 'Astronot' }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Maksimal 2 peminjaman aktif per anggota stasiun.</div>
                @error('member_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="unit_id" class="form-label">Unit Tersedia <span class="text-danger">*</span></label>
                <select class="form-select @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" required>
                    <option value="">-- Pilih Unit --</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>
                            [{{ $u->unit_code }}] {{ $u->name }} ({{ $u->type === 'board_game' ? 'Board Game' : 'Console' }})
                        </option>
                    @endforeach
                </select>
                @error('unit_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info py-2 small mb-4">
                <i class="bi bi-info-circle me-1"></i> Tanggal peminjaman akan otomatis tercatat hari ini, dan tanggal jatuh tempo adalah <strong>+5 hari kalender</strong>. Petugas pencatat otomatis akun Komandan yang sedang aktif.
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('loans.active') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Konfirmasi Peminjaman</button>
            </div>
        </form>
    </div>
</div>
@endsection
