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
        Schema::create('adhesion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personne_id')->constrained('personne');
            $table->timestamp('date_creation_dossier');
            $table->foreignId('groupe_id')->constrained('groupe');
            $table->enum('etat', ['annulé', 'validé', 'réglé', 'complet', 'essai', 'liste d’attente', 'créé']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adhesion');
    }
};
