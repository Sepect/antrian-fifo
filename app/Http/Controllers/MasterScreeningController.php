<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;

class MasterScreeningController extends Controller
{
    public function index()
    {
        $screenings = Screening::orderBy('created_at', 'desc')->get();
        return view('staff.master.screening', compact('screenings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|integer',
            'category' => 'nullable|string|max:255',
        ]);

        Screening::create($request->only('name', 'weight', 'category'));

        return back()->with('success', 'Data kriteria gejala berhasil ditambahkan.');
    }

    public function update(Request $request, Screening $screening)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|integer',
            'category' => 'nullable|string|max:255',
        ]);

        $screening->update($request->only('name', 'weight', 'category'));

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Screening $screening)
    {
        $screening->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
}
