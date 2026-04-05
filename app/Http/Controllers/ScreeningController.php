<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Screening;
use App\Models\QueueScreeningItem;

class ScreeningController extends Controller
{
    public function show(?Queue $queue = null)
    {
        $screenings = Screening::all();
        $pendingQueues = \App\Models\Queue::with('patient')->where('queue_date', \Carbon\Carbon::today())
                              ->whereIn('status', ['menunggu', 'dipanggil'])
                              ->orderBy('queue_number', 'asc')->get();
        $selectedIds = $queue && $queue->exists ? $queue->screeningItems()->pluck('screening_id')->toArray() : [];
        return view('staff.screening', compact('queue', 'screenings', 'pendingQueues', 'selectedIds'));
    }

    public function store(Request $request, Queue $queue)
    {
        $request->validate([
            'screenings' => 'array',
            'screenings.*' => 'exists:screenings,id'
        ]);

        $selectedScreenings = $request->input('screenings', []);

        $queue->screeningItems()->delete();
        foreach ($selectedScreenings as $sId) {
            QueueScreeningItem::create([
                'queue_id' => $queue->id,
                'screening_id' => $sId
            ]);
        }

        $totalScore = Screening::whereIn('id', $selectedScreenings)->sum('weight');
        $priority = $totalScore >= 10 ? 'darurat' : 'umum';

        $queue->update([
            'total_score' => $totalScore,
            'priority' => $priority
        ]);

        return redirect('/staff/dashboard')->with('success', 'Skoring berhasil diperbarui. Antrian bergeser secara otomatis.');
    }
}
