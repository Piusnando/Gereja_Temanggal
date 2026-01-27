<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        $logo = SiteSetting::where('key', 'site_logo')->first();
        return view('admin.settings.index', compact('banners', 'logo'));
    }

    public function updateLogo(Request $request)
    {
        $request->validate(['logo' => 'required|image|max:2048']);

        $path = $request->file('logo')->store('uploads/settings', 'public');

        // Simpan atau update ke database
        SiteSetting::updateOrCreate(
            ['key' => 'site_logo'],
            ['value' => $path]
        );

        return back()->with('success', 'Logo berhasil diperbarui!');
    }

    public function storeBanner(Request $request)
    {
        $request->validate(['image' => 'required|image|max:2048']); // Max 4MB

        $path = $request->file('image')->store('uploads/banners', 'public');

        Banner::create([
            'image_path' => $path,
            'title' => $request->title,
            'is_active' => true
        ]);

        return back()->with('success', 'Banner berhasil ditambahkan!');
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        $banner->update([
            'title' => $request->title,
            'order' => $request->order,
            'is_active' => $request->is_active
        ]);

        return back()->with('success', 'Data banner berhasil diperbarui!');
    }

    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        
        // Hapus file fisik
        if(Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }
        
        $banner->delete();
        return back()->with('success', 'Banner dihapus!');
    }

    public function updateAllBanners(Request $request)
    {
        $request->validate([
            'banners' => 'required|array',
            'banners.*.title' => 'nullable|string|max:255',
            'banners.*.order' => 'required|integer',
            'banners.*.is_active' => 'required|boolean',
        ]);

        $banners = $request->input('banners');

        foreach ($banners as $id => $data) {
            // Update tiap baris berdasarkan ID
            Banner::where('id', $id)->update([
                'title' => $data['title'],
                'order' => $data['order'],
                'is_active' => $data['is_active'],
            ]);
        }

        return back()->with('success', 'Semua perubahan berhasil disimpan!');
    }
}