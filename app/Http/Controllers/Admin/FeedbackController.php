<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        // Ambil data terbaru, paginasi 10 per halaman
        $feedbacks = Feedback::latest()->paginate(10);
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function destroy($id)
    {
        Feedback::destroy($id);
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}