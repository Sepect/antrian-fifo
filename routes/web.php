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

// Dropdown pencarian nama pasien. Publik karena dipakai halaman guest; dibatasi rate limit
// dan tidak pernah mengembalikan No. RM maupun NIK.
Route::get('/patients/search', [\App\Http\Controllers\PatientController::class, 'search'])
    ->middleware('throttle:30,1');

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
    Route::get('/patients/{patient}/edit', [\App\Http\Controllers\PatientController::class, 'edit']);
    Route::post('/patients/{patient}/update', [\App\Http\Controllers\PatientController::class, 'update']);
    // Perawat Routes
    Route::middleware('role:perawat')->group(function () {
        Route::post('/queue/{queue}/call', [StaffController::class, 'callPatient']);
        Route::post('/queue/{queue}/cancel', [StaffController::class, 'cancelPatient']);

        Route::get('/queue-history', [StaffController::class, 'history']);
        
        Route::get('/screening/{queue?}', [ScreeningController::class, 'show']);
        Route::post('/screening/{queue}', [ScreeningController::class, 'store']);

        Route::get('/register-patient', function () {
            $polyclinics = \App\Models\Polyclinic::all();
            return view('staff.register-patient', compact('polyclinics'));
        });
        
        Route::post('/register-patient', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'patient_type' => 'required|in:baru,lama',
                'complaint' => 'required|string',
                'polyclinic_id' => 'required|exists:polyclinics,id',
            ]);

            if ($request->patient_type === 'lama') {
                $request->validate([
                    'patient_id' => 'required|exists:patients,id',
                ], [
                    'patient_id.required' => 'Silakan pilih nama pasien dari daftar pencarian.',
                    'patient_id.exists' => 'Silakan pilih nama pasien dari daftar pencarian.',
                ]);
                $patient = \App\Models\Patient::findOrFail($request->patient_id);
            } else {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'nik' => 'nullable|string|max:16',
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
                    'nik' => $request->nik,
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

            // Validasi antrean poli
            $lastQueue = \App\Models\Queue::where('queue_date', $today)
                ->where('polyclinic_id', $request->polyclinic_id)
                ->orderBy('queue_number', 'desc')
                ->first();
            $queueNumber = $lastQueue ? $lastQueue->queue_number + 1 : 1;

            $queue = \App\Models\Queue::create([
                'patient_id' => $patient->id,
                'polyclinic_id' => $request->polyclinic_id,
                'registered_by' => auth()->id(),
                'queue_date' => $today,
                'queue_number' => $queueNumber,
                'booking_code' => strtoupper(\Illuminate\Support\Str::random(6)),
                'screening_notes' => $request->complaint,
            ]);
            return redirect('/staff/dashboard')->with('message', 'Pasien ' . $patient->name . ' (' . $patient->medical_record_number . ') antrean no. ' . $queueNumber);
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

        Route::get('/import', [\App\Http\Controllers\Admin\ImportController::class, 'index'])->name('admin.import.index');
        Route::post('/import', [\App\Http\Controllers\Admin\ImportController::class, 'store'])->name('admin.import.store');

        Route::get('/polyclinics', [\App\Http\Controllers\Admin\PolyclinicController::class, 'index'])->name('admin.polyclinics.index');
        Route::post('/polyclinics', [\App\Http\Controllers\Admin\PolyclinicController::class, 'store'])->name('admin.polyclinics.store');
        Route::post('/polyclinics/{polyclinic}/update', [\App\Http\Controllers\Admin\PolyclinicController::class, 'update'])->name('admin.polyclinics.update');
        Route::post('/polyclinics/{polyclinic}/delete', [\App\Http\Controllers\Admin\PolyclinicController::class, 'destroy'])->name('admin.polyclinics.destroy');
    });
});
