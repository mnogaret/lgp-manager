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
        Schema::create('groupe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saison_id')->constrained('saison');
            $table->string('code');
            $table->string('nom');
            $table->string('type');
            $table->decimal('prix', 8, 2); // 8 chiffres au total, dont 2 après la virgule
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupe');
    }
};
