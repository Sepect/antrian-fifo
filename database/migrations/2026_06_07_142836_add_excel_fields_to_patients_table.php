<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->integer('no_rm')->nullable();
            $table->string('id_rm_p')->nullable();
            $table->string('status_pasien')->nullable();
            $table->string('keluarga_pegawai')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->text('keterangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn([
                'employee_id',
                'no_rm',
                'id_rm_p',
                'status_pasien',
                'keluarga_pegawai',
                'tempat_lahir',
                'keterangan'
            ]);
        });
    }
};
