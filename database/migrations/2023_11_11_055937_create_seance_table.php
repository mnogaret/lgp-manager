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
        Schema::create('seance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_id')->constrained('groupe');
            $table->foreignId('creneau_id')->nullable()->constrained('creneau');
            $table->string('code')->unique(); // Un code unique pour identifier la séance
            $table->enum('statut', ['Ouvert', 'Fermé'])->default('Ouvert');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seance');
    }
};
