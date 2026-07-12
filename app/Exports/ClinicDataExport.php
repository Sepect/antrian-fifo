<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClinicDataExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * Satu berkas, dua sheet: pasien di sheet pertama, rekam medis di kedua.
     */
    public function sheets(): array
    {
        return [
            new PatientsSheet(),
            new MedicalRecordsSheet(),
        ];
    }
}
