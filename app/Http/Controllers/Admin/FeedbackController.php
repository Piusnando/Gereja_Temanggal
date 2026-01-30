<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request; // Pastikan ini ada

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        // Mulai Query
        $query = Feedback::query();

        // Logika Search: Jika ada input 'search'
        if ($request->filled('search')) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }

        // Ambil data (terbaru), paginasi 10
        // appends() berguna agar saat klik halaman 2, hasil pencarian tidak hilang
        $feedbacks = $query->latest()
                           ->paginate(10)
                           ->appends(['search' => $request->search]);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function destroy($id)
    {
        Feedback::destroy($id);
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}