<?php

namespace App\Http\Controllers;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // 2. Simpan ke Database
        Feedback::create([
            'message' => $request->message,
            'is_read' => false, // Default belum dibaca
        ]);

        // 3. Redirect kembali dengan pesan sukses
        return back()->with('success_feedback', 'Terima kasih! Kritik dan saran Anda telah terkirim.');
    }
}