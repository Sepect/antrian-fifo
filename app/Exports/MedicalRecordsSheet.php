<?php

namespace App\Exports;

use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MedicalRecordsSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private int $row = 0;

    public function title(): string
    {
        return 'Data Rekam Medis';
    }

    public function query(): Builder
    {
        return MedicalRecord::query()
            ->with(['patient', 'polyclinic'])
            ->orderByDesc('visit_date')
            ->orderByDesc('id');
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Rekam Medis',
            'Nama Pasien',
            'Tanggal Kunjungan',
            'Poliklinik',
            'Anamnese',
            'Pemeriksaan Fisik',
            'Diagnosis',
            'Tindakan',
            'Resep',
            'Keterangan',
        ];
    }

    /**
     * @param  MedicalRecord  $record
     */
    public function map($record): array
    {
        return [
            ++$this->row,
            $record->patient?->medical_record_number,
            $record->patient?->name,
            $record->visit_date?->format('d/m/Y'),
            $record->polyclinic?->name,
            $record->anamnese,
            $record->pemeriksaan_fisik,
            $record->diagnosis,
            $record->action_taken,
            $record->prescription,
            $record->keterangan,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
