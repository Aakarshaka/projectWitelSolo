<?php

namespace App\Exports;

use App\Models\Supportneeded;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupportneededExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Supportneeded::all();
    }

    public function headings(): array
    {
        // Ambil nama kolom dari tabel supportneeded
        return \Schema::getColumnListing((new Supportneeded)->getTable());
    }
}
