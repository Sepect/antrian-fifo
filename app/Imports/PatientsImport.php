<?php

namespace App\Imports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PatientsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama'])) {
            return null;
        }

        // Validate if patient already exists to skip
        $exists = false;
        
        if (!empty($row['no_rm'])) {
            $exists = Patient::where('no_rm', $row['no_rm'])->orWhere('medical_record_number', $row['no_rm'])->exists();
        }
        
        if (!$exists && !empty($row['id_rm_p'])) {
            $exists = Patient::where('id_rm_p', $row['id_rm_p'])
                ->orWhere('medical_record_number', $row['id_rm_p'])
                ->orWhere('no_rm', $row['id_rm_p'])->exists();
        }
        
        if (!$exists && !empty($row['nama'])) {
            $exists = Patient::where('name', $row['nama'])->exists();
        }

        if ($exists) {
            return null; // Skip inserting if patient already exists
        }

        return new Patient([
            'no_rm' => $row['no_rm'] ?? null,
            'id_rm_p' => $row['id_rm_p'] ?? null,
            'medical_record_number' => $row['id_rm_p'] ?? $row['no_rm'] ?? null,
            'status_pasien' => $row['status_pasien'] ?? null,
            'keluarga_pegawai' => $row['keluarga_pegawai'] ?? null,
            'name' => $row['nama'],
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'birth_date' => isset($row['tgl_lahir']) ? Date::excelToDateTimeObject($row['tgl_lahir']) : null,
            'gender' => $row['jenis_kelamin'] ?? null,
            'religion' => $row['agama'] ?? null,
            'occupation' => $row['pekerjaan'] ?? null,
            'address' => $row['alamat'] ?? null,
            'keterangan' => $row['keterangan'] ?? null,
            'phone' => '000000000', // Default if empty
        ]);
    }
}
