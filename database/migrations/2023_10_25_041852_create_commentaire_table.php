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
        Schema::create('commentaire', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('utilisateur_id')->constrained('users'); // Supposant que vous utilisez la table 'users' pour les utilisateurs
            $table->enum('type', ['adhesion', 'personne']);
            $table->unsignedBigInteger('entite_id');
            $table->text('commentaire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaire');
    }
};
