<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // 3. PENTING: Gunakan back() agar kembali ke halaman asal (Footer)
        // Jangan gunakan redirect('/feedback') atau view()
        return back()->with('success_feedback', 'Terima kasih! Kritik dan saran Anda telah terkirim.');
    }
}