<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->string('id_rm')->nullable();
            $table->date('visit_date')->nullable();
            $table->foreignId('polyclinic_id')->nullable()->constrained('polyclinics')->nullOnDelete();
            $table->text('anamnese')->nullable();
            $table->text('pemeriksaan_fisik')->nullable();
            $table->text('keterangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign(['polyclinic_id']);
            $table->dropColumn([
                'id_rm',
                'visit_date',
                'polyclinic_id',
                'anamnese',
                'pemeriksaan_fisik',
                'keterangan'
            ]);
        });
    }
};
