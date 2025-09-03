<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            
            // Data Responden
            $table->enum('respondent_status', ['ditemukan', 'pindah', 'tidak_ditemukan', 'meninggal'])->default('ditemukan');
            $table->date('respondent_status_date');
            $table->string('head_of_household_name');
            $table->string('family_card_number', 16)->unique();
            $table->string('nik', 16);
            $table->text('address');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('village');
            $table->string('district');
            
            // Survey Tempat Tinggal
            $table->enum('house_ownership_status', ['milik_sendiri', 'kontrak', 'sewa', 'bebas_sewa', 'dinas', 'lainnya']);
            $table->enum('floor_type', ['marmer', 'keramik', 'parket', 'kayu', 'semen', 'bambu', 'tanah']);
            $table->enum('wall_type', ['tembok', 'kayu', 'bambu', 'seng', 'lainnya']);
            $table->enum('roof_type', ['genteng', 'seng', 'asbes', 'kayu', 'bambu', 'ijuk', 'lainnya']);
            $table->enum('water_source', ['pdam', 'sumur_bor', 'sumur_gali', 'mata_air', 'air_hujan', 'sungai', 'lainnya']);
            $table->enum('lighting_source', ['listrik_pln', 'listrik_non_pln', 'minyak_tanah', 'petromaks', 'lilin', 'lainnya']);
            $table->string('pln_id')->nullable();
            $table->string('electricity_power')->nullable();
            $table->enum('toilet_facility', ['sendiri', 'bersama', 'umum', 'tidak_ada']);
            $table->enum('toilet_type', ['leher_angsa', 'plengsengan', 'cemplung', 'tidak_ada']);
            $table->enum('waste_disposal', ['tangki_septik', 'spal', 'kolam', 'sungai', 'pantai', 'tanah_lapang', 'lainnya']);
            $table->enum('cooking_fuel', ['listrik', 'gas_lpg', 'gas_kota', 'minyak_tanah', 'kayu_bakar', 'arang', 'lainnya']);
            
            // Geotagging
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Status dan Verifikasi
            $table->enum('recommendation_status', ['miskin', 'tidak_miskin'])->nullable();
            $table->enum('verification_status', ['belum_diverifikasi', 'disetujui', 'perlu_revisi'])->default('belum_diverifikasi');
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            
            // Data Entry
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('family_card_number');
            $table->index('nik');
            $table->index('verification_status');
            $table->index('recommendation_status');
            $table->index(['district', 'village']);
            $table->index('created_by');
            $table->index('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};