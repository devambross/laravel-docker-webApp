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
        Schema::create('asistencias_diarias', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_socio', 50); // Código del socio/familiar (0001, 0001-A, etc)
            $table->enum('tipo', ['socio', 'familiar', 'invitado']);
            $table->string('nombre');
            $table->string('dni', 20)->nullable();
            $table->foreignId('evento_id')->nullable()->constrained('eventos')->onDelete('cascade'); // Si viene de un evento
            $table->string('evento_nombre')->nullable(); // Nombre del evento (desnormalizado para histórico)
            $table->timestamp('fecha_hora_entrada'); // Fecha y hora exacta de la entrada
            $table->date('fecha'); // Fecha del día (para agrupar por día)
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index(['fecha', 'codigo_socio']);
            $table->index('fecha');
            $table->index('codigo_socio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias_diarias');
    }
};
