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
        Schema::create('passage_de_lame', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('personne_id'); // Clé étrangère vers la table personne
            $table->date('date')->nullable();
            $table->string('examinateur')->nullable();
            $table->string('niveau'); // Les niveaux possibles que vous avez mentionnés
            $table->enum('etat', ['Planifié', 'Passé', 'Échoué', 'Annulé']);
            $table->string('medaille')->nullable(); // Médaille à remettre
            $table->boolean('medaille_remise')->default(false);
            $table->timestamps();

            // Clé étrangère référençant 'id' dans la table 'personne'
            $table->foreign('personne_id')->references('id')->on('personne')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passage_de_lame');
    }
};
