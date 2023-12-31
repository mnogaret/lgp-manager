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
        Schema::create('foyer', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant_total', 8, 2)->nullable(); // 8 chiffres au total, dont 2 après la virgule
            $table->decimal('montant_regle', 8, 2)->nullable(); // 8 chiffres au total, dont 2 après la virgule
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foyer');
    }
};
