import esLocale from '@fullcalendar/core/locales/es';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import FullCalendar from '@fullcalendar/react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { CalendarDays, Plus } from 'lucide-react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { cronograma } from '@/routes';
import eventos from '@/routes/eventos';
import type { EventoCalendario } from '@/types/calendario';

export default function Calendario({
    eventos: listaEventos,
}: {
    eventos: EventoCalendario[];
}) {
    const { isAdmin } = usePage().props;

    return (
        <>
            <Head title="Calendario" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <div className="flex flex-wrap items-center justify-between gap-2">
                    <Heading
                        title="Calendario"
                        description="Colegio Alessandro Volta"
                    />

                    <div className="flex flex-wrap gap-2">
                        <Button variant="outline" asChild>
                            <Link href={cronograma()}>
                                <CalendarDays />
                                Ver cronograma
                            </Link>
                        </Button>

                        {isAdmin && (
                            <Button asChild>
                                <Link href={eventos.create()}>
                                    <Plus />
                                    Nuevo evento
                                </Link>
                            </Button>
                        )}
                    </div>
                </div>

                <div className="rounded-xl border bg-card p-2 sm:p-4">
                    <FullCalendar
                        plugins={[dayGridPlugin, interactionPlugin]}
                        locale={esLocale}
                        initialView="dayGridMonth"
                        firstDay={1}
                        height="auto"
                        dayMaxEvents={3}
                        headerToolbar={{
                            left: 'prev,next hoy',
                            center: 'title',
                            right: '',
                        }}
                        buttonText={{ today: 'Hoy', month: 'Mes' }}
                        events={listaEventos.map((e) => ({
                            id: String(e.id),
                            title: e.title,
                            start: e.date,
                            allDay: true,
                        }))}
                        eventClick={(info) => {
                            info.jsEvent.preventDefault();

                            router.get(eventos.show(Number(info.event.id)).url);
                        }}
                        dateClick={(info) => {
                            if (!isAdmin) {
                                return;
                            }

                            router.get(eventos.create().url, {
                                fecha: info.dateStr,
                            });
                        }}
                    />
                </div>

                {isAdmin && (
                    <p className="text-sm text-muted-foreground">
                        Clic en un día para crear un evento en esa fecha, o clic
                        en un evento para ver su detalle.
                    </p>
                )}
            </div>
        </>
    );
}
