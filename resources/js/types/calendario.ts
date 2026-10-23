export type CursoSimple = {
    id: number;
    nombre: string;
};

export type CursoTabla = {
    id: number;
    nombre: string;
    nivel: string | null;
    turno: string | null;
};

export type EventoCalendario = {
    id: number;
    title: string;
    date: string;
};

export type EventoTabla = {
    id: number;
    titulo: string;
    fecha: string;
    actividades: number;
};

export type EventoForm = {
    id?: number;
    titulo: string;
    fecha: string;
};

export type EventoDetalle = {
    id: number;
    titulo: string;
    fecha: string;
};

export type ActividadEditable = {
    id?: number;
    evento: string;
    curso: string | null;
    hora: string;
};

export type ActividadCronograma = ActividadEditable & {
    id: number;
    evento_id: number;
    fecha: string;
    titulo_evento: string;
};
