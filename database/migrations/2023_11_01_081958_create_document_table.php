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
            $table->date('date');
            $table->string('type');
            $table->string('url');
            $table->string('extra');
            $table->unsignedBigInteger('saison_id');
            $table->unsignedBigInteger('personne_id');
            $table->foreign('saison_id')->references('id')->on('saison');
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
