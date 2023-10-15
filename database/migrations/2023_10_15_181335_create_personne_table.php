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
        Schema::create('personne', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('telephone1')->nullable();
            $table->string('telephone2')->nullable();
            $table->string('adresse_postale')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
            $table->date('date_naissance')->nullable();
            $table->char('sexe', 1)->nullable(); // M ou F
            $table->string('nationalite')->nullable();
            $table->string('ville_naissance')->nullable();
            $table->string('numero_licence')->nullable();
            $table->unsignedBigInteger('chef_de_foyer_id')->nullable();
            $table->foreign('chef_de_foyer_id')->references('id')->on('personne')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personne');
    }
};
