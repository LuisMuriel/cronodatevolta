<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_evento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evento_id')->constrained()->cascadeOnDelete();
            $table->unique(['curso_id', 'evento_id']);
        });

        Schema::create('actividad_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();
            $table->unique(['curso_id', 'actividad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_evento');
        Schema::dropIfExists('actividad_curso');
    }
};
