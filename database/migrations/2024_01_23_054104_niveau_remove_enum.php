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
        // Supression de l'enum sur la table personne
        Schema::table('personne', function (Blueprint $table) {
            $table->string('niveau')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personne', function (Blueprint $table) {
            $table->enum('niveau', ['Lame 1', 'Lame 2', 'Lame 3', 'Lame 4', 'Lame 5', 'Lame 6', 'Lame 7', 'Lame 8', 'Lame 1/2', 'Lame 3/4', 'Lame 5/6', 'Lame 7/8'])->nullable()->change();
        });
    }
};
