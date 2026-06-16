<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class RekamMedisImport implements WithMultipleSheets, SkipsUnknownSheets
{
    public function sheets(): array
    {
        return [
            'database'    => new EmployeesImport(),
            'data_pasien' => new PatientsImport(),
            'rekam_medis' => new MedicalRecordsImport(),
            'mcu'         => new McuRecordsImport(),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // Ignore unknown sheets
    }
}
