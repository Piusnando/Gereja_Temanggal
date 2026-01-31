@extends('layouts.admin')

@section('content')

    {{-- 1. HEADER HALAMAN --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="text-center md:text-left">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h1>
            <p class="text-sm text-gray-500">Kelola akun untuk Admin, Pengurus, OMK, dan Petugas Liturgi.</p>
        </div>
        
        <a href="{{ route('admin.users.create') }}" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center justify-center transition duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah User Baru
        </a>
    </div>

    {{-- 2. FLASH MESSAGE --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center" role="alert">
        <div><strong class="font-bold">Berhasil!</strong> <span class="block sm:inline">{{ session('success') }}</span></div>
        <button @click="show = false" class="text-green-700 font-bold hover:text-green-900">×</button>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center" role="alert">
        <div><strong class="font-bold">Gagal!</strong> <span class="block sm:inline">{{ session('error') }}</span></div>
        <button @click="show = false" class="text-red-700 font-bold hover:text-red-900">×</button>
    </div>
    @endif

    {{-- 3. TABEL DATA --}}
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        
        <!-- Wrapper Scroll Horizontal untuk layar sangat kecil -->
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">Pengguna</th>
                        
                        <!-- Kolom Email disembunyikan di Mobile (hidden), muncul di Tablet keatas (md:table-cell) -->
                        <th class="px-4 py-3 text-left hidden md:table-cell">Email</th>
                        
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        
                        <!-- KOLOM NAMA & AVATAR (Responsive) -->
                        <td class="px-4 py-4 align-middle">
                            <div class="flex items-center">
                                <!-- Avatar Logic -->
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 border border-blue-200 shrink-0 overflow-hidden">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="overflow-hidden">
                                    <p class="font-bold text-gray-800 truncate max-w-[150px] sm:max-w-none">{{ $user->name }}</p>
                                    
                                    <!-- Email muncul disini HANYA pada tampilan Mobile (md:hidden) -->
                                    <p class="text-xs text-gray-500 md:hidden truncate max-w-[150px]">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- KOLOM EMAIL (Desktop Only) -->
                        <td class="px-4 py-4 text-gray-600 hidden md:table-cell">
                            {{ $user->email }}
                        </td>

                        <!-- KOLOM ROLE -->
                        <td class="px-4 py-4 align-middle">
                            @php
                                $roleName = $user->role;
                                $badgeClass = 'bg-gray-100 text-gray-800';
                                $labelText = ucwords(str_replace('_', ' ', $roleName));

                                // Custom Warna & Label
                                switch($roleName) {
                                    case 'admin':
                                        $badgeClass = 'bg-red-100 text-red-800 border border-red-200';
                                        break;
                                    case 'pengurus_gereja':
                                        $badgeClass = 'bg-blue-100 text-blue-800 border border-blue-200';
                                        break;
                                    case 'direktur_musik':
                                        $badgeClass = 'bg-purple-100 text-purple-800 border border-purple-200';
                                        break;
                                    case 'omk':
                                        $badgeClass = 'bg-orange-100 text-orange-800 border border-orange-200';
                                        $labelText = 'OMK';
                                        break;
                                    case 'pia_pir':
                                        $badgeClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                                        $labelText = 'PIA / PIR';
                                        break;
                                    default: // Misdinar & Lektor
                                        $badgeClass = 'bg-green-100 text-green-800 border border-green-200';
                                        break;
                                }
                            @endphp
                            <!-- Pada mobile text role lebih kecil (text-[10px]) -->
                            <span class="inline-block px-2 py-1 font-semibold leading-tight rounded-full text-[10px] md:text-xs {{ $badgeClass }} whitespace-nowrap">
                                {{ $labelText }}
                            </span>
                        </td>

                        <!-- KOLOM AKSI -->
                        <td class="px-4 py-4 text-center align-middle">
                            <div class="flex justify-center items-center gap-2">
                                
                                <!-- Tombol Edit -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded transition" title="Edit Data">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                
                                <!-- Tombol Hapus -->
                                @if(auth()->id() != $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini? \n\nTindakan ini tidak dapat dibatalkan.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded transition" title="Hapus User">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @else
                                    <!-- Placeholder jika diri sendiri -->
                                    <div class="hidden sm:block">
                                        <span class="text-gray-400 text-xs italic px-2 py-1 bg-gray-50 rounded cursor-not-allowed">Akun Saya</span>
                                    </div>
                                    <!-- Icon Gembok untuk mobile -->
                                    <div class="sm:hidden text-gray-400 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-500 italic bg-gray-50">
                            Belum ada data pengguna yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- 4. PAGINATION --}}
        <div class="px-5 py-4 bg-white border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

@endsection