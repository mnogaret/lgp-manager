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
            $table->unsignedBigInteger('foyer_id');
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
            $table->enum('sexe', ['M', 'F'])->nullable(); // M ou F
            $table->string('nationalite', ['Allemand', 'Américain', 'Brésilien', 'Canadien', 'Chinois', 'Espagnol', 'Estonien', 'Français', 'Lésothien', 'Libanais', 'Lituanien', 'Marocain', 'Russe', 'Suédois', 'Tunisien', 'Ukrainien'])->nullable();
            $table->string('ville_naissance')->nullable();
            $table->date('date_certificat_medical')->nullable();
            $table->string('nom_assurance')->nullable();
            $table->string('numero_assurance')->nullable();
            $table->enum('droit_image', ['O', 'N'])->nullable();
            $table->string('numero_licence')->nullable();
            $table->enum('niveau', ['Lame 1', 'Lame 2', 'Lame 3', 'Lame 4', 'Lame 5', 'Lame 6', 'Lame 7', 'Lame 8', 'Lame 1/2', 'Lame 3/4', 'Lame 5/6', 'Lame 7/8'])->nullable();
            $table->foreign('foyer_id')->references('id')->on('foyer');
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
