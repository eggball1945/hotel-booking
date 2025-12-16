@extends('layouts.app')

@section('title', 'Daftar Kamar')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-bed me-2"></i>Daftar Kamar
        </h2>

        @auth
            @if(in_array(auth()->user()->role, ['admin','resepsionis']))
                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Tambah Room
                </a>
            @endif
        @endauth
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor Kamar</th>
                            <th>Tipe Kamar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                            <tr>
                                <td>
                                    {{ $loop->iteration + ($rooms->currentPage() - 1) * $rooms->perPage() }}
                                </td>

                                <td>
                                    {{ $room->room_number }}
                                </td>

                                <td>
                                    {{-- AMAN DARI NULL --}}
                                    {{ optional($room->roomType)->name ?? '-' }}
                                </td>

                                <td>
                                    @if($room->status === 'available')
                                        <span class="badge bg-success">Available</span>
                                    @elseif($room->status === 'booked')
                                        <span class="badge bg-warning text-dark">Booked</span>
                                    @else
                                        <span class="badge bg-danger">Maintenance</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('tamu.rooms.show', $room->id) }}"
                                       class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @auth
                                        @if(in_array(auth()->user()->role, ['admin','resepsionis']))
                                            <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                               class="btn btn-sm btn-warning text-white">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.rooms.destroy', $room->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-bed fa-2x mb-2"></i>
                                    <br>
                                    Tidak ada data kamar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="mt-3">
        {{ $rooms->links() }}
    </div>

</div>
@endsection
