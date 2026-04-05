<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Patient;
use App\Models\Screening;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/staff/dashboard');
        }

        return back()->withErrors([
            'email' => 'Informasi login tidak ditemukan.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function dashboard()
    {
        $today = Carbon::today();
        
        $stats = [
            'total' => Queue::where('queue_date', $today)->count(),
            'menunggu' => Queue::where('queue_date', $today)->where('status', 'menunggu')->count(),
            'darurat' => Queue::where('queue_date', $today)->where('priority', 'darurat')->count(),
            'selesai' => Queue::where('queue_date', $today)->where('status', 'selesai')->count(),
        ];

        // Core of Smart FIFO Logic mapping
        // Core of Smart FIFO Logic mapping
        $queues = Queue::with('patient')
            ->where('queue_date', $today)
            ->whereIn('status', ['menunggu', 'dipanggil', 'selesai'])
            ->orderByRaw("FIELD(status, 'dipanggil', 'menunggu', 'selesai')")
            ->orderBy('total_score', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('staff.dashboard', compact('queues', 'stats'));
    }

    public function callPatient(Queue $queue)
    {
        // Set all currently 'dipanggil' back to 'menunggu' (if any was left hanging)
        Queue::where('queue_date', Carbon::today())
             ->where('status', 'dipanggil')
             ->update(['status' => 'menunggu']);

        $queue->update(['status' => 'dipanggil']);
        
        // TODO: Broadcast QueueUpdated Event
        
        return back()->with('message', 'Pasien dipanggil.');
    }

    public function cancelPatient(Queue $queue)
    {
        $queue->update(['status' => 'batal']);
        // TODO: Broadcast Event for auto-shift update in guests
        return back();
    }
    public function history(Request $request)
    {
        $query = Queue::with('patient')->orderBy('created_at', 'desc');
        
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereHas('patient', function($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(medical_record_number) LIKE ?', ["%{$search}%"]);
            })->orWhereRaw('LOWER(booking_code) LIKE ?', ["%{$search}%"]);
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('queue_date', $request->date);
        }

        $queues = $query->paginate(20)->withQueryString();
        
        return view('staff.queue-history', compact('queues'));
    }
}
