<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Polyclinic;
use Illuminate\Http\Request;

class PolyclinicController extends Controller
{
    public function index()
    {
        $polyclinics = Polyclinic::all();
        return view('admin.polyclinics.index', compact('polyclinics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:polyclinics',
            'description' => 'nullable|string'
        ]);

        Polyclinic::create($request->all());

        return back()->with('success', 'Data Poli berhasil ditambahkan.');
    }

    public function update(Request $request, Polyclinic $polyclinic)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:polyclinics,name,' . $polyclinic->id,
            'description' => 'nullable|string'
        ]);

        $polyclinic->update($request->all());

        return back()->with('success', 'Data Poli berhasil diperbarui.');
    }

    public function destroy(Polyclinic $polyclinic)
    {
        $polyclinic->delete();
        return back()->with('success', 'Data Poli berhasil dihapus.');
    }
}
