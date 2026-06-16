<?php

namespace App\Imports;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Polyclinic;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MedicalRecordsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['id_rm']) && !isset($row['nama'])) {
            return null;
        }

        // Find patient by RM number or name
        $patient = null;
        
        if (isset($row['no_rm'])) {
            $patient = Patient::where('no_rm', $row['no_rm'])->orWhere('medical_record_number', $row['no_rm'])->first();
        }
        if (!$patient && isset($row['id_rm'])) {
            $patient = Patient::where('id_rm_p', $row['id_rm'])
                ->orWhere('no_rm', $row['id_rm'])
                ->orWhere('medical_record_number', $row['id_rm'])->first();
        }
        if (!$patient && isset($row['nama_pasien'])) {
            $patient = Patient::where('name', $row['nama_pasien'])
                ->orWhere('id_rm_p', $row['nama_pasien'])->first();
        }
        if (!$patient && isset($row['nama'])) {
            $patient = Patient::where('name', $row['nama'])->first();
        }

        // Find or create polyclinic
        $polyclinicId = null;
        if (isset($row['polbagian'])) {
            $polyName = strtoupper(trim($row['polbagian']));
            $polyclinic = Polyclinic::firstOrCreate(['name' => $polyName]);
            $polyclinicId = $polyclinic->id;
        }

        return new MedicalRecord([
            'id_rm'             => $row['id_rm'] ?? null,
            'patient_id'        => $patient ? $patient->id : 1, // Fallback to 1 if not found to avoid error (dummy)
            'queue_id'          => null,
            'visit_date'        => isset($row['tglkunjungan']) ? Date::excelToDateTimeObject($row['tglkunjungan']) : null,
            'polyclinic_id'     => $polyclinicId,
            'anamnese'          => $row['anamnase_keluhan_utama'] ?? null,
            'pemeriksaan_fisik' => $row['pemeriksaan_fisik'] ?? null,
            'diagnosis'         => $row['diagnose'] ?? 'Tidak ada',
            'prescription'      => $row['therapie'] ?? null,
            'keterangan'        => $row['ket'] ?? null,
        ]);
    }
}
