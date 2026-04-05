<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::withCount('medicalRecords')->orderBy('created_at', 'desc');
        
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(medical_record_number) LIKE ?', ["%{$search}%"]);
        }

        $patients = $query->paginate(15)->withQueryString();
            
        return view('staff.patients.index', compact('patients'));
    }

    public function show($id)
    {
        $patient = Patient::with(['medicalRecords' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'medicalRecords.queue.screeningItems.screening'])->findOrFail($id);

        return view('staff.patients.show', compact('patient'));
    }
}
