@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 400px; margin-top: 80px;">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="fw-bold text-center mb-4">Admin / Resepsionis Login</h3>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection
