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
        Schema::create('household_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained('households')->onDelete('cascade');
            $table->enum('asset_type', [
                'lemari_es', 'ac', 'tv', 'laptop', 'komputer', 'hp_android', 'hp_biasa',
                'sepeda_motor', 'mobil', 'emas', 'tabungan', 'tanah_bangunan', 
                'sawah', 'kebun', 'kolam', 'ternak_sapi', 'ternak_kerbau', 
                'ternak_kuda', 'ternak_babi', 'ternak_kambing', 'ternak_ayam'
            ]);
            $table->boolean('owned')->default(false);
            $table->integer('quantity')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('household_id');
            $table->index(['household_id', 'asset_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_assets');
    }
};