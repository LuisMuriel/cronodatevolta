import { Head, Link, usePage } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { useState } from 'react';
import ActividadFila from '@/components/actividad-fila';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { fechaLarga } from '@/lib/fechas';
import { calendario } from '@/routes';
import type { ActividadEditable, EventoDetalle } from '@/types/calendario';

type NuevaFila = ActividadEditable & { key: number };

export default function EventoShow({
    evento,
    actividades,
    cursos,
}: {
    evento: EventoDetalle;
    actividades: ActividadEditable[];
    cursos: string[];
}) {
    const { isAdmin } = usePage().props;
    const [nuevas, setNuevas] = useState<NuevaFila[]>([]);

    return (
        <>
            <Head title={evento.titulo} />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <div className="flex flex-wrap items-start justify-between gap-2">
                    <Heading
                        title={evento.titulo}
                        description={fechaLarga(evento.fecha)}
                    />

                    <div className="flex flex-wrap gap-2">
                        <Button variant="outline" asChild>
                            <Link href={calendario()}>
                                Volver al calendario
                            </Link>
                        </Button>
                    </div>
                </div>

                <datalist id="cursos">
                    {cursos.map((curso) => (
                        <option key={curso} value={curso} />
                    ))}
                </datalist>

                <div className="overflow-x-auto rounded-xl border bg-card">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3">Evento</th>
                                <th className="p-3">Curso</th>
                                <th className="p-3">Hora</th>
                                {isAdmin && <th className="p-3">Acciones</th>}
                            </tr>
                        </thead>

                        <tbody>
                            {actividades.length === 0 &&
                                nuevas.length === 0 && (
                                    <tr>
                                        <td
                                            colSpan={isAdmin ? 4 : 3}
                                            className="p-6 text-center text-muted-foreground"
                                        >
                                            Este evento no tiene actividades
                                            todavía.
                                        </td>
                                    </tr>
                                )}

                            {actividades.map((actividad) =>
                                isAdmin ? (
                                    <ActividadFila
                                        key={actividad.id}
                                        actividad={actividad}
                                        eventoId={evento.id}
                                    />
                                ) : (
                                    <tr key={actividad.id} className="border-t">
                                        <td className="p-3">
                                            {actividad.evento}
                                        </td>
                                        <td className="p-3">
                                            {actividad.curso ?? '—'}
                                        </td>
                                        <td className="p-3 whitespace-nowrap">
                                            {actividad.hora || '—'}
                                        </td>
                                    </tr>
                                ),
                            )}

                            {nuevas.map((fila) => (
                                <ActividadFila
                                    key={fila.key}
                                    actividad={fila}
                                    eventoId={evento.id}
                                    onGuardar={() =>
                                        setNuevas((lista) =>
                                            lista.filter(
                                                (n) => n.key !== fila.key,
                                            ),
                                        )
                                    }
                                    onEliminar={() =>
                                        setNuevas((lista) =>
                                            lista.filter(
                                                (n) => n.key !== fila.key,
                                            ),
                                        )
                                    }
                                />
                            ))}
                        </tbody>
                    </table>
                </div>

                {isAdmin && (
                    <div>
                        <Button
                            variant="outline"
                            onClick={() =>
                                setNuevas((lista) => [
                                    ...lista,
                                    {
                                        key: Date.now(),
                                        evento: '',
                                        curso: '',
                                        hora: '',
                                    },
                                ])
                            }
                        >
                            <Plus />
                            Agregar fila
                        </Button>
                    </div>
                )}
            </div>
        </>
    );
}
