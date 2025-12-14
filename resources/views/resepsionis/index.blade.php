@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Data Resepsionis</h2>

        <a href="{{ route('admin.resepsionis.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
            + Tambah Resepsionis
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Username</th>
                    <th class="px-4 py-2 border">Outlet</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($resepsionis as $r)
                <tr class="border">
                    <td class="px-4 py-2 border text-center">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $r->nama }}</td>
                    <td class="px-4 py-2 border">{{ $r->username }}</td>
                    <td class="px-4 py-2 border">{{ $r->outlet->nama }}</td>

                    <td class="px-4 py-2 border">
                        <a href="{{ route('admin.resepsionis.edit', $r->id) }}"
                           class="text-yellow-500 font-semibold hover:underline mr-3">
                            Edit
                        </a>

                        <form action="{{ route('admin.resepsionis.destroy', $r->id) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus data ini?')"
                                    class="text-red-600 font-semibold hover:underline">
                                Hapus
                            </button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection 
