import { Head } from '@inertiajs/react';
import EventoForm from '@/components/evento-form';
import { calendario } from '@/routes';
import eventos from '@/routes/eventos';

export default function CreateEvento({
    fechaSeleccionada,
}: {
    fechaSeleccionada: string | null;
}) {
    return (
        <>
            <Head title="Nuevo evento" />
            <EventoForm
                accion={eventos.store().url}
                metodo="post"
                evento={{
                    titulo: '',
                    fecha: fechaSeleccionada ?? '',
                }}
                volver={calendario().url}
            />
        </>
    );
}
