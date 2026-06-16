<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class RekamMedisImport implements WithMultipleSheets, SkipsUnknownSheets
{
    public function sheets(): array
    {
        return [
            0 => new PatientsImport(), // Sheet 1: Data Pasien
            1 => new MedicalRecordsImport(), // Sheet 2: Data Rekam Medis
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // Ignore unknown sheets
    }
}
