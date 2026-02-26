<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity; // Tetap pakai model Activity
use App\Models\Lingkungan;
use Illuminate\Http\Request;

class YouthEventController extends Controller
{
    public function index()
    {
        // Hanya ambil kegiatan dengan tipe 'youth'
        $events = Activity::where('type', 'youth')
                        ->latest('start_time')
                        ->paginate(10);
        
        return view('admin.youth.events.index', compact('events'));
    }

    public function create()
    {
        $lingkungans = Lingkungan::orderBy('name')->get();
        return view('admin.youth.events.create', compact('lingkungans'));
    }

    public function store(Request $request)
    {
        $request->validate([ /* ... validasi tanpa 'type' ... */ ]);
        
        $data = $request->all();
        $data['type'] = 'youth'; // Paksa tipe menjadi 'youth'

        // ... (Logika simpan data lainnya tetap sama) ...
        
        Activity::create($data);

        return redirect()->route('admin.youth.events.index')->with('success', 'Kegiatan Bina Iman berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        // Ambil data, pastikan tipenya 'youth' untuk keamanan
        $event = Activity::where('id', $id)->where('type', 'youth')->firstOrFail();
        $lingkungans = Lingkungan::orderBy('name')->get();
        
        return view('admin.youth.events.edit', compact('event', 'lingkungans'));
    }

    public function update(Request $request, $id)
    {
        $event = Activity::where('id', $id)->where('type', 'youth')->firstOrFail();
        
        $request->validate([ /* ... validasi ... */ ]);

        $data = $request->all();
        // ... (Logika update lainnya) ...
        
        $event->update($data);

        return redirect()->route('admin.youth.events.index')->with('success', 'Kegiatan diperbarui.');
    }
    
    public function destroy($id)
    {
        $event = Activity::where('id', $id)->where('type', 'youth')->firstOrFail();
        $event->delete();
        
        return back()->with('success', 'Kegiatan dihapus.');
    }
}