@extends('layouts.app')
@section('title', 'Profil Saya — StarDeck')

@section('content')
<div class="mb-4">
    <h1 class="h4 mb-1"><i class="bi bi-person-circle me-2"></i>Profil Saya</h1>
    <p class="text-muted small mb-0">Informasi identitas kru dan penugasan stasiun Anda.</p>
</div>

<div class="card shadow-sm border-0" style="max-width: 750px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-4">
            @if($profile && $profile->photo)
                <img src="{{ asset('storage/' . $profile->photo) }}" alt="{{ $user->name }}" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
            @else
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3 fw-bold fs-2" style="width: 80px; height: 80px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h2 class="h4 mb-1">{{ $user->name }}</h2>
                <div class="text-muted">{{ $user->email }}</div>
                <span class="badge bg-primary mt-1">Astronot (Member)</span>
            </div>
        </div>

        <hr>

        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <span class="text-muted d-block small">Pangkat / Posisi</span>
                <span class="fw-semibold">{{ $profile->rank_or_position ?? '-' }}</span>
            </div>
            <div class="col-sm-6">
                <span class="text-muted d-block small">Modul Stasiun</span>
                <span class="fw-semibold">{{ $profile->station_module ?? '-' }}</span>
            </div>
            <div class="col-sm-6">
                <span class="text-muted d-block small">Nomor Telepon</span>
                <span class="fw-semibold">{{ $profile->phone ?? '-' }}</span>
            </div>
            <div class="col-sm-6">
                <span class="text-muted d-block small">Alamat / Lokasi Kamar</span>
                <span>{{ $profile->address ?: '-' }}</span>
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit Profil Saya
        </a>
    </div>
</div>
@endsection
