<?php

namespace App\Models;

use Database\Factories\EventoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $titulo
 * @property Carbon $fecha
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['titulo', 'fecha'])]
class Evento extends Model
{
    /** @use HasFactory<EventoFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'fecha' => 'date:Y-m-d',
        ];
    }

    /**
     * Actividades programadas para el evento.
     *
     * @return HasMany<Actividad, $this>
     */
    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class)->orderBy('hora');
    }
}
