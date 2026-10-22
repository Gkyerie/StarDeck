@extends('layouts.app')
@section('title', 'Edit Anggota — StarDeck')

@section('content')
<div class="mb-4">
    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Anggota
    </a>
    <h1 class="h4 mb-0"><i class="bi bi-pencil me-2"></i>Edit Anggota: {{ $member->name }}</h1>
</div>

<div class="card shadow-sm border-0" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="{{ route('members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h5 class="card-title mb-3 text-primary"><i class="bi bi-person-badge me-2"></i>Informasi Akun</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $member->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $member->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Password <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h5 class="card-title mb-3 text-primary"><i class="bi bi-card-heading me-2"></i>Profil Kru / Modul</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $member->memberProfile->phone ?? '') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="rank_or_position" class="form-label">Pangkat / Posisi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('rank_or_position') is-invalid @enderror" id="rank_or_position" name="rank_or_position" value="{{ old('rank_or_position', $member->memberProfile->rank_or_position ?? '') }}" required>
                    @error('rank_or_position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="station_module" class="form-label">Modul Stasiun <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('station_module') is-invalid @enderror" id="station_module" name="station_module" value="{{ old('station_module', $member->memberProfile->station_module ?? '') }}" required>
                    @error('station_module')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="photo" class="form-label">Foto Profil <small class="text-muted">(JPG/PNG, maks. 2MB)</small></label>
                    @if($member->memberProfile && $member->memberProfile->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $member->memberProfile->photo) }}" alt="Foto" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/jpeg,image/png">
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="address" class="form-label">Alamat / Lokasi Kamar <small class="text-muted">(Opsional)</small></label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $member->memberProfile->address ?? '') }}">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Perbarui Anggota</button>
            </div>
        </form>
    </div>
</div>
@endsection
