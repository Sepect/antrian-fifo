<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Queue;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GuestController extends Controller
{
    public function showRegister()
    {
        return view('guest.register');
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'patient_type' => 'required|in:baru,lama',
            'complaint' => 'required|string',
        ]);

        if ($request->patient_type === 'lama') {
            $request->validate([
                'medical_record_number' => 'required|string|exists:patients,medical_record_number',
            ]);
            $patient = Patient::where('medical_record_number', strtoupper($request->medical_record_number))->first();
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:L,P',
            ]);

            $lastPatient = Patient::whereNotNull('medical_record_number')->latest('id')->first();
            if ($lastPatient && preg_match('/RM-(\d+)/', $lastPatient->medical_record_number, $matches)) {
                $newNumber = 'RM-' . str_pad($matches[1] + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = 'RM-0001';
            }

            $patient = Patient::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'medical_record_number' => $newNumber,
            ]);
        }

        $today = Carbon::today();
        
        // Prevent registering twice in the same day if still waiting
        $existingQueue = Queue::where('patient_id', $patient->id)
            ->where('queue_date', $today)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->first();
            
        if ($existingQueue) {
            return redirect('/status')->with('error', 'Pasien sudah memiliki antrean aktif hari ini.');
        }

        $lastQueue = Queue::where('queue_date', $today)->orderBy('queue_number', 'desc')->first();
        $queueNumber = $lastQueue ? $lastQueue->queue_number + 1 : 1;
        $bookingCode = strtoupper(Str::random(6));

        $queue = Queue::create([
            'patient_id' => $patient->id,
            'queue_date' => $today,
            'queue_number' => $queueNumber,
            'booking_code' => $bookingCode,
            'screening_notes' => $request->complaint,
        ]);

        return redirect('/status')->with('success', [
            'number' => $queueNumber,
            'rm' => $patient->medical_record_number
        ]);
    }

    public function showStatus()
    {
        return view('guest.status');
    }

    public function trackDisplay(Request $request)
    {
        $request->validate([
            'medical_record_number' => 'required|string',
        ]);

        $rm = strtoupper($request->medical_record_number);

        $queue = Queue::with('patient')->where('queue_date', Carbon::today())
            ->whereHas('patient', function($q) use ($rm) {
                $q->where('medical_record_number', $rm);
            })
            ->latest()
            ->first();

        if (!$queue) {
            return back()->with('error', 'Kredensial Antrian tidak valid atau antrian bukan untuk hari ini.');
        }

        // Calculate Position Logic (Smart FIFO)
        // Those who have Higher Total Score, OR (Same Score AND Earlier Created At) and are still waiting.
        if ($queue->status === 'menunggu') {
            $aheadCount = Queue::where('queue_date', Carbon::today())
                ->where('status', 'menunggu')
                ->where(function($query) use ($queue) {
                    $query->where('total_score', '>', $queue->total_score)
                          ->orWhere(function($subq) use ($queue) {
                              $subq->where('total_score', '=', $queue->total_score)
                                   ->where('created_at', '<', $queue->created_at);
                          });
                })->count();
        } else {
            $aheadCount = 0;
        }

        $servingNow = Queue::where('queue_date', Carbon::today())
            ->where('status', 'dipanggil')
            ->orderBy('updated_at', 'desc')
            ->first();

        return view('guest.status-display', compact('queue', 'aheadCount', 'servingNow'));
    }
}
