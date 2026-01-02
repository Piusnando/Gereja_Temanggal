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
}