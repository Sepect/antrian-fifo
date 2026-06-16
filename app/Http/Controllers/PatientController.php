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
                  ->orWhereRaw('LOWER(medical_record_number) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(nik) LIKE ?', ["%{$search}%"]);
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

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('staff.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $request->validate([
            'nik' => 'nullable|string|max:16',
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'religion' => 'nullable|string|max:50',
            'occupation' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ]);

        $patient->update($request->only([
            'nik', 'name', 'gender', 'birth_date',
            'religion', 'occupation', 'address', 'phone'
        ]));

        return redirect("/staff/patients/{$patient->id}")->with('message', 'Data pasien berhasil diperbarui.');
    }
}
