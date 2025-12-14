@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <h1 class="text-3xl font-bold mb-6">Dashboard Admin</h1>

    <!-- Welcome Card -->
    <div class="bg-white p-6 shadow-md rounded-lg mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-600">Kelola user dan lihat statistik sistem di sini.</p>
        </div>
        <div>
            <a href="{{ route('admin.auth.register') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                Tambah User Baru
            </a>
        </div>
    </div>



    <!-- Tabel User -->
    <div class="bg-white shadow-lg rounded-xl p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-2xl font-semibold text-gray-800">Daftar User</h2>
        <span class="text-sm text-gray-500">
            Total: {{ $users->count() }} user
        </span>
    </div>

    <div class="overflow-x-auto rounded-lg border">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 font-semibold">#</th>
                    <th class="px-6 py-3 font-semibold">Nama</th>
                    <th class="px-6 py-3 font-semibold">Email</th>
                    <th class="px-6 py-3 font-semibold">Role</th>
                    <th class="px-6 py-3 font-semibold">Status</th>
                    <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-600">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-800">
                            {{ $user->name }}
                        </div>
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $user->email }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            bg-blue-100 text-blue-700">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1
                                bg-green-100 text-green-700
                                px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-check-circle"></i> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1
                                bg-red-100 text-red-700
                                px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-times-circle"></i> Nonaktif
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="p-2 rounded-lg text-blue-600
                                      hover:bg-blue-50 hover:text-blue-800
                                      transition"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.users.destroy', $user->id) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Yakin ingin menghapus user ini?')"
                                        class="p-2 rounded-lg text-red-600
                                               hover:bg-red-50 hover:text-red-800
                                               transition"
                                        title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection
