<?php

namespace App\Exports;

use App\Models\Newwarroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class NewwarroomExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Newwarroom::select([
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
            // Hilangkan id, created_at, updated_at, dan supportneeded_id
        ])->orderBy('tgl', 'asc')->get(); // Urutkan berdasarkan tanggal terkecil
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Agenda',
            'UIC',
            'Peserta',
            'Pembahasan',
            'Action Plan',
            'Support Needed',
            'Info Kompetitor',
            'Jumlah Action Plan',
            'Update Action Plan',
            'Status Action Plan',
        ];
    }

    public function map($newwarroom): array
    {
        return [
            // Format tanggal menjadi "3 Juli 2025"
            $newwarroom->tgl ? Carbon::parse($newwarroom->tgl)
                ->setTimezone('Asia/Jakarta')
                ->locale('id')
                ->isoFormat('D MMMM YYYY') : '',
            $newwarroom->agenda,
            $newwarroom->uic,
            $newwarroom->peserta,
            $newwarroom->pembahasan,
            $newwarroom->action_plan,
            $newwarroom->support_needed,
            $newwarroom->info_kompetitor,
            $newwarroom->jumlah_action_plan,
            $newwarroom->update_action_plan,
            $newwarroom->status_action_plan,
        ];
    }
}