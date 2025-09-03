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
        Schema::create('household_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained('households')->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('file_size');
            $table->string('mime_type');
            $table->integer('order')->default(1); // 1, 2, 3 for max 3 photos
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('household_id');
            $table->index(['household_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_photos');
    }
};