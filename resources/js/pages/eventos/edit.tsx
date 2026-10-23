import { Head } from '@inertiajs/react';
import EventoForm from '@/components/evento-form';
import eventos from '@/routes/eventos';
import type { EventoForm as EventoFormType } from '@/types/calendario';

export default function EditEvento({ evento }: { evento: EventoFormType }) {
    return (
        <>
            <Head title="Editar evento" />
            <EventoForm
                accion={eventos.update(evento.id!).url}
                metodo="put"
                evento={evento}
                volver={eventos.show(evento.id!).url}
            />
        </>
    );
}
