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
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de la zone (ex: "Cuisine", "Salon")
            $table->text('description')->nullable();
            $table->decimal('pos_x', 8, 2)->nullable(); // Position X pour la vue 3D
            $table->decimal('pos_y', 8, 2)->nullable(); // Position Y pour la vue 3D
            $table->decimal('pos_z', 8, 2)->nullable(); // Position Z pour la vue 3D
            $table->json('dimensions')->nullable(); // Longueur, largeur, hauteur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
