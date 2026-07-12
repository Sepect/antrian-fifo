<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ClinicDataExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function download(): BinaryFileResponse
    {
        $filename = 'data-pasien-rekam-medis-' . Carbon::now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new ClinicDataExport(), $filename);
    }
}
