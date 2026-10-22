@extends('layouts.app')
@section('title', 'Edit Profil Saya — StarDeck')

@section('content')
<div class="mb-4">
    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Profil
    </a>
    <h1 class="h4 mb-0"><i class="bi bi-pencil me-2"></i>Edit Profil Saya</h1>
</div>

<div class="card shadow-sm border-0" style="max-width: 750px;">
    <div class="card-body p-4">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                    <div class="form-text">Perubahan nama dilakukan melalui Komandan Stasiun (Admin).</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="rank_or_position" class="form-label">Pangkat / Posisi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('rank_or_position') is-invalid @enderror" id="rank_or_position" name="rank_or_position" value="{{ old('rank_or_position', $profile->rank_or_position ?? '') }}" required>
                    @error('rank_or_position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="station_module" class="form-label">Modul Stasiun <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('station_module') is-invalid @enderror" id="station_module" name="station_module" value="{{ old('station_module', $profile->station_module ?? '') }}" required>
                    @error('station_module')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="photo" class="form-label">Foto Profil <small class="text-muted">(JPG/PNG, maks. 2MB)</small></label>
                    @if($profile && $profile->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $profile->photo) }}" alt="Foto" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/jpeg,image/png">
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">Alamat / Lokasi Kamar <small class="text-muted">(Opsional)</small></label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $profile->address ?? '') }}">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
