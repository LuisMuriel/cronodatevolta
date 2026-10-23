import { Head, Link, useForm } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import pin from '@/routes/pin';

export default function Welcome() {
    const { data, setData, post, processing, errors } = useForm({ pin: '' });

    return (
        <>
            <Head title="Ingresar" />

            <div className="flex min-h-screen flex-col items-center justify-center bg-background p-6 text-foreground">
                <div className="w-full max-w-sm space-y-5 rounded-xl border bg-card p-6 shadow-sm">
                    <div className="space-y-1 text-center">
                        <h1 className="text-xl font-semibold">
                            Colegio Alessandro Volta
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            Calendario y cronograma
                        </p>
                    </div>

                    <form
                        onSubmit={(e) => {
                            e.preventDefault();
                            post(pin.store().url);
                        }}
                        className="grid gap-3"
                    >
                        <div className="grid gap-1.5">
                            <Label htmlFor="pin">PIN</Label>
                            <Input
                                id="pin"
                                type="password"
                                inputMode="numeric"
                                autoComplete="off"
                                autoFocus
                                value={data.pin}
                                onChange={(e) => setData('pin', e.target.value)}
                            />
                            <InputError message={errors.pin} />
                        </div>

                        <Button
                            type="submit"
                            className="w-full"
                            disabled={processing}
                        >
                            Ingresar
                        </Button>
                    </form>

                    <div className="text-center">
                        <Link
                            href={login().url}
                            className="text-xs text-muted-foreground underline-offset-4 hover:underline"
                        >
                            Acceso administrador
                        </Link>
                    </div>
                </div>
            </div>
        </>
    );
}
