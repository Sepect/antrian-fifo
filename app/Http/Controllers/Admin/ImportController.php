<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportRekamMedisJob;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $path = $request->file('excel_file')->store('imports');

        ImportRekamMedisJob::dispatch(storage_path('app/private/' . $path));

        return back()->with('success', 'File berhasil diunggah. Proses import sedang berjalan di background.');
    }
}
