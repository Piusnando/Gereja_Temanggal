@extends('layouts.admin')
@section('content')
<div x-data="{ modalOpen: false, selectedWilayah: '' }">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Database Anggota {{ $dbCategory }}</h1>
            <p class="text-sm text-gray-500">Rekap data anggota, umur, dan persentase keaktifan.</p>
        </div>
        <button @click="modalOpen = true" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow">+ Tambah Anggota</button>
    </div>

    @if(session('success')) <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div> @endif

    <!-- Tabel Anggota -->
    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">Asal Wilayah/Lingk.</th>
                    <th class="px-6 py-4">TTL & Umur</th>
                    <th class="px-6 py-4">Keaktifan</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($members as $m)
                <tr class="hover:bg-blue-50">
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-900 block">{{ $m->name }}</span>
                        <span class="text-xs text-blue-600 font-semibold">{{ $m->baptism_name ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="block font-bold text-gray-700">{{ $m->territory->name ?? '-' }}</span>
                        <span class="text-xs">{{ $m->lingkungan->name ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="block">{{ $m->birth_place ?? '-' }}, {{ $m->birth_date ? $m->birth_date->format('d M Y') : '-' }}</span>
                        <span class="text-xs font-bold text-green-600">{{ $m->age }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $m->attendance_percentage }}%"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500">{{ $m->attendance_percentage }}% Hadir Kegiatan</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center items-center gap-2">
                            <!-- Tombol Edit Baru -->
                            <a href="{{ route('admin.youth.members.edit', ['category' => $categoryUrl, 'id' => $m->id]) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded transition" title="Edit Data">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            
                            <!-- Tombol Hapus Lama yang Dipercantik -->
                            <form action="{{ route('admin.youth.members.destroy',['category' => $categoryUrl, 'id' => $m->id]) }}" method="POST" onsubmit="return confirm('Hapus anggota ini? Data absensinya juga akan ikut terhapus!');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition" title="Hapus Anggota">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada anggota terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t">{{ $members->links() }}</div>
    </div>

    <!-- Modal Tambah Anggota -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4" x-cloak>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
            <h3 class="text-xl font-bold mb-4">Tambah Anggota {{ $dbCategory }}</h3>
            <form action="{{ route('admin.youth.members.store', $categoryUrl) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold">Nama Lengkap</label><input type="text" name="name" class="w-full border rounded p-2" required></div>
                    <div><label class="text-xs font-bold">Nama Baptis</label><input type="text" name="baptism_name" class="w-full border rounded p-2"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold">Tempat Lahir</label><input type="text" name="birth_place" class="w-full border rounded p-2"></div>
                    <div><label class="text-xs font-bold">Tanggal Lahir</label><input type="date" name="birth_date" class="w-full border rounded p-2"></div>
                </div>
                <div><label class="text-xs font-bold">Alamat Lengkap</label><input type="text" name="address" class="w-full border rounded p-2"></div>
                
                <!-- Dependent Dropdown Wilayah & Lingkungan -->
                <div class="grid grid-cols-2 gap-4" x-data="{ territories: {{ \Illuminate\Support\Js::from($territories) }} }">
                    <div>
                        <label class="text-xs font-bold">Wilayah</label>
                        <select name="territory_id" x-model="selectedWilayah" class="w-full border rounded p-2 bg-white">
                            <option value="">Pilih Wilayah</option>
                            <template x-for="t in territories" :key="t.id"><option :value="t.id" x-text="t.name"></option></template>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold">Lingkungan</label>
                        <select name="lingkungan_id" class="w-full border rounded p-2 bg-white" :disabled="!selectedWilayah">
                            <option value="">Pilih Lingkungan</option>
                            <template x-for="t in territories"><template x-if="t.id == selectedWilayah">
                                <template x-for="l in t.lingkungans" :key="l.id"><option :value="l.id" x-text="l.name"></option></template>
                            </template></template>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-gray-200 rounded font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection