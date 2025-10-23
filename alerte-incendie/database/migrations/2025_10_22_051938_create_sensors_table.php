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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name'); // Nom du capteur (ex: "Capteur Fumée Cuisine")
            $table->enum('type', ['smoke', 'temperature', 'humidity', 'flame', 'gas']); // Type de capteur
            $table->string('unit')->nullable(); // Unité de mesure (°C, %, ppm, etc.)
            $table->decimal('threshold_warn', 8, 2)->nullable(); // Seuil d'avertissement
            $table->decimal('threshold_alarm', 8, 2)->nullable(); // Seuil d'alarme
            $table->enum('status', ['active', 'inactive', 'calibrating', 'error'])->default('active');
            $table->decimal('pos_x', 8, 2)->nullable(); // Position X dans la zone
            $table->decimal('pos_y', 8, 2)->nullable(); // Position Y dans la zone
            $table->decimal('pos_z', 8, 2)->nullable(); // Position Z dans la zone
            $table->json('calibration_data')->nullable(); // Données de calibration
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
