import { Head, Link, router } from '@inertiajs/react';
import { Eye, Pencil, Plus, Trash2 } from 'lucide-react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { fechaCorta } from '@/lib/fechas';
import { calendario } from '@/routes';
import eventos from '@/routes/eventos';
import type { EventoTabla } from '@/types/calendario';

export default function EventosIndex({
    eventos: lista,
}: {
    eventos: EventoTabla[];
}) {
    const eliminar = (id: number) => {
        if (confirm('¿Seguro que quieres eliminar este evento?')) {
            router.delete(eventos.destroy(id).url);
        }
    };

    return (
        <>
            <Head title="Eventos" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <div className="flex flex-wrap items-center justify-between gap-2">
                    <Heading
                        title="Eventos"
                        description="Colegio Alessandro Volta"
                    />

                    <div className="flex flex-wrap gap-2">
                        <Button variant="outline" asChild>
                            <Link href={calendario()}>Ver calendario</Link>
                        </Button>

                        <Button asChild>
                            <Link href={eventos.create()}>
                                <Plus />
                                Nuevo evento
                            </Link>
                        </Button>
                    </div>
                </div>

                <div className="overflow-x-auto rounded-xl border bg-card">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3">Título</th>
                                <th className="p-3">Fecha</th>
                                <th className="p-3">Actividades</th>
                                <th className="p-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            {lista.length === 0 && (
                                <tr>
                                    <td
                                        colSpan={4}
                                        className="p-6 text-center text-muted-foreground"
                                    >
                                        Todavía no hay eventos. Crea el primero.
                                    </td>
                                </tr>
                            )}

                            {lista.map((evento) => (
                                <tr key={evento.id} className="border-t">
                                    <td className="p-3 font-medium">
                                        {evento.titulo}
                                    </td>
                                    <td className="p-3 whitespace-nowrap">
                                        {fechaCorta(evento.fecha)}
                                    </td>
                                    <td className="p-3">
                                        <Badge variant="secondary">
                                            {evento.actividades}
                                        </Badge>
                                    </td>
                                    <td className="p-3">
                                        <div className="flex gap-1">
                                            <Button
                                                size="icon"
                                                variant="outline"
                                                asChild
                                            >
                                                <Link
                                                    href={
                                                        eventos.show(evento.id)
                                                            .url
                                                    }
                                                >
                                                    <Eye />
                                                </Link>
                                            </Button>

                                            <Button
                                                size="icon"
                                                variant="outline"
                                                asChild
                                            >
                                                <Link
                                                    href={
                                                        eventos.edit(evento.id)
                                                            .url
                                                    }
                                                >
                                                    <Pencil />
                                                </Link>
                                            </Button>

                                            <Button
                                                size="icon"
                                                variant="destructive"
                                                onClick={() =>
                                                    eliminar(evento.id)
                                                }
                                            >
                                                <Trash2 />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}
