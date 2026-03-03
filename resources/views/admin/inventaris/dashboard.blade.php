@extends('layouts.admin')

@section('title', 'Dashboard Inventaris')

@section('content')
<!-- Load Chart.js & Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Logika Alpine untuk Dashboard -->
<div class="container mx-auto px-4 py-2" 
    x-data="inventoryDashboard()"
    x-init="initCharts(); fetchData()">

    <!-- HEADER & FILTERS -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex flex-col lg:flex-row justify-between lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Inventaris</h1>
                <p class="text-sm text-gray-500">Analisis aset dan kondisi barang gereja secara real-time.</p>
            </div>
            
            <!-- FORM FILTER -->
            <!-- @change.debounce memanggil fetchData setiap filter diubah -->
            <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto" @change.debounce.500ms="fetchData()">
                <!-- Filter Lokasi -->
                <select x-model="filters.location_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">-- Semua Lokasi --</option>
                    @foreach($allLocations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                    @endforeach
                </select>

                <!-- Filter Kategori -->
                <select x-model="filters.category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <!-- Filter Kondisi -->
                <select x-model="filters.condition" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">-- Semua Kondisi --</option>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Sedang">Rusak Sedang</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 1. KPI CARDS (Dibuat Dinamis dengan x-text) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500"><p class="text-xs text-gray-400 font-bold uppercase">Total Item</p><h2 class="text-3xl font-black text-gray-800 mt-1" x-text="kpi.totalItems">0</h2></div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500"><p class="text-xs text-gray-400 font-bold uppercase">Kondisi Baik</p><h2 class="text-3xl font-black text-gray-800 mt-1" x-text="kpi.totalBaik">0</h2></div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500"><p class="text-xs text-gray-400 font-bold uppercase">Perlu Perbaikan</p><h2 class="text-3xl font-black text-gray-800 mt-1" x-text="kpi.totalRusak">0</h2></div>
    </div>

    <!-- 2. GRAFIK (Semua dalam 1 baris) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Kondisi (Pie) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-80"><canvas id="conditionChart"></canvas></div>
        <!-- Chart Lokasi (Bar) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-80"><canvas id="locationChart"></canvas></div>
        <!-- Chart Kategori (Bar) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-80"><canvas id="categoryChart"></canvas></div>
    </div>
</div>

<script>
    function inventoryDashboard() {
        return {
            // -- DATA & STATE --
            charts: {}, // Untuk menyimpan object chart
            filters: { // Untuk menyimpan nilai filter
                location_id: '',
                category_id: '',
                condition: ''
            },
            kpi: { // Untuk menyimpan angka KPI
                totalItems: 0,
                totalBaik: 0,
                totalRusak: 0
            },

            // -- FUNGSI-FUNGSI --
            
            // 1. Inisialisasi semua Chart dengan data kosong
            initCharts() {
                this.charts.condition = this.createConditionChart([]);
                this.charts.location = this.createLocationChart([], []);
                this.charts.category = this.createCategoryChart([], []);
            },

            // 2. Fetch data dari Controller
            async fetchData() {
                // Buat URL dengan parameter filter
                const url = new URL('{{ route("admin.inventaris.chart_data") }}');
                url.search = new URLSearchParams(this.filters).toString();
                
                try {
                    const response = await fetch(url);
                    const data = await response.json();

                    // Update KPI
                    this.kpi.totalItems = data.totalItems;
                    this.kpi.totalBaik = data.totalBaik;
                    this.kpi.totalRusak = data.totalRusak;

                    // Update Chart
                    this.charts.condition.data.datasets[0].data = data.conditionData;
                    this.charts.condition.update();

                    this.charts.location.data.labels = data.locationData.labels;
                    this.charts.location.data.datasets[0].data = data.locationData.values;
                    this.charts.location.update();

                    this.charts.category.data.labels = data.categoryData.labels;
                    this.charts.category.data.datasets[0].data = data.categoryData.values;
                    this.charts.category.update();

                } catch (error) {
                    console.error('Error fetching chart data:', error);
                }
            },

            // 3. Template untuk setiap Chart (Bisa dikustomisasi)
            createConditionChart(data) {
                return new Chart(document.getElementById('conditionChart'), {
                    type: 'doughnut',
                    data: { labels: ['Baik', 'Rusak Sedang', 'Rusak Berat'], datasets: [{ data: data, backgroundColor: ['#10B981', '#F59E0B', '#EF4444'], borderWidth: 0 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } }, title: { display: true, text: 'Proporsi Kondisi Barang' } } }
                });
            },
            createLocationChart(labels, values) {
                return new Chart(document.getElementById('locationChart'), {
                    type: 'bar',
                    data: { labels: labels, datasets: [{ label: 'Jumlah', data: values, backgroundColor: '#8B5CF6', borderRadius: 4 }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, title: { display: true, text: 'Sebaran Aset per Lokasi' } }, scales: { y: { beginAtZero: true } } }
                });
            },
            createCategoryChart(labels, values) {
                return new Chart(document.getElementById('categoryChart'), {
                    type: 'bar',
                    data: { labels: labels, datasets: [{ label: 'Jumlah', data: values, backgroundColor: '#3B82F6', borderRadius: 4 }] },
                    options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false }, title: { display: true, text: 'Top 5 Kategori Barang' } }, scales: { x: { beginAtZero: true } } }
                });
            }
        }
    }
</script>
@endsection