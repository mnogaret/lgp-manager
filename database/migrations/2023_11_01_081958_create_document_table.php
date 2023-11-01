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
        Schema::create('document', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->enum('type', ['Assurance', 'Questionnaire santé', 'Autorisations', 'Certificat médical', 'Autre']);
            $table->enum('statut', ['OK', 'KO']);
            $table->string('url')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('personne_id');
            $table->foreign('personne_id')->references('id')->on('personne')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document');
    }
};
