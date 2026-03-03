<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvItem;
use App\Models\InvCategory;
use App\Models\InvLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvDashboardController extends Controller
{
    /**
     * Tampilan awal Dashboard
     */
    public function index()
    {
        // Ambil data untuk Form Filter
        $allLocations = InvLocation::orderBy('name')->get();
        $allCategories = InvCategory::orderBy('name')->get();
        
        return view('admin.inventaris.dashboard', compact('allLocations', 'allCategories'));
    }

    /**
     * Endpoint API internal untuk mengambil data chart secara dinamis
     */
    public function getDataForCharts(Request $request)
    {
        // --- BUAT QUERY DASAR ---
        $query = InvItem::query();

        // --- TERAPKAN FILTER JIKA ADA ---
        if ($request->filled('location_id')) {
            $query->where('inv_location_id', $request->location_id);
        }
        if ($request->filled('category_id')) {
            $query->where('inv_category_id', $request->category_id);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // --- HITUNG DATA ---
        
        // 1. KPI
        $totalItems = $query->count();
        $totalRusak = (clone $query)->whereIn('condition', ['Rusak Sedang', 'Rusak Berat'])->count();
        $totalBaik = (clone $query)->where('condition', 'Baik')->count();

        // 2. Data Komposisi Kondisi (Pie Chart)
        $conditionStats = (clone $query)->select('condition', DB::raw('count(*) as total'))
                            ->groupBy('condition')
                            ->pluck('total', 'condition')->toArray();
        $dataKondisi = [
            $conditionStats['Baik'] ?? 0,
            $conditionStats['Rusak Sedang'] ?? 0,
            $conditionStats['Rusak Berat'] ?? 0
        ];

        // 3. Data Perbandingan Kategori (Bar Chart Horizontal)
        $categoryStats = (clone $query)->join('inv_categories', 'inv_items.inv_category_id', '=', 'inv_categories.id')
            ->select('inv_categories.name', DB::raw('count(*) as total'))
            ->groupBy('inv_categories.name')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        
        // 4. Data Sebaran Lokasi (Bar Chart Vertical)
        $locationStats = (clone $query)->join('inv_locations', 'inv_items.inv_location_id', '=', 'inv_locations.id')
            ->select('inv_locations.name', DB::raw('count(*) as total'))
            ->groupBy('inv_locations.name')
            ->orderByDesc('total')
            ->take(8)
            ->get();
        
        // --- KEMBALIKAN DATA SEBAGAI JSON ---
        return response()->json([
            'totalItems' => $totalItems,
            'totalBaik' => $totalBaik,
            'totalRusak' => $totalRusak,
            'conditionData' => $dataKondisi,
            'categoryData' => [
                'labels' => $categoryStats->pluck('name'),
                'values' => $categoryStats->pluck('total'),
            ],
            'locationData' => [
                'labels' => $locationStats->pluck('name'),
                'values' => $locationStats->pluck('total'),
            ],
        ]);
    }
}