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
        Schema::table('personne', function (Blueprint $table) {
            $table->string('hash_code', 64)->nullable(); // Longueur du hash SHA-256 (64 caractères)
            $table->enum('badge', ['Non', 'À remettre', 'Remis'])->default('Non');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personne', function (Blueprint $table) {
            $table->dropColumn('badge');
            $table->dropColumn('hash_code');
        });
    }
};
