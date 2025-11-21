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
        Schema::create('participantes_evento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('mesa_id')->nullable()->constrained('mesas')->onDelete('set null');
            $table->integer('numero_silla')->nullable();
            $table->enum('tipo', ['socio', 'invitado']);
            $table->string('codigo_socio', 50); // Código del socio o del socio anfitrión si es invitado
            $table->string('codigo_participante', 50); // S001 o S001-INV1, S001-INV2, etc.
            $table->string('dni', 20);
            $table->string('nombre');
            $table->string('n_recibo', 100)->nullable();
            $table->string('n_boleta', 100)->nullable();
            $table->timestamps();

            // Un participante no puede ocupar la misma silla dos veces en el mismo evento
            $table->unique(['evento_id', 'mesa_id', 'numero_silla']);
            // Un código de participante es único por evento
            $table->unique(['evento_id', 'codigo_participante']);

            $table->index(['codigo_socio', 'tipo']);
            $table->index('codigo_participante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participantes_evento');
    }
};
