<?php

namespace App\Exports;

use App\Models\Supportneeded;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class SupportneededExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    public function collection()
    {
        // Urutkan berdasarkan tanggal terkecil (ASC)
        // Ganti 'created_at' dengan nama kolom tanggal yang sebenarnya
        return Supportneeded::orderBy('start_date', 'asc')->get();
    }

    public function headings(): array
    {
        // Ambil semua kolom kecuali id, created_at, dan updated_at
        $columns = \Schema::getColumnListing((new Supportneeded)->getTable());
        $filteredColumns = array_filter($columns, function($column) {
            return !in_array($column, ['id', 'created_at', 'updated_at']);
        });
        
        // Tambahkan kolom "No" di awal
        return array_merge(['No'], $filteredColumns);
    }

    public function map($supportneeded): array
    {
        $this->rowNumber++;
        
        $columns = \Schema::getColumnListing((new Supportneeded)->getTable());
        $mappedData = [$this->rowNumber]; // Mulai dengan nomor urut

        foreach ($columns as $column) {
            // Skip kolom id, created_at, dan updated_at
            if (in_array($column, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $value = $supportneeded->$column;
            
            // Handle date columns - format tanggal menjadi "3 Juli 2025"
            if ($value instanceof \Carbon\Carbon || $value instanceof \DateTime) {
                $mappedData[] = Carbon::parse($value)
                    ->setTimezone('Asia/Jakarta')
                    ->locale('id') // Set locale ke Indonesia
                    ->isoFormat('D MMMM YYYY'); // Format: 3 Juli 2025
            } elseif (is_string($value) && $this->isDateString($value)) {
                try {
                    $mappedData[] = Carbon::parse($value)
                        ->setTimezone('Asia/Jakarta')
                        ->locale('id')
                        ->isoFormat('D MMMM YYYY');
                } catch (\Exception $e) {
                    $mappedData[] = $value;
                }
            } else {
                $mappedData[] = $value;
            }
        }

        return $mappedData;
    }

    private function isDateString($value): bool
    {
        // Cek apakah string adalah format tanggal
        if (!is_string($value)) return false;
        return (bool) preg_match('/\d{4}-\d{2}-\d{2}/', $value) || 
               (bool) preg_match('/\d{2}\/\d{2}\/\d{4}/', $value) ||
               (bool) preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $value);
    }
}