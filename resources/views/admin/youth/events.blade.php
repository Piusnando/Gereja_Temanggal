@extends('layouts.admin')

@section('content')
<!-- Panggil Library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div x-data="{ modalOpen: false }">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal & Absensi {{ $dbCategory }}</h1>
            <p class="text-sm text-gray-500 mt-1">Atur jadwal kegiatan, input absensi, dan pantau tren kehadiran.</p>
        </div>
        <button @click="modalOpen = true" class="w-full md:w-auto bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-5 rounded-lg shadow-md transition flex items-center justify-center transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Kegiatan Baru
        </button>
    </div>

    @if(session('success')) 
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 shadow-sm flex items-center border border-green-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div> 
    @endif

    <!-- ============================================== -->
    <!-- GRAFIK TREN KEHADIRAN (BARU)                   -->
    <!-- ============================================== -->
    @if(count($chartData) > 0)
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-1 flex items-center">
            <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
            Grafik Tren Kehadiran
        </h3>
        <p class="text-xs text-gray-400 mb-4">Pantauan berdasarkan 10 kegiatan terakhir.</p>
        
        <div class="w-full h-64">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>
    @endif

    <!-- ============================================== -->
    <!-- KOTAK DAFTAR JADWAL KEGIATAN                   -->
    <!-- ============================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 flex flex-col hover:border-orange-300 hover:shadow-xl transition duration-300 group">
            
            <div class="mb-4">
                <span class="inline-block bg-orange-100 text-orange-800 text-xs font-bold px-3 py-1 rounded-full border border-orange-200 mb-2">
                    {{ $event->event_date->translatedFormat('d M Y, H:i') }}
                </span>
                <h3 class="text-lg font-bold text-gray-800 group-hover:text-orange-600 transition leading-tight">{{ $event->title }}</h3>
                <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $event->description ?? 'Tidak ada deskripsi tambahan.' }}</p>
            </div>
            
            <!-- Tambahan: Menampilkan Angka Jumlah Hadir di Card -->
            <div class="mb-5 mt-auto pt-4 border-t border-gray-50">
                <span class="text-sm font-bold {{ $event->attendances_count > 0 ? 'text-green-600' : 'text-gray-400' }} flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $event->attendances_count }} Orang Hadir
                </span>
            </div>
            
            <a href="{{ route('admin.youth.attendance', ['category' => $categoryUrl, 'id' => $event->id]) }}" class="w-full block text-center bg-blue-50 text-blue-600 font-bold py-2.5 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm border border-blue-100 hover:border-transparent">
                Buka Lembar Absensi &rarr;
            </a>
        </div>
        @empty
        <div class="col-span-1 md:col-span-3 text-center py-16 bg-white rounded-2xl text-gray-400 border border-dashed border-gray-300">
            <div class="inline-block p-4 rounded-full bg-gray-50 mb-3">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <p class="text-gray-500 font-medium">Belum ada jadwal kegiatan untuk {{ $dbCategory }}.</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-8">
        {{ $events->links() }}
    </div>

    <!-- ============================================== -->
    <!-- MODAL TAMBAH KEGIATAN                          -->
    <!-- ============================================== -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-cloak>
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="modalOpen = false" x-show="modalOpen" x-transition.opacity></div>
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative z-50 overflow-hidden transform transition-all"
             x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="bg-orange-500 p-5 text-white flex justify-between items-center">
                <h3 class="text-xl font-bold">Buat Jadwal Baru</h3>
                <button @click="modalOpen = false" class="hover:text-red-200"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <form action="{{ route('admin.youth.events.store', $categoryUrl) }}" method="POST" class="p-6 space-y-4 bg-gray-50">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Kegiatan</label>
                    <input type="text" name="title" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 outline-none shadow-sm bg-white" placeholder="Contoh: Rapat Mingguan" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal & Waktu</label>
                    <input type="datetime-local" name="event_date" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 outline-none shadow-sm bg-white" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan Singkat (Opsional)</label>
                    <input type="text" name="description" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-orange-500 outline-none shadow-sm bg-white" placeholder="Tema atau info lokasi">
                </div>
                
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" @click="modalOpen = false" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-lg font-bold transition shadow-sm">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-bold transition shadow-md">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- SCRIPT CHART.JS (Render Grafik)                -->
<!-- ============================================== -->
@if(count($chartData) > 0)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!}, // Tanggal Kegiatan
                datasets:[{
                    label: 'Jumlah Kehadiran (Orang)',
                    data: {!! json_encode($chartData) !!}, // Angka Kehadiran
                    borderColor: '#f97316', // Warna Garis (Orange Tailwind)
                    backgroundColor: 'rgba(249, 115, 22, 0.1)', // Warna Latar Transparan
                    borderWidth: 3,
                    pointBackgroundColor: '#ea580c',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Membuat garis melengkung (smooth)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9ca3af' }, // Karena hitung orang, tidak ada koma
                        grid: { borderDash: [4, 4], color: '#f3f4f6' }
                    },
                    x: {
                        ticks: { color: '#9ca3af', font: { size: 10 } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection