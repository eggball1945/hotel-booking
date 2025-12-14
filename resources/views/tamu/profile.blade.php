@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>Profil Saya
                    </h4>
                </div>

                <div class="card-body">
                    {{-- Flash message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Update profile --}}
                    <form method="POST" action="{{ route('tamu.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text"
                                   class="form-control"
                                   name="name"
                                   value="{{ auth()->user()->name }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   class="form-control"
                                   value="{{ auth()->user()->email }}"
                                   disabled>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ ucfirst(auth()->user()->role) }}"
                                   disabled>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </form>

                    <hr class="my-4">

                    {{-- Update password --}}
                    <h5 class="mb-3">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </h5>

                    <form method="POST" action="{{ route('tamu.profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password"
                                   class="form-control"
                                   name="password"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password"
                                   class="form-control"
                                   name="password_confirmation"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-key me-2"></i>Ubah Password
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('tamu.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
