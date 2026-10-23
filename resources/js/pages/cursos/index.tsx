import { Head, router, useForm } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import CursoFila from '@/components/curso-fila';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import rutasCursos from '@/routes/cursos';
import type { CursoTabla } from '@/types/calendario';

export default function CursosIndex({
    cursos: lista,
}: {
    cursos: CursoTabla[];
}) {
    const { data, setData, post, processing, errors, reset } = useForm({
        nombre: '',
        nivel: '',
        turno: '',
    });

    const eliminar = (id: number) => {
        if (confirm('¿Seguro que quieres eliminar este curso?')) {
            router.delete(rutasCursos.destroy(id));
        }
    };

    return (
        <>
            <Head title="Cursos" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <Heading
                    title="Cursos"
                    description="Colegio Alessandro Volta"
                />

                <form
                    onSubmit={(e) => {
                        e.preventDefault();
                        post(rutasCursos.store().url, {
                            preserveScroll: true,
                            onSuccess: () => reset(),
                        });
                    }}
                    className="grid items-end gap-3 rounded-xl border bg-card p-4 md:grid-cols-4"
                >
                    <div className="grid gap-1.5">
                        <Label htmlFor="nombre">Nombre *</Label>
                        <Input
                            id="nombre"
                            value={data.nombre}
                            onChange={(e) => setData('nombre', e.target.value)}
                            required
                        />
                        <InputError message={errors.nombre} />
                    </div>

                    <div className="grid gap-1.5">
                        <Label htmlFor="nivel">Nivel</Label>
                        <Input
                            id="nivel"
                            value={data.nivel}
                            onChange={(e) => setData('nivel', e.target.value)}
                            placeholder="Primaria, Secundaria..."
                        />
                        <InputError message={errors.nivel} />
                    </div>

                    <div className="grid gap-1.5">
                        <Label htmlFor="turno">Turno</Label>
                        <Input
                            id="turno"
                            value={data.turno}
                            onChange={(e) => setData('turno', e.target.value)}
                            placeholder="Mañana, Tarde"
                        />
                        <InputError message={errors.turno} />
                    </div>

                    <Button type="submit" disabled={processing}>
                        <Plus />
                        Agregar curso
                    </Button>
                </form>

                <div className="overflow-x-auto rounded-xl border bg-card">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3">Curso</th>
                                <th className="p-3">Nivel</th>
                                <th className="p-3">Turno</th>
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
                                        Todavía no hay cursos registrados.
                                    </td>
                                </tr>
                            )}

                            {lista.map((curso) => (
                                <CursoFila
                                    key={curso.id}
                                    curso={curso}
                                    onEliminar={() => eliminar(curso.id)}
                                />
                            ))}
                        </tbody>
                    </table>
                </div>

                <p className="text-sm text-muted-foreground">
                    Los cursos se usan como sugerencia al registrar el curso de
                    una actividad.
                </p>
            </div>
        </>
    );
}
