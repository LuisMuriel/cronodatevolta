const MESES = [
    'enero',
    'febrero',
    'marzo',
    'abril',
    'mayo',
    'junio',
    'julio',
    'agosto',
    'septiembre',
    'octubre',
    'noviembre',
    'diciembre',
];

const DIAS = [
    'domingo',
    'lunes',
    'martes',
    'miércoles',
    'jueves',
    'viernes',
    'sábado',
];

type Partes = { dia: number; mes: number; anio: number };

function partes(iso: string): Partes | null {
    const [anio, mes, dia] = (iso ?? '').split('-').map(Number);

    if (!anio || !mes || !dia) {
        return null;
    }

    return { dia, mes, anio };
}

/** "viernes 12 de junio de 2026" */
export function fechaLarga(iso: string): string {
    const p = partes(iso);

    if (!p) {
        return iso;
    }

    const diaSemana = DIAS[new Date(p.anio, p.mes - 1, p.dia).getDay()];

    return `${diaSemana} ${p.dia} de ${MESES[p.mes - 1]} de ${p.anio}`;
}

/** "12/06/2026" */
export function fechaCorta(iso: string): string {
    const p = partes(iso);

    if (!p) {
        return iso;
    }

    const mes = String(p.mes).padStart(2, '0');
    const dia = String(p.dia).padStart(2, '0');

    return `${dia}/${mes}/${p.anio}`;
}
