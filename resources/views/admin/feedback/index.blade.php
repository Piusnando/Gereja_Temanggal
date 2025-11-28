@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Kotak Kritik & Saran</h1>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($feedbacks->isEmpty())
        <div class="p-8 text-center text-gray-500">
            Belum ada kritik atau saran yang masuk.
        </div>
    @else
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-40">
                        Tanggal
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Isi Pesan
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($feedbacks as $item)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm align-top">
                        <p class="text-gray-900 whitespace-no-wrap font-bold">
                            {{ $item->created_at->format('d M Y') }}
                        </p>
                        <p class="text-gray-500 text-xs">
                            {{ $item->created_at->format('H:i') }} WIB
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-800 bg-gray-50 p-3 rounded border border-gray-100">
                            {{ $item->message }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center align-middle">
                        <form action="{{ route('admin.feedback.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition" title="Hapus Pesan">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="px-5 py-5 bg-white border-t">
            {{ $feedbacks->links() }}
        </div>
    @endif
</div>
@endsection