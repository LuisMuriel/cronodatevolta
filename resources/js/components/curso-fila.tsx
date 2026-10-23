import { useForm } from '@inertiajs/react';
import { Pencil, Save, Trash2, X } from 'lucide-react';
import { useState } from 'react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import cursos from '@/routes/cursos';
import type { CursoTabla } from '@/types/calendario';

export default function CursoFila({
    curso,
    onEliminar,
}: {
    curso: CursoTabla;
    onEliminar: () => void;
}) {
    const [editando, setEditando] = useState(false);

    const { data, setData, put, processing, errors, clearErrors } = useForm({
        nombre: curso.nombre,
        nivel: curso.nivel ?? '',
        turno: curso.turno ?? '',
    });

    const guardar = () => {
        put(cursos.update(curso.id).url, {
            preserveScroll: true,
            onSuccess: () => setEditando(false),
        });
    };

    if (!editando) {
        return (
            <tr className="border-t">
                <td className="p-3 font-medium">{curso.nombre}</td>
                <td className="p-3">{curso.nivel ?? '—'}</td>
                <td className="p-3">{curso.turno ?? '—'}</td>
                <td className="p-3">
                    <div className="flex gap-1">
                        <Button
                            size="icon"
                            variant="outline"
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
                            onClick={onEliminar}
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
            <td className="p-2">
                <Input
                    value={data.nombre}
                    onChange={(e) => setData('nombre', e.target.value)}
                    className="h-8"
                />
                <InputError message={errors.nombre} />
            </td>
            <td className="p-2">
                <Input
                    value={data.nivel}
                    onChange={(e) => setData('nivel', e.target.value)}
                    className="h-8"
                    placeholder="Nivel"
                />
                <InputError message={errors.nivel} />
            </td>
            <td className="p-2">
                <Input
                    value={data.turno}
                    onChange={(e) => setData('turno', e.target.value)}
                    className="h-8"
                    placeholder="Turno"
                />
                <InputError message={errors.turno} />
            </td>
            <td className="p-2">
                <div className="flex gap-1">
                    <Button size="icon" onClick={guardar} disabled={processing}>
                        <Save />
                    </Button>

                    <Button
                        size="icon"
                        variant="outline"
                        onClick={() => setEditando(false)}
                    >
                        <X />
                    </Button>
                </div>
            </td>
        </tr>
    );
}
