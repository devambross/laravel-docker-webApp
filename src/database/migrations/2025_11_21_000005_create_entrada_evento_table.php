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
        Schema::create('entrada_evento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participante_evento_id')->constrained('participantes_evento')->onDelete('cascade');
            $table->boolean('entrada_club')->default(false);
            $table->boolean('entrada_evento')->default(false);
            $table->timestamp('fecha_hora_club')->nullable();
            $table->timestamp('fecha_hora_evento')->nullable();
            $table->timestamps();

            $table->unique('participante_evento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrada_evento');
    }
};
