<?php

namespace App\Exports;

use App\Models\Supportneeded;
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

class SupportneededExport implements 
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
    private $supportNeeds; // Tambahkan property untuk menyimpan data

    public function collection()
    {
        // Ambil data support needed dan simpan di property
        $this->supportNeeds = Supportneeded::orderBy('start_date', 'asc')->get();

        // Hitung statistik
        $this->calculateStatistics($this->supportNeeds);

        // Buat collection yang berisi 8 baris kosong untuk header + data aktual
        $collection = collect();
        
        // Tambahkan 8 baris kosong untuk header dan statistik
        for ($i = 0; $i < 8; $i++) {
            $collection->push([]);
        }
        
        // Tambahkan data support needed
        foreach ($this->supportNeeds as $support) {
            $collection->push($support);
        }

        $this->totalRows = $collection->count();
        return $collection;
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
            return array_fill(0, 13, ''); // 13 kolom kosong
        }
        
        // Untuk data support needed (mulai baris ke-9)
        $dataRowNumber = $this->rowNumber - 7; // Nomor urut data (1, 2, 3, ...)
        $this->rowNumber++;

        // Jika row kosong atau bukan instance Supportneeded, return kosong
        if (empty($row) || !($row instanceof Supportneeded)) {
            return array_fill(0, 13, '');
        }

        return [
            $dataRowNumber, // No urut data
            $row->agenda ?? '-',
            $row->unit_or_telda ?? '-',
            $row->start_date
                ? Carbon::parse($row->start_date)
                    ->setTimezone('Asia/Jakarta')
                    ->locale('id')
                    ->isoFormat('D MMM YYYY')
                : '-',
            $row->end_date
                ? Carbon::parse($row->end_date)
                    ->setTimezone('Asia/Jakarta')
                    ->locale('id')
                    ->isoFormat('D MMM YYYY')
                : '-',
            $row->off_day ?? '-',
            $row->notes_to_follow_up ?? '-',
            $row->uic ?? '-',
            $row->progress ?? '-',
            ($row->complete ?? 0) . '%',
            $this->getStatusBadge($row->status ?? ''),
            $row->response_uic ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->totalRows; // Total rows sesuai data yang ada
        $lastColumn = 'L'; // Kolom terakhir (13 kolom = M)
        
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
            
            // Center alignment untuk semua kolom kecuali notes dan response
            "A9:A$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // No
            "B9:B$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Agenda
            "C9:C$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Unit/Telda
            "D9:D$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Start Date
            "E9:E$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // End Date
            "F9:F$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Off Day
            "H9:H$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // UIC
            "J9:I$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Progress
            "K9:J$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // % Complete
            "L9:K$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Status
            
            // Left alignment untuk notes dan response (tetap middle vertical)
            "G9:G$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]], // Notes to Follow Up
            "M9:L$lastRow" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]], // Response UIC
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 20,  // Agenda
            'C' => 15,  // Unit/Telda
            'D' => 12,  // Start Date
            'E' => 12,  // End Date
            'F' => 10,  // Off Day
            'G' => 35,  // Notes to Follow Up
            'H' => 8,   // UIC
            'I' => 12,  // Progress
            'J' => 12,  // % Complete
            'K' => 15,  // Status
            'L' => 35,  // Response UIC
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // ROW 1: JUDUL UTAMA
                $sheet->mergeCells('A1:L1');
                $sheet->setCellValue('A1', 'SUPPORT NEEDED MANAGEMENT');
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
                $sheet->setCellValue('A3', "Periode: {$currentMonth} | Total Support: {$this->statistics['total']} Items");
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
                
                // Set header tabel dengan urutan yang benar
                $headers = [
                    'NO', 
                    'AGENDA', 
                    'UNIT/TELDA', 
                    'START DATE', 
                    'END DATE', 
                    '# OFF DAY', 
                    'NOTES TO FOLLOW UP', 
                    'UIC', 
                    'PROGRESS', 
                    '% COMPLETE', 
                    'STATUS', 
                    'RESPONSE UIC'
                ];
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
        
        // Card 1: Total Support
        $sheet->mergeCells('A5:E5');
        $sheet->setCellValue('A5', 'Total');
        $sheet->getStyle('A5:E5')->applyFromArray([
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
        
        // Card 2: Close
        $sheet->mergeCells('F5:I5');
        $sheet->setCellValue('F5', 'Close');
        $sheet->getStyle('F5:I5')->applyFromArray([
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
        
        // Card 3: Actual Progress
        $sheet->mergeCells('J5:L5');
        $sheet->setCellValue('J5', 'Actual Progress');
        $sheet->getStyle('J5:L5')->applyFromArray([
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
        
        // ROW 6: Value Cards
        // Value 1: Total
        $sheet->mergeCells('A6:E6');
        $sheet->setCellValue('A6', $this->statistics['total']);
        $sheet->getStyle('A6:E6')->applyFromArray([
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
        
        // Value 2: Close
        $sheet->mergeCells('F6:I6');
        $closeText = $this->statistics['close'] . ' (' . $this->statistics['close_percentage'] . '%)';
        $sheet->setCellValue('F6', $closeText);
        $sheet->getStyle('F6:I6')->applyFromArray([
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
        
        // Value 3: Actual Progress
        $sheet->mergeCells('J6:L6');
        $sheet->setCellValue('J6', $this->statistics['actual_progress'] . '%');
        $sheet->getStyle('J6:L6')->applyFromArray([
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

    private function calculateStatistics($supportNeeds)
    {
        $total = $supportNeeds->count();
        
        // Hitung close berdasarkan progress "Done" sesuai controller
        $close = $supportNeeds->where('progress', 'Done')->count();
        
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;
        
        // Hitung rata-rata progress sesuai logic di controller
        $progressMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'On Progress' => 75,
            'Done' => 100,
        ];

        $totalProgress = 0;
        $count = 0;

        foreach ($supportNeeds as $support) {
            if (isset($progressMap[$support->progress])) {
                $totalProgress += $progressMap[$support->progress];
                $count++;
            }
        }

        $actualProgress = $count > 0 ? round($totalProgress / $count, 1) : 0;
        
        $this->statistics = [
            'total' => $total,
            'close' => $close,
            'close_percentage' => $closePercentage,
            'actual_progress' => $actualProgress,
        ];
    }

    private function getStatusBadge($status)
    {
        switch ($status) {
            case 'Action':
                return '🔵 ACTION';
            case 'Eskalasi':
                return '🔴 ESKALASI';
            case 'Support Needed':
                return '🟡 SUPPORT NEEDED';
            default:
                return $status ?? '-';
        }
    }
}