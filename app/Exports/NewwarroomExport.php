<?php

namespace App\Exports;

use App\Models\Newwarroom;
use App\Models\ActionPlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class NewwarroomExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithColumnWidths,
    WithEvents,
    ShouldAutoSize
{
    private $rowNumber = 0;
    private $totalRows = 0;
    private $statistics = [];
    private $warrooms;

    public function collection()
    {
        // Kembalikan data kosong untuk baris 1-8, karena akan diisi manual di registerEvents
        $emptyRows = collect([
            [], [], [], [], [], [], [], [] // 8 baris kosong untuk header dan statistik
        ]);
        
        // Ambil data warroom dengan relasi yang benar
        $this->warrooms = Newwarroom::with(['actionPlans' => function($query) {
                $query->orderBy('plan_number', 'asc');
            }])
            ->orderByRaw('tgl IS NULL') // Null dates last
            ->orderBy('tgl', 'asc')
            ->get();

        // Hitung statistik dari database
        $this->calculateStatisticsFromDatabase();

        // Proses data warroom
        foreach ($this->warrooms as $warroom) {
            // Gabungkan semua action plans dalam 1 baris
            $actionPlansText = '-';
            $updatePlansText = '-';
            $statusPlansText = '-';
            
            if ($warroom->actionPlans && $warroom->actionPlans->isNotEmpty()) {
                // Format action plans dengan penomoran
                $actionPlansArray = [];
                $updatePlansArray = [];
                $statusPlansArray = [];
                
                foreach ($warroom->actionPlans as $index => $plan) {
                    $actionPlansArray[] = ($index + 1) . '. ' . ($plan->action_plan ?? '');
                    
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
            
            $emptyRows->push([
                'no' => '', // akan diisi di map()
                'tgl' => $warroom->tgl,
                'agenda' => $warroom->agenda,
                'uic' => $warroom->uic,
                'peserta' => $warroom->peserta,
                'pembahasan' => $warroom->pembahasan,
                'action_plan' => $actionPlansText,
                'support_needed' => $warroom->support_needed ?? '-',
                'info_kompetitor' => $warroom->info_kompetitor ?? '-',
                'jumlah_action_plan' => $warroom->actionPlans ? $warroom->actionPlans->count() : 0,
                'update_action_plan' => $updatePlansText,
                'status_action_plan' => $statusPlansText,
            ]);
        }

        $this->totalRows = $emptyRows->count();
        return $emptyRows;
    }

    public function headings(): array
    {
        // Return empty karena header akan diatur manual di registerEvents()
        return [];
    }

    public function map($row): array
    {
        // Untuk 8 baris pertama (header & statistik), return kosong
        if ($this->rowNumber < 8) {
            $this->rowNumber++;
            return array_fill(0, 12, ''); // 12 kolom
        }
        
        // Untuk data warroom (mulai baris ke-9)
        $dataRowNumber = $this->rowNumber - 7; // Nomor urut data (1, 2, 3, ...)
        $this->rowNumber++;

        // Jika row kosong (baris header/statistik), skip
        if (empty($row) || !isset($row['tgl'])) {
            return array_fill(0, 12, '');
        }

        return [
            $dataRowNumber, // No urut data
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
            $row['support_needed'] ?? '-',
            $row['info_kompetitor'] ?? '-',
            $row['jumlah_action_plan'] ?? 0,
            $row['update_action_plan'] ?? '-',
            $row['status_action_plan'] ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->totalRows; // Total rows sesuai data yang ada
        $lastColumn = 'L'; // Kolom terakhir (sampai Status Action Plan)
        
        return [
            // Style untuk header kolom (ROW 8)
            'A8:L8' => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
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
            
            // Style untuk semua data (mulai ROW 9)
            "A9:L$lastRow" => [
                'font' => [
                    'size' => 10,
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            
            // Center alignment untuk kolom tertentu
            "A9:A$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // No
            "B9:B$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Tanggal
            "C9:C$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Agenda
            "D9:D$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // UIC
            "E9:E$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Peserta
            "J9:J$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Jumlah Action Plan
            "L9:L$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Status
            
            // Left alignment untuk kolom text panjang
            "F9:F$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]], // Pembahasan
            "G9:G$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]], // Action Plan
            "H9:H$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]], // Support Needed
            "I9:I$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]], // Info Kompetitor
            "K9:K$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]], // Update Action Plan
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 12,  // Tanggal
            'C' => 20,  // Agenda
            'D' => 10,  // UIC
            'E' => 15,  // Peserta
            'F' => 30,  // Pembahasan
            'G' => 35,  // Action Plan
            'H' => 25,  // Support Needed
            'I' => 25,  // Info Kompetitor
            'J' => 8,   // Jumlah Action Plan
            'K' => 35,  // Update Action Plan
            'L' => 20,  // Status Action Plan
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // ROW 1: JUDUL UTAMA
                $sheet->mergeCells('A1:L1');
                $sheet->setCellValue('A1', 'FORUM WAR ROOM ACTIVITY');
                $sheet->getRowDimension(1)->setRowHeight(40);
                $sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '8B1538'], // Warna merah
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
                ]);
                
                // ROW 2: KOSONG (spacing)
                $sheet->getRowDimension(2)->setRowHeight(15);
                
                // ROW 3: INFORMASI PERIODE
                $currentMonth = Carbon::now()->locale('id')->isoFormat('MMMM YYYY');
                $sheet->mergeCells('A3:L3');
                $sheet->setCellValue('A3', "Periode: {$currentMonth} | Total Warroom: {$this->statistics['total_agenda']} Agenda");
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getStyle('A3:L3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['argb' => '495057'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'F8F9FA'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'DEE2E6'],
                        ],
                    ],
                ]);
                
                // ROW 4: KOSONG (spacing)
                $sheet->getRowDimension(4)->setRowHeight(10);
                
                // ROW 5-6: STATISTIK CARDS
                $this->addStatisticCards($sheet);
                
                // ROW 7: KOSONG (spacing)
                $sheet->getRowDimension(7)->setRowHeight(15);
                
                // ROW 8: HEADER TABEL
                $sheet->getRowDimension(8)->setRowHeight(35);
                
                // Set header tabel
                $headers = ['No', 'Tanggal', 'Agenda', 'UIC', 'Peserta', 'Pembahasan', 'Action Plan', 'Support Needed', 'Info Kompetitor', 'Jumlah Action Plan', 'Update Action Plan', 'Status Action Plan'];
                foreach ($headers as $index => $header) {
                    $column = chr(65 + $index); // A, B, C, dst.
                    $sheet->setCellValue($column . '8', $header);
                }
            },
        ];
    }

    private function addStatisticCards($sheet)
    {
        // ROW 5-6: STATISTIK CARDS
        $sheet->getRowDimension(5)->setRowHeight(30);
        $sheet->getRowDimension(6)->setRowHeight(35);
        
        // Card 1: Total Eskalasi
        $sheet->mergeCells('A5:D5');
        $sheet->setCellValue('A5', 'Total Eskalasi');
        $sheet->getStyle('A5:D5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '8B1538'], // Merah
            ],
            'font' => [
                'color' => ['argb' => 'FFFFFF'], 
                'bold' => true,
                'size' => 12,
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
        ]);
        
        // Card 2: Jumlah Action Plan
        $sheet->mergeCells('E5:H5');
        $sheet->setCellValue('E5', 'Jumlah Action Plan');
        $sheet->getStyle('E5:H5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '6F42C1'], // Ungu
            ],
            'font' => [
                'color' => ['argb' => 'FFFFFF'], 
                'bold' => true,
                'size' => 12,
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
        ]);
        
        // Card 3: Jumlah Agenda
        $sheet->mergeCells('I5:L5');
        $sheet->setCellValue('I5', 'Jumlah Agenda');
        $sheet->getStyle('I5:L5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '20C997'], // Hijau
            ],
            'font' => [
                'color' => ['argb' => 'FFFFFF'], 
                'bold' => true,
                'size' => 12,
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
        ]);
        
        // ROW 6: Value Cards dengan nilai dari database
        // Value 1: Total Eskalasi
        $sheet->mergeCells('A6:D6');
        $sheet->setCellValue('A6', $this->statistics['total_eskalasi']);
        $sheet->getStyle('A6:D6')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFFF'],
            ],
            'font' => [
                'color' => ['argb' => '8B1538'], 
                'bold' => true,
                'size' => 20,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '8B1538'],
                ],
            ],
        ]);
        
        // Value 2: Total Action Plans
        $sheet->mergeCells('E6:H6');
        $sheet->setCellValue('E6', $this->statistics['total_action_plans']);
        $sheet->getStyle('E6:H6')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFFF'],
            ],
            'font' => [
                'color' => ['argb' => '6F42C1'], 
                'bold' => true,
                'size' => 20,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '6F42C1'],
                ],
            ],
        ]);
        
        // Value 3: Total Agenda
        $sheet->mergeCells('I6:L6');
        $sheet->setCellValue('I6', $this->statistics['total_agenda']);
        $sheet->getStyle('I6:L6')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFFF'],
            ],
            'font' => [
                'color' => ['argb' => '20C997'], 
                'bold' => true,
                'size' => 20,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '20C997'],
                ],
            ],
        ]);
    }

    /**
     * Hitung statistik langsung dari database
     */
    private function calculateStatisticsFromDatabase()
    {
        // Total agenda
        $totalAgenda = $this->warrooms->count();
        
        // Total action plans dan eskalasi
        $totalActionPlans = 0;
        $totalEskalasi = 0;
        
        foreach ($this->warrooms as $warroom) {
            if ($warroom->actionPlans) {
                $totalActionPlans += $warroom->actionPlans->count();
                
                // Hitung eskalasi per warroom
                foreach ($warroom->actionPlans as $actionPlan) {
                    if (strtolower($actionPlan->status_action_plan) === 'eskalasi') {
                        $totalEskalasi++;
                    }
                }
            }
        }
        
        $this->statistics = [
            'total_eskalasi' => $totalEskalasi,
            'total_action_plans' => $totalActionPlans,
            'total_agenda' => $totalAgenda,
        ];
    }

    private function getStatusBadge($status)
    {
        switch (strtolower($status)) {
            case 'completed':
            case 'selesai':
            case 'done':
                return '✅ SELESAI';
            case 'in_progress':
            case 'progress':
            case 'on progress':
            case 'open':
                return '🔄 ON PROGRESS';
            case 'pending':
            case 'need discuss':
                return '⏳ PENDING';
            case 'cancelled':
            case 'dibatalkan':
                return '❌ DIBATALKAN';
            case 'eskalasi':
                return '🚨 ESKALASI';
            default:
                return $status ?? '-';
        }
    }
}