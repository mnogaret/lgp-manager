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
        Schema::create('reglement', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Espèces', 'Virement', 'Chèque', 'Chèque vacance', 'Pass’Sport', 'Pass’Région', 'Caution']);
            $table->date('date')->nullable();
            $table->decimal('montant', 8, 2);
            $table->string('code')->nullable();
            $table->boolean('depose')->default(false);
            $table->boolean('acquitte')->default(false);
            $table->unsignedBigInteger('saison_id');
            $table->unsignedBigInteger('foyer_id');
            $table->foreign('saison_id')->references('id')->on('saison');
            $table->foreign('foyer_id')->references('id')->on('foyer')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reglement');
    }
};
