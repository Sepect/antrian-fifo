<?php

namespace App\Exports;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatientsSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private int $row = 0;

    public function title(): string
    {
        return 'Data Pasien';
    }

    public function query(): Builder
    {
        return Patient::query()->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Rekam Medis',
            'NIK',
            'Nama Pasien',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Umur',
            'Alamat',
            'No. HP',
            'Agama',
            'Pekerjaan',
            'Status Pasien',
            'Keterangan',
            'Terdaftar Sejak',
        ];
    }

    /**
     * @param  Patient  $patient
     */
    public function map($patient): array
    {
        return [
            ++$this->row,
            $patient->medical_record_number,
            // NIK ditulis sebagai teks; Excel akan memotong 16 digit jadi notasi
            // ilmiah kalau dibiarkan terbaca sebagai angka.
            (string) $patient->nik,
            $patient->name,
            $patient->gender_label,
            $patient->tempat_lahir,
            $patient->birth_date?->format('d/m/Y'),
            $patient->age,
            $patient->address,
            (string) $patient->phone,
            $patient->religion,
            $patient->occupation,
            $patient->status_pasien,
            $patient->keterangan,
            $patient->created_at?->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
