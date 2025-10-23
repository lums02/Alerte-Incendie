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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('sensor_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('level', ['info', 'warning', 'critical', 'emergency']); // Niveau d'alerte
            $table->string('title'); // Titre de l'alerte
            $table->text('message'); // Message détaillé
            $table->json('data')->nullable(); // Données supplémentaires (valeurs, etc.)
            $table->timestamp('triggered_at'); // Moment du déclenchement
            $table->timestamp('resolved_at')->nullable(); // Moment de résolution
            $table->enum('status', ['active', 'resolved', 'false_positive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
