<?php

namespace App\Models;

use Database\Factories\ActividadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $evento_id
 * @property string $evento
 * @property string|null $curso
 * @property string|null $hora
 * @property-read Evento $evento
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['evento_id', 'evento', 'curso', 'hora'])]
class Actividad extends Model
{
    /** @use HasFactory<ActividadFactory> */
    use HasFactory;

    protected $table = 'actividades';

    /**
     * Evento al que pertenece la actividad.
     *
     * @return BelongsTo<Evento, $this>
     */
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    /**
     * Fecha del evento al que pertenece la actividad.
     */
    public function getFechaAttribute(): ?string
    {
        return $this->eventoRelacion()?->fecha?->toDateString();
    }

    /**
     * Título del evento al que pertenece la actividad.
     */
    public function getTituloEventoAttribute(): ?string
    {
        return $this->eventoRelacion()?->titulo;
    }

    /**
     * El evento padre, ya sea desde la relación cargada o consultándolo.
     */
    protected function eventoRelacion(): ?Evento
    {
        if ($this->relationLoaded('evento')) {
            return $this->getRelation('evento');
        }

        return $this->evento()->first();
    }
}
