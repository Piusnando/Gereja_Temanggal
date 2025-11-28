<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'message' => $request->message
        ]);

        return back()->with('success_feedback', 'Terima kasih! Kritik dan saran Anda telah terkirim.');
    }
}