import { Head, Link, usePage } from '@inertiajs/react';
import { CalendarDays, Printer } from 'lucide-react';
import { useMemo } from 'react';
import ActividadFila from '@/components/actividad-fila';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { fechaLarga } from '@/lib/fechas';
import { calendario } from '@/routes';
import type { ActividadCronograma } from '@/types/calendario';

export default function Cronograma({
    actividades,
    cursos,
}: {
    actividades: ActividadCronograma[];
    cursos: string[];
}) {
    const { isAdmin } = usePage().props;

    const porDia = useMemo(() => {
        const grupos = new Map<string, ActividadCronograma[]>();

        for (const actividad of actividades) {
            const lista = grupos.get(actividad.fecha) ?? [];
            lista.push(actividad);
            grupos.set(actividad.fecha, lista);
        }

        return [...grupos.entries()].sort(([a], [b]) => a.localeCompare(b));
    }, [actividades]);

    return (
        <>
            <Head title="Cronograma" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <div className="flex flex-wrap items-center justify-between gap-2">
                    <Heading
                        title="Cronograma de actividades"
                        description="Colegio Alessandro Volta"
                    />

                    <div className="flex flex-wrap gap-2">
                        <Button variant="outline" asChild>
                            <Link href={calendario()}>
                                <CalendarDays />
                                Ver calendario
                            </Link>
                        </Button>

                        <Button
                            variant="outline"
                            onClick={() => window.print()}
                        >
                            <Printer />
                            Imprimir
                        </Button>
                    </div>
                </div>

                <datalist id="cursos">
                    {cursos.map((curso) => (
                        <option key={curso} value={curso} />
                    ))}
                </datalist>

                {porDia.length === 0 && (
                    <p className="rounded-xl border bg-card p-6 text-sm text-muted-foreground">
                        Todavía no hay actividades registradas.
                    </p>
                )}

                {porDia.map(([fecha, lista]) => (
                    <div key={fecha} className="rounded-xl border bg-card">
                        <div className="border-b p-4">
                            <h3 className="font-semibold">
                                {fechaLarga(fecha)}
                            </h3>
                            <p className="text-sm text-muted-foreground">
                                {lista[0]?.titulo_evento}
                            </p>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead className="bg-muted/50 text-left">
                                    <tr>
                                        <th className="p-3">Evento</th>
                                        <th className="p-3">Curso</th>
                                        <th className="p-3">Hora</th>
                                        {isAdmin && (
                                            <th className="p-3">Acciones</th>
                                        )}
                                    </tr>
                                </thead>

                                <tbody>
                                    {lista.map((actividad) =>
                                        isAdmin ? (
                                            <ActividadFila
                                                key={actividad.id}
                                                actividad={actividad}
                                                eventoId={actividad.evento_id}
                                            />
                                        ) : (
                                            <tr
                                                key={actividad.id}
                                                className="border-t"
                                            >
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                ))}
            </div>
        </>
    );
}
