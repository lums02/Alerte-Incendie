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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du device (ex: "Arduino Cuisine")
            $table->string('location')->nullable(); // Localisation physique
            $table->string('api_key')->unique(); // Clé API pour authentification
            $table->enum('status', ['online', 'offline', 'error'])->default('offline');
            $table->timestamp('last_seen_at')->nullable(); // Dernière communication
            $table->json('settings')->nullable(); // Configuration spécifique
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
