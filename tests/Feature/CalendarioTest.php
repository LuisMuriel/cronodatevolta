<?php

namespace Tests\Feature;

use App\Models\Actividad;
use App\Models\Curso;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CalendarioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.calendar_pin' => '1234']);
    }

    public function test_la_pagina_de_inicio_solo_muestra_el_formulario_de_pin()
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('welcome'));
    }

    public function test_el_pin_correcto_permite_entrar_al_calendario()
    {
        $this->post(route('pin.store'), ['pin' => '1234'])
            ->assertRedirect(route('calendario'))
            ->assertSessionHas('pin_acceso', true);
    }

    public function test_un_pin_incorrecto_no_permite_entrar()
    {
        $this->post(route('pin.store'), ['pin' => '9999'])
            ->assertSessionHasErrors('pin');

        $this->assertGuest();
    }

    public function test_sin_pin_las_rutas_protegidas_redirigen_a_la_pagina_de_inicio()
    {
        $this->get(route('calendario'))->assertRedirect(route('home'));
        $this->get(route('cronograma'))->assertRedirect(route('home'));
    }

    public function test_el_administrador_accede_sin_pin()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get(route('calendario'))->assertOk();
        $this->actingAs($admin)->get(route('cronograma'))->assertOk();
    }

    public function test_la_pagina_de_calendario_muestra_los_eventos()
    {
        Evento::create([
            'titulo' => 'Festival de Danzas',
            'fecha' => '2026-06-12',
        ]);

        $this->withSession(['pin_acceso' => true])
            ->get(route('calendario'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('calendario')
                ->has('eventos')
                ->where('eventos', fn ($eventos): bool => $eventos[0]['title'] === 'Festival de Danzas'
                    && $eventos[0]['date'] === '2026-06-12')
            );
    }

    public function test_la_hoja_de_cronograma_muestra_las_actividades()
    {
        $evento = Evento::create([
            'titulo' => 'Festival de Danzas',
            'fecha' => '2026-06-12',
        ]);

        Actividad::create([
            'evento_id' => $evento->id,
            'evento' => 'Danza folklorica andina',
            'curso' => 'Tercero de Secundaria',
            'hora' => '08:00',
        ]);

        $this->withSession(['pin_acceso' => true])
            ->get(route('cronograma'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('cronograma')
                ->has('actividades')
                ->where('actividades', fn ($actividades): bool => $actividades[0]['evento'] === 'Danza folklorica andina'
                    && $actividades[0]['curso'] === 'Tercero de Secundaria'
                    && $actividades[0]['hora'] === '08:00'
                    && $actividades[0]['fecha'] === '2026-06-12')
            );
    }

    public function test_la_pagina_del_evento_muestra_su_tabla_de_actividades()
    {
        $evento = Evento::create([
            'titulo' => 'Festival de Danzas',
            'fecha' => '2026-06-12',
        ]);

        Actividad::create([
            'evento_id' => $evento->id,
            'evento' => 'Danza folklorica andina',
            'curso' => 'Tercero de Secundaria',
            'hora' => '08:00',
        ]);

        $this->withSession(['pin_acceso' => true])
            ->get(route('eventos.show', $evento))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('eventos/show')
                ->where('evento.titulo', 'Festival de Danzas')
                ->where('actividades', fn ($actividades): bool => $actividades[0]['evento'] === 'Danza folklorica andina')
            );
    }

    public function test_un_usuario_con_pin_no_puede_administrar_eventos()
    {
        $this->withSession(['pin_acceso' => true])->post(route('eventos.store'), [
            'titulo' => 'Festival de Danzas',
            'fecha' => '2026-06-12',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('eventos', ['titulo' => 'Festival de Danzas']);
    }

    public function test_entrar_con_pin_cierra_la_sesion_de_administrador()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('pin.store'), ['pin' => '1234'])
            ->assertRedirect(route('calendario'))
            ->assertSessionHas('pin_acceso', true);

        $this->assertGuest();
    }

    public function test_el_modo_publico_es_solo_lectura()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('pin.store'), ['pin' => '1234'])
            ->assertRedirect(route('calendario'));

        $this->assertGuest();

        $this->post(route('eventos.store'), [
            'titulo' => 'Festival de Danzas',
            'fecha' => '2026-06-12',
        ])->assertRedirect(route('login'));

        $this->withSession(['pin_acceso' => true])
            ->get(route('calendario'))
            ->assertInertia(fn (Assert $page) => $page->where('isAdmin', false));

        $this->assertDatabaseMissing('eventos', ['titulo' => 'Festival de Danzas']);
    }

    public function test_iniciar_sesion_como_administrador_sale_del_modo_publico()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->withSession(['pin_acceso' => true])
            ->post(route('login.store'), [
                'email' => $admin->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('calendario'))
            ->assertSessionMissing('pin_acceso');

        $this->assertAuthenticatedAs($admin);

        $this->post(route('eventos.store'), [
            'titulo' => 'Evento del administrador',
            'fecha' => '2026-06-12',
        ])->assertRedirect();

        $this->assertDatabaseHas('eventos', ['titulo' => 'Evento del administrador']);
    }

    public function test_un_usuario_sin_rol_de_administrador_no_administra_eventos()
    {
        $usuario = User::factory()->create(['is_admin' => false]);

        $this->actingAs($usuario)->get(route('eventos.index'))->assertForbidden();
        $this->actingAs($usuario)->get(route('eventos.create'))->assertForbidden();
    }

    public function test_un_usuario_sin_rol_de_administrador_no_edita_ni_elimina_eventos()
    {
        $usuario = User::factory()->create(['is_admin' => false]);
        $evento = Evento::create([
            'titulo' => 'Festival de Danzas',
            'fecha' => '2026-06-12',
        ]);

        $this->actingAs($usuario)->put(route('eventos.update', $evento), [
            'titulo' => 'Editado',
            'fecha' => '2026-06-13',
        ])->assertForbidden();

        $this->actingAs($usuario)
            ->delete(route('eventos.destroy', $evento))
            ->assertForbidden();

        $this->assertSame('Festival de Danzas', $evento->fresh()->titulo);
    }

    public function test_un_usuario_sin_rol_de_administrador_no_gestiona_actividades()
    {
        $usuario = User::factory()->create(['is_admin' => false]);
        $evento = Evento::create([
            'titulo' => 'Festival de Danzas',
            'fecha' => '2026-06-12',
        ]);
        $actividad = Actividad::create([
            'evento_id' => $evento->id,
            'evento' => 'Danza folklorica andina',
            'curso' => 'Tercero de Secundaria',
            'hora' => '08:00',
        ]);

        $this->actingAs($usuario)->post(route('actividades.store'), [
            'evento_id' => $evento->id,
            'evento' => 'Otra actividad',
            'curso' => 'Primero de Secundaria',
            'hora' => '09:00',
        ])->assertForbidden();

        $this->actingAs($usuario)->put(route('actividades.update', $actividad), [
            'evento' => 'Actividad editada',
        ])->assertForbidden();

        $this->actingAs($usuario)
            ->delete(route('actividades.destroy', $actividad))
            ->assertForbidden();

        $this->assertSame('Danza folklorica andina', $actividad->fresh()->evento);
    }

    public function test_un_usuario_sin_rol_de_administrador_no_gestiona_cursos()
    {
        $usuario = User::factory()->create(['is_admin' => false]);
        $curso = Curso::create([
            'nombre' => 'Primero de Secundaria',
            'nivel' => 'Secundaria',
            'turno' => 'Mañana',
        ]);

        $this->actingAs($usuario)->get(route('cursos.index'))->assertForbidden();

        $this->actingAs($usuario)->post(route('cursos.store'), [
            'nombre' => 'Segundo de Secundaria',
        ])->assertForbidden();

        $this->actingAs($usuario)->put(route('cursos.update', $curso), [
            'nombre' => 'Curso editado',
        ])->assertForbidden();

        $this->actingAs($usuario)
            ->delete(route('cursos.destroy', $curso))
            ->assertForbidden();

        $this->assertSame('Primero de Secundaria', $curso->fresh()->nombre);
    }

    public function test_el_administrador_crea_edita_y_elimina_un_evento()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('eventos.store'), [
            'titulo' => 'Dramatización',
            'fecha' => '2026-07-01',
        ])->assertRedirect();

        $evento = Evento::firstOrFail();

        $this->assertSame('Dramatización', $evento->titulo);
        $this->assertSame('2026-07-01', $evento->fecha->toDateString());

        $this->actingAs($admin)->put(route('eventos.update', $evento), [
            'titulo' => 'Dramatización editada',
            'fecha' => '2026-07-02',
        ])->assertRedirect();

        $this->assertSame('Dramatización editada', $evento->fresh()->titulo);

        $this->actingAs($admin)
            ->delete(route('eventos.destroy', $evento))
            ->assertRedirect(route('calendario'));

        $this->assertDatabaseMissing('eventos', ['id' => $evento->id]);
    }

    public function test_el_administrador_gestiona_las_actividades_del_evento()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $evento = Evento::create([
            'titulo' => 'Encuentro Deportivo',
            'fecha' => '2026-09-05',
        ]);

        $this->actingAs($admin)->post(route('actividades.store'), [
            'evento_id' => $evento->id,
            'evento' => 'Voleibol',
            'curso' => 'Quinto de Secundaria',
            'hora' => '09:00',
        ])->assertSessionHasNoErrors();

        $actividad = Actividad::firstOrFail();

        $this->assertSame('Voleibol', $actividad->evento);
        $this->assertSame('09:00', substr((string) $actividad->hora, 0, 5));

        $this->actingAs($admin)->put(route('actividades.update', $actividad), [
            'evento' => 'Voleibol mixto',
            'curso' => 'Sexto de Primaria',
            'hora' => '10:00',
        ])->assertSessionHasNoErrors();

        $this->assertSame('Voleibol mixto', $actividad->fresh()->evento);

        $this->actingAs($admin)
            ->delete(route('actividades.destroy', $actividad))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('actividades', ['id' => $actividad->id]);
    }

    public function test_se_valida_los_datos_del_evento()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('eventos.store'), [])
            ->assertSessionHasErrors(['titulo', 'fecha']);
    }
}
