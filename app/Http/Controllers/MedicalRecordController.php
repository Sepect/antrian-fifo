<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    public function create(?Queue $queue = null)
    {
        if (!$queue || !$queue->exists) {
            $pendingQueues = Queue::with('patient')->where('queue_date', \Carbon\Carbon::today())
                                  ->whereIn('status', ['menunggu', 'dipanggil'])
                                  ->orderBy('queue_number', 'asc')->get();
            return view('staff.emr', compact('pendingQueues'));
        }
        
        $history = MedicalRecord::with('queue.screeningItems.screening')
                    ->where('patient_id', $queue->patient_id)
                    ->latest()
                    ->get();
                    
        return view('staff.emr', compact('queue', 'history'));
    }

    public function store(Request $request, Queue $queue)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'action_taken' => 'nullable|string',
            'prescription' => 'nullable|string'
        ]);

        MedicalRecord::create([
            'patient_id' => $queue->patient_id,
            'queue_id' => $queue->id,
            'diagnosis' => $request->diagnosis,
            'action_taken' => $request->action_taken,
            'prescription' => $request->prescription,
        ]);

        $queue->update(['status' => 'selesai']);

        return redirect('/staff/dashboard')->with('success', 'Pemeriksaan selesai. Antrian telah ditutup.');
    }
}
