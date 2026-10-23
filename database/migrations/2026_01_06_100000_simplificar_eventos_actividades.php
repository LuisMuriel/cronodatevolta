<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->string('evento')->default('');
            $table->string('curso')->nullable();
            $table->time('hora')->nullable();
        });

        DB::table('actividades')->update([
            'evento' => DB::raw('nombre'),
            'hora' => DB::raw('hora_inicio'),
        ]);

        DB::statement(
            'UPDATE actividades SET curso = (SELECT c.nombre FROM cursos c'
            .' INNER JOIN actividad_curso ac ON ac.curso_id = c.id'
            .' WHERE ac.actividad_id = actividades.id)'
        );

        Schema::table('actividades', function (Blueprint $table) {
            $table->dropColumn([
                'nombre',
                'tipo',
                'responsable',
                'hora_inicio',
                'hora_fin',
                'lugar',
                'observacion',
            ]);
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn([
                'descripcion',
                'hora_inicio',
                'hora_fin',
                'lugar',
                'imagen',
            ]);
        });

        Schema::dropIfExists('curso_evento');
        Schema::dropIfExists('actividad_curso');
    }

    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->string('nombre')->default('');
            $table->string('tipo')->default('Danza');
            $table->string('responsable')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->string('lugar')->nullable();
            $table->text('observacion')->nullable();
        });

        DB::table('actividades')->update([
            'nombre' => DB::raw('evento'),
            'hora_inicio' => DB::raw('hora'),
        ]);

        Schema::table('actividades', function (Blueprint $table) {
            $table->dropColumn(['evento', 'curso', 'hora']);
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->text('descripcion')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->string('lugar')->nullable();
            $table->string('imagen')->nullable();
        });

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
};
