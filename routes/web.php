<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\MedicalRecordController;

// Guest Routes
Route::get('/', [GuestController::class, 'showRegister']);
Route::post('/register', [GuestController::class, 'processRegister']);
Route::get('/status', [GuestController::class, 'showStatus']);
Route::get('/status-display', [GuestController::class, 'trackDisplay']); // Using GET to make it easy to copy-paste URLs or refresh

// Auth
Route::get('/login', [StaffController::class, 'showLogin'])->name('login');
Route::post('/login', [StaffController::class, 'processLogin']);
Route::post('/logout', [StaffController::class, 'logout'])->name('logout');

// Staff Routes
Route::middleware('auth')->prefix('staff')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard']);
    
    // Personal Profile Setting (Accessible by Both)
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'editProfile']);
    Route::post('/profile', [\App\Http\Controllers\UserController::class, 'updateProfile']);
    
    // Patient Directory (Accessible by Both)
    Route::get('/patients', [\App\Http\Controllers\PatientController::class, 'index']);
    Route::get('/patients/{patient}', [\App\Http\Controllers\PatientController::class, 'show']);
    // Perawat Routes
    Route::middleware('role:perawat')->group(function () {
        Route::post('/queue/{queue}/call', [StaffController::class, 'callPatient']);
        Route::post('/queue/{queue}/cancel', [StaffController::class, 'cancelPatient']);

        Route::get('/queue-history', [StaffController::class, 'history']);
        
        Route::get('/screening/{queue?}', [ScreeningController::class, 'show']);
        Route::post('/screening/{queue}', [ScreeningController::class, 'store']);

        Route::get('/register-patient', function () {
            return view('staff.register-patient');
        });
        
        Route::post('/register-patient', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'patient_type' => 'required|in:baru,lama',
                'complaint' => 'required|string',
            ]);

            if ($request->patient_type === 'lama') {
                $request->validate([
                    'medical_record_number' => 'required|string|exists:patients,medical_record_number',
                ]);
                $patient = \App\Models\Patient::where('medical_record_number', strtoupper($request->medical_record_number))->first();
            } else {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'phone' => 'nullable|string|max:20',
                    'gender' => 'nullable|in:L,P',
                ]);

                $lastPatient = \App\Models\Patient::whereNotNull('medical_record_number')->latest('id')->first();
                if ($lastPatient && preg_match('/RM-(\d+)/', $lastPatient->medical_record_number, $matches)) {
                    $newNumber = 'RM-' . str_pad($matches[1] + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = 'RM-0001';
                }

                $patient = \App\Models\Patient::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'medical_record_number' => $newNumber,
                ]);
            }
            
            $today = \Carbon\Carbon::today();
            $existingQueue = \App\Models\Queue::where('patient_id', $patient->id)
                ->where('queue_date', $today)
                ->whereIn('status', ['menunggu', 'dipanggil'])
                ->first();
                
            if ($existingQueue) {
                return redirect('/staff/dashboard')->with('error', 'Pasien sudah memiliki antrean aktif hari ini.');
            }

            $lastQueue = \App\Models\Queue::where('queue_date', $today)->orderBy('queue_number', 'desc')->first();
            $queueNumber = $lastQueue ? $lastQueue->queue_number + 1 : 1;

            $queue = \App\Models\Queue::create([
                'patient_id' => $patient->id,
                'registered_by' => auth()->id(),
                'queue_date' => $today,
                'queue_number' => $queueNumber,
                'booking_code' => strtoupper(\Illuminate\Support\Str::random(6)),
                'screening_notes' => $request->complaint,
            ]);
            return redirect('/staff/dashboard')->with('message', 'Pasien ' . $patient->medical_record_number . ' antrean no. ' . $queueNumber);
        });

        Route::get('/emr/{queue?}', [MedicalRecordController::class, 'create']);
        Route::post('/emr/{queue}', [MedicalRecordController::class, 'store']);
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('master')->group(function () {
        Route::get('/screening', [\App\Http\Controllers\MasterScreeningController::class, 'index']);
        Route::post('/screening', [\App\Http\Controllers\MasterScreeningController::class, 'store']);
        Route::post('/screening/{screening}/update', [\App\Http\Controllers\MasterScreeningController::class, 'update']);
        Route::post('/screening/{screening}/delete', [\App\Http\Controllers\MasterScreeningController::class, 'destroy']);
        
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);
        Route::post('/users/{user}/update', [\App\Http\Controllers\UserController::class, 'update']);
        Route::post('/users/{user}/delete', [\App\Http\Controllers\UserController::class, 'destroy']);
    });
});
