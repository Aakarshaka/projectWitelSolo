<?php

namespace App\Exports;

use App\Models\Newwarroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class NewwarroomExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithColumnWidths,
    ShouldAutoSize
{
    private $rowNumber = 0;
    private $totalRows = 0;

    public function collection()
    {
        $rows = new Collection();

        // Ambil semua warroom beserta action plans-nya
        $warrooms = Newwarroom::with('actionPlans')
            ->orderBy('tgl', 'asc')
            ->get();

        foreach ($warrooms as $warroom) {
            // Gabungkan semua action plans dalam 1 baris
            $actionPlansText = '-';
            $updatePlansText = '-';
            $statusPlansText = '-';
            
            if ($warroom->actionPlans->isNotEmpty()) {
                // Format action plans dengan penomoran
                $actionPlansArray = [];
                $updatePlansArray = [];
                $statusPlansArray = [];
                
                foreach ($warroom->actionPlans as $index => $plan) {
                    $actionPlansArray[] = ($index + 1) . '. ' . $plan->action_plan;
                    
                    $updatePlansArray[] = ($index + 1) . '. ' . 
                        ($plan->update_action_plan ?: 'Belum ada update');
                    
                    $statusPlansArray[] = ($index + 1) . '. ' . 
                        $this->getStatusBadge($plan->status_action_plan);
                }
                
                // Gabungkan dengan line break
                $actionPlansText = implode("\n", $actionPlansArray);
                $updatePlansText = implode("\n", $updatePlansArray);
                $statusPlansText = implode("\n", $statusPlansArray);
            }
            
            $rows->push([
                'tgl' => $warroom->tgl,
                'agenda' => $warroom->agenda,
                'uic' => $warroom->uic,
                'peserta' => $warroom->peserta,
                'pembahasan' => $warroom->pembahasan,
                'action_plan' => $actionPlansText,
                'update_action_plan' => $updatePlansText,
                'status_action_plan' => $statusPlansText,
                'support_needed' => $warroom->support_needed ?? '-',
                'info_kompetitor' => $warroom->info_kompetitor ?? '-',
                'jumlah_action_plan' => $warroom->jumlah_action_plan ?? 0,
            ]);
        }

        $this->totalRows = $rows->count();
        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Agenda',
            'UIC',
            'Peserta',
            'Pembahasan',
            'Action Plan',
            'Update Action Plan',
            'Status Action Plan',
            'Support Needed',
            'Info Kompetitor',
            'Jumlah Action Plan',
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $row['tgl']
                ? Carbon::parse($row['tgl'])
                    ->setTimezone('Asia/Jakarta')
                    ->locale('id')
                    ->isoFormat('D MMMM YYYY')
                : '-',
            $row['agenda'] ?? '-',
            $row['uic'] ?? '-',
            $row['peserta'] ?? '-',
            $row['pembahasan'] ?? '-',
            $row['action_plan'] ?? '-',
            $row['update_action_plan'] ?? '-',
            $row['status_action_plan'] ?? '-',
            $row['support_needed'] ?? '-',
            $row['info_kompetitor'] ?? '-',
            $row['jumlah_action_plan'] ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->totalRows + 1; // +1 for header
        $lastColumn = 'L'; // Kolom terakhir yang berisi data (Jumlah Action Plan)
        
        // Set row height untuk header
        $sheet->getRowDimension(1)->setRowHeight(25);
        
        return [
            // Style untuk header
            "A1:{$lastColumn}1" => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            
            // Style untuk semua data (hanya sampai kolom L)
            "A2:{$lastColumn}{$lastRow}" => [
                'font' => [
                    'size' => 10,
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            
            // Style khusus untuk kolom No (center alignment)
            "A2:A$lastRow" => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            
            // Style khusus untuk kolom Tanggal (center alignment)
            "B2:B$lastRow" => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            
            // Style khusus untuk kolom Status (center alignment)
            "I2:I$lastRow" => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'bold' => true,
                ],
            ],
            
            // Style khusus untuk kolom Jumlah Action Plan (center alignment)
            "L2:L$lastRow" => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Tanggal
            'C' => 25,  // Agenda
            'D' => 15,  // UIC
            'E' => 20,  // Peserta
            'F' => 35,  // Pembahasan
            'G' => 45,  // Action Plan (lebih lebar untuk multiple plans)
            'H' => 45,  // Update Action Plan (lebih lebar untuk multiple plans)
            'I' => 20,  // Status Action Plan (lebih lebar untuk multiple status)
            'J' => 20,  // Support Needed
            'K' => 20,  // Info Kompetitor
            'L' => 8,   // Jumlah Action Plan
        ];
    }

    private function getStatusBadge($status)
    {
        switch (strtolower($status)) {
            case 'completed':
            case 'selesai':
                return '✅ SELESAI';
            case 'in_progress':
            case 'progress':
            case 'on progress':
                return '🔄 ON PROGRESS';
            case 'pending':
                return '⏳ PENDING';
            case 'cancelled':
            case 'dibatalkan':
                return '❌ DIBATALKAN';
            default:
                return $status ?? '-';
        }
    }
}