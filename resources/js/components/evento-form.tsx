import { Link, useForm } from '@inertiajs/react';
import { Save } from 'lucide-react';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { EventoForm } from '@/types/calendario';

type Props = {
    accion: string;
    metodo: 'post' | 'put';
    evento?: EventoForm;
    volver: string;
};

export default function EventoForm({ accion, metodo, evento, volver }: Props) {
    const { data, setData, post, put, processing, errors } = useForm({
        titulo: evento?.titulo ?? '',
        fecha: evento?.fecha ?? '',
    });

    const enviar = (e: React.FormEvent) => {
        e.preventDefault();

        if (metodo === 'post') {
            post(accion);
        } else {
            put(accion);
        }
    };

    return (
        <div className="flex flex-1 flex-col gap-4 p-4">
            <Heading
                title={metodo === 'post' ? 'Nuevo evento' : 'Editar evento'}
                description="Colegio Alessandro Volta"
            />

            <form
                onSubmit={enviar}
                className="grid max-w-xl gap-4 rounded-xl border bg-card p-4"
            >
                <div className="grid gap-1.5">
                    <Label htmlFor="titulo">Título *</Label>
                    <Input
                        id="titulo"
                        value={data.titulo}
                        onChange={(e) => setData('titulo', e.target.value)}
                        required
                    />
                    <InputError message={errors.titulo} />
                </div>

                <div className="grid gap-1.5">
                    <Label htmlFor="fecha">Fecha *</Label>
                    <Input
                        id="fecha"
                        type="date"
                        value={data.fecha}
                        onChange={(e) => setData('fecha', e.target.value)}
                        required
                    />
                    <InputError message={errors.fecha} />
                </div>

                <div className="flex gap-2">
                    <Button type="submit" disabled={processing}>
                        <Save />
                        Guardar
                    </Button>

                    <Button variant="outline" asChild>
                        <Link href={volver}>Cancelar</Link>
                    </Button>
                </div>
            </form>
        </div>
    );
}
