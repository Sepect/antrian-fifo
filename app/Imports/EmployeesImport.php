<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama'])) {
            return null;
        }

        return new Employee([
            'nip_lama'      => $row['niplama'] ?? null,
            'nip_baru'      => $row['nipbaru'] ?? null,
            'nama'          => $row['nama'],
            'nama_lengkap'  => $row['nama_lengkap'] ?? $row['nama'],
            'jabatan'       => $row['jabatan'] ?? null,
            'tempat_lahir'  => $row['tempat_lahir'] ?? null,
            'tgl_lahir'     => isset($row['tgl_lahir']) ? Date::excelToDateTimeObject($row['tgl_lahir']) : null,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
            'pekerjaan'     => $row['pekerjaan'] ?? null,
            'alamat'        => $row['alamat'] ?? null,
            'agama'         => $row['agama'] ?? null,
            'nama_pasangan' => $row['nama_pasangan'] ?? null,
            'unit_pasangan' => $row['unit_pasangan'] ?? null,
            'keterangan'    => $row['keterangan'] ?? null,
        ]);
    }
}
