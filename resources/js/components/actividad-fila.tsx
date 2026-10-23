import { router, useForm } from '@inertiajs/react';
import { Pencil, Save, Trash2, X } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import actividades from '@/routes/actividades';
import type { ActividadEditable } from '@/types/calendario';

export default function ActividadFila({
    actividad,
    eventoId,
    onGuardar,
    onEliminar,
}: {
    actividad: ActividadEditable;
    eventoId: number;
    onGuardar?: () => void;
    onEliminar?: () => void;
}) {
    const esNueva = !actividad.id;
    const [editando, setEditando] = useState(esNueva);

    const { data, setData, post, put, processing, errors, clearErrors } =
        useForm({
            evento_id: eventoId,
            evento: actividad.evento ?? '',
            curso: actividad.curso ?? '',
            hora: actividad.hora ?? '',
        });

    const guardar = () => {
        const opciones = {
            preserveScroll: true,
            onSuccess: () => {
                setEditando(false);
                onGuardar?.();
            },
        };

        if (actividad.id) {
            put(actividades.update(actividad.id).url, opciones);
        } else {
            post(actividades.store().url, opciones);
        }
    };

    const cancelar = () => {
        if (esNueva) {
            onEliminar?.();

            return;
        }

        clearErrors();
        setEditando(false);
    };

    const eliminar = () => {
        if (!actividad.id) {
            onEliminar?.();

            return;
        }

        if (confirm('¿Seguro que quieres eliminar esta actividad?')) {
            router.delete(actividades.destroy(actividad.id).url, {
                preserveScroll: true,
            });
        }
    };

    if (!editando) {
        return (
            <tr className="border-t">
                <td className="p-3">{actividad.evento}</td>
                <td className="p-3">{actividad.curso ?? '—'}</td>
                <td className="p-3 whitespace-nowrap">
                    {actividad.hora || '—'}
                </td>
                <td className="p-2">
                    <div className="flex gap-1">
                        <Button
                            size="icon"
                            variant="outline"
                            title="Editar"
                            onClick={() => {
                                clearErrors();
                                setEditando(true);
                            }}
                        >
                            <Pencil />
                        </Button>

                        <Button
                            size="icon"
                            variant="destructive"
                            title="Eliminar"
                            onClick={eliminar}
                        >
                            <Trash2 />
                        </Button>
                    </div>
                </td>
            </tr>
        );
    }

    return (
        <tr className="border-t">
            <td className="p-2 align-top">
                <Input
                    value={data.evento}
                    onChange={(e) => setData('evento', e.target.value)}
                    className="h-9"
                    placeholder="Actividad"
                />
                {errors.evento && (
                    <p className="mt-1 text-xs text-destructive">
                        {errors.evento}
                    </p>
                )}
            </td>

            <td className="p-2 align-top">
                <Input
                    list="cursos"
                    value={data.curso}
                    onChange={(e) => setData('curso', e.target.value)}
                    className="h-9"
                    placeholder="Curso"
                />
                {errors.curso && (
                    <p className="mt-1 text-xs text-destructive">
                        {errors.curso}
                    </p>
                )}
            </td>

            <td className="p-2 align-top">
                <Input
                    type="time"
                    value={data.hora}
                    onChange={(e) => setData('hora', e.target.value)}
                    className="h-9"
                />
                {errors.hora && (
                    <p className="mt-1 text-xs text-destructive">
                        {errors.hora}
                    </p>
                )}
            </td>

            <td className="p-2 align-top">
                <div className="flex gap-1">
                    <Button
                        size="icon"
                        onClick={guardar}
                        disabled={processing}
                        title="Guardar"
                    >
                        <Save />
                    </Button>

                    <Button
                        size="icon"
                        variant="outline"
                        onClick={cancelar}
                        title="Cancelar"
                    >
                        <X />
                    </Button>
                </div>
            </td>
        </tr>
    );
}
