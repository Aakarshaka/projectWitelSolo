<?php

namespace App\Exports;

use App\Models\Newwarroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewwarroomExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Newwarroom::select([
            'id',
            'tgl',
            'agenda',
            'uic',
            'peserta',
            'pembahasan',
            'action_plan',
            'support_needed',
            'info_kompetitor',
            'jumlah_action_plan',
            'update_action_plan',
            'status_action_plan',
            // Jangan masukkan supportneeded_id
        ])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Agenda',
            'UIC',
            'peserta',
            'pembahasan',
            'action_plan',
            'support_needed',
            'info_kompetitor',
            'jumlah_action_plan',
            'update_action_plan',
            'status_action_plan',
        ];
    }
}
