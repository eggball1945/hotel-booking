@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3>Edit Resepsionis</h3>

    <form action="{{ route('admin.resepsionis.update', $resepsionis->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama" 
                   value="{{ $resepsionis->nama }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" 
                   value="{{ $resepsionis->username }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password (kosongkan jika tidak ganti)</label>
            <input type="password" class="form-control" name="password">
        </div>

        <div class="mb-3">
            <label class="form-label">Pilih Outlet</label>
            <select class="form-select" name="id_outlet" required>
                @foreach($outlet as $o)
                    <option value="{{ $o->id }}" 
                        {{ $o->id == $resepsionis->id_outlet ? 'selected' : '' }}>
                        {{ $o->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.resepsionis.index') }}" class="btn btn-secondary">Kembali</a>

    </form>

</div>
@endsection
