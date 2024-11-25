<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('passage_de_lame', function (Blueprint $table) {
            $table->string('lame_session')->default('Novembre 2024')->after('medaille_remise'); // Ajouter la colonne avec une valeur par défaut
        });

        // Mettre à jour les valeurs existantes
        DB::table('passage_de_lame')->update(['lame_session' => 'Février 2024']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('passage_de_lame', function (Blueprint $table) {
            $table->dropColumn('lame_session'); // Supprimer la colonne si la migration est annulée
        });
    }
};
