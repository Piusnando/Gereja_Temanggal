@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Input Presensi {{ $category }}</h1>
        
        <!-- Filter Kategori (Tombol Ganti PIA/OMK) -->
        <div class="flex bg-gray-200 rounded-lg p-1">
            <a href="{{ route('admin.youth.attendance.create', ['category' => 'PIA', 'activity_id' => $selectedActivityId]) }}" 
               class="px-4 py-2 text-sm font-bold rounded-md transition {{ $category == 'PIA' ? 'bg-white text-pink-600 shadow' : 'text-gray-500 hover:text-gray-700' }}">
               PIA (Anak)
            </a>
            <a href="{{ route('admin.youth.attendance.create', ['category' => 'OMK', 'activity_id' => $selectedActivityId]) }}" 
               class="px-4 py-2 text-sm font-bold rounded-md transition {{ $category == 'OMK' ? 'bg-white text-blue-600 shadow' : 'text-gray-500 hover:text-gray-700' }}">
               OMK (Muda)
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded flex justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="font-bold">&times;</button>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        
        <form action="{{ route('admin.youth.attendance.store') }}" method="POST">
            @csrf
            <!-- Hidden input untuk kategori agar controller tahu mana yang sedang diedit -->
            <input type="hidden" name="category_filter" value="{{ $category }}">

            <!-- 1. PILIH KEGIATAN -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Kegiatan / Pertemuan</label>
                <!-- Script onchange untuk reload halaman saat ganti kegiatan -->
                <select name="activity_id" id="activity_selector" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 bg-gray-50 font-bold text-gray-700" 
                        onchange="window.location.href='{{ route('admin.youth.attendance.create') }}?category={{ $category }}&activity_id=' + this.value">
                    <option value="" disabled {{ !$selectedActivityId ? 'selected' : '' }}>-- Pilih Kegiatan --</option>
                    @foreach($activities as $act)
                        <option value="{{ $act->id }}" {{ $selectedActivityId == $act->id ? 'selected' : '' }}>
                            {{ $act->start_time->format('d/m/Y') }} - {{ $act->title }} ({{ $act->organizer }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih kegiatan terlebih dahulu untuk memunculkan/mengedit data presensi.</p>
            </div>

            <!-- 2. DAFTAR ANGGOTA (Hanya muncul jika kegiatan sudah dipilih) -->
            @if($selectedActivityId)
                
                <div class="border-t border-gray-100 pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Daftar Anggota {{ $category }}</h3>
                        
                        <!-- Check All -->
                        <label class="inline-flex items-center cursor-pointer text-sm text-blue-600 font-bold hover:underline">
                            <input type="checkbox" id="checkAll" class="form-checkbox h-4 w-4 mr-2">
                            Pilih Semua
                        </label>
                    </div>

                    @if($members->isEmpty())
                        <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-gray-500">Belum ada data anggota untuk kategori {{ $category }}.</p>
                            <a href="{{ route('admin.youth.members.create', ['category' => $category]) }}" class="text-blue-600 font-bold hover:underline text-sm">+ Tambah Anggota Dulu</a>
                        </div>
                    @else
                        <!-- GRID LIST -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($members as $member)
                                @php
                                    $isChecked = in_array($member->id, $attendanceIds);
                                @endphp
                                <label class="flex items-center p-3 rounded-lg border transition cursor-pointer select-none group {{ $isChecked ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200 hover:bg-gray-50' }}">
                                    <input type="checkbox" name="attendees[]" value="{{ $member->id }}" 
                                           class="member-checkbox form-checkbox h-5 w-5 text-green-600 rounded focus:ring-green-500 border-gray-300"
                                           {{ $isChecked ? 'checked' : '' }}>
                                    
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-800 group-hover:text-black">{{ $member->name }}</span>
                                        <span class="block text-xs text-gray-500">{{ $member->lingkungan->name ?? '-' }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end sticky bottom-0 bg-white p-4 shadow-inner md:shadow-none md:p-0 md:static">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-1 w-full md:w-auto">
                            Simpan Presensi
                        </button>
                    </div>
                </div>

            @else
                <!-- Placeholder jika belum pilih kegiatan -->
                <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-gray-500 font-medium">Silakan pilih kegiatan di atas untuk memulai input data.</p>
                </div>
            @endif

        </form>
    </div>
</div>

<script>
    // Script Sederhana untuk Check All
    document.getElementById('checkAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.member-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
            // Trigger visual change (optional)
            if(this.checked) {
                cb.closest('label').classList.add('bg-green-50', 'border-green-200');
                cb.closest('label').classList.remove('bg-white', 'border-gray-200');
            } else {
                cb.closest('label').classList.remove('bg-green-50', 'border-green-200');
                cb.closest('label').classList.add('bg-white', 'border-gray-200');
            }
        });
    });

    // Visual Change on Individual Click
    document.querySelectorAll('.member-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
             if(this.checked) {
                this.closest('label').classList.add('bg-green-50', 'border-green-200');
                this.closest('label').classList.remove('bg-white', 'border-gray-200');
            } else {
                this.closest('label').classList.remove('bg-green-50', 'border-green-200');
                this.closest('label').classList.add('bg-white', 'border-gray-200');
            }
        });
    });
</script>
@endsection