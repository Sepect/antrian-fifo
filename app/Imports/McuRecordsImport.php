<?php

namespace App\Imports;

use App\Models\McuRecord;
use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class McuRecordsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama'])) {
            return null;
        }

        // Find patient
        $patient = null;
        if (isset($row['nama_pasien'])) {
            $patient = Patient::where('id_rm_p', $row['nama_pasien'])->first();
        }
        if (!$patient && isset($row['nama'])) {
            $patient = Patient::where('name', $row['nama'])->first();
        }

        return new McuRecord([
            'patient_id' => $patient ? $patient->id : 1, // Fallback
            'tgl_mcu'    => isset($row['tgl_mcu']) ? Date::excelToDateTimeObject($row['tgl_mcu']) : null,
            'hasil_mcu'  => $row['hasil_mcu'] ?? null,
        ]);
    }
}
