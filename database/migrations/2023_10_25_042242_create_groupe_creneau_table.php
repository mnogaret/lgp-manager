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
        Schema::create('groupe_creneau', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_id')->constrained('groupe');
            $table->foreignId('creneau_id')->constrained('creneau');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupe_creneau');
    }
};
