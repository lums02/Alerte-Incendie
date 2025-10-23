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
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 10, 4); // Valeur mesurée
            $table->string('unit')->nullable(); // Unité de mesure
            $table->timestamp('measured_at'); // Timestamp de la mesure
            $table->json('raw_data')->nullable(); // Données brutes du capteur
            $table->enum('quality', ['good', 'warning', 'error'])->default('good'); // Qualité de la mesure
            $table->timestamps();
            
            // Index pour optimiser les requêtes temporelles
            $table->index(['sensor_id', 'measured_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
};
