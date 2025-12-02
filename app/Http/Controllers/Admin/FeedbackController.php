<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::latest()->paginate(10);
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function destroy($id)
    {
        // Proteksi: Pengurus Gereja tidak boleh hapus
        if (Auth::user()->role == 'pengurus_gereja') {
            return back()->with('error', 'Maaf, Pengurus Gereja hanya memiliki akses melihat (View Only).');
        }

        Feedback::destroy($id);
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}