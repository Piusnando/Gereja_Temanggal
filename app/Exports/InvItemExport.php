<?php

namespace App\Exports;

use App\Models\InvItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // <-- Diperbaiki
use Maatwebsite\Excel\Concerns\WithStyles;     // <-- Diganti untuk Auto Filter
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvItemExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    // Menangkap request filter dari Controller
    public function __construct($request)
    {
        $this->request = $request;
    }

    // 1. Logika Query (SAMA PERSIS dengan filter di Controller)
    public function query()
    {
        $query = InvItem::query()->with(['location', 'category']);

        if (!empty($this->request['location_id'])) {
            $query->where('inv_location_id', $this->request['location_id']);
        }
        if (!empty($this->request['category_id'])) {
            $query->where('inv_category_id', $this->request['category_id']);
        }
        if (!empty($this->request['search'])) {
            $search = $this->request['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('item_code');
    }

    // 2. Judul Kolom (Header) di Excel
    public function headings(): array
    {
        return[
            'Kode Barang',
            'Nama Barang',
            'Lokasi',
            'Kategori',
            'Nomor Seri',
            'Kondisi',
            'Deskripsi / Keterangan',
        ];
    }

    // 3. Mapping Data ke Kolom Excel
    public function map($item): array
    {
        return[
            $item->item_code,
            $item->name,
            $item->location->name ?? '-',
            $item->category->name ?? '-',
            $item->serial_number,
            $item->condition,
            $item->description ?? '-',
        ];
    }

    // 4. Styling Excel (Termasuk Auto Filter & Bold Header)
    public function styles(Worksheet $sheet)
    {
        // Pasang Auto-Filter dari kolom A sampai G di baris 1
        $sheet->setAutoFilter('A1:G1');

        // Bikin text header (Baris 1) jadi Bold / Tebal
        return [
            1 => ['font' =>['bold' => true]],
        ];
    }
}