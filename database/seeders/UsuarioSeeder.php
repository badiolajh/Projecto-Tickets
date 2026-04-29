<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────
        $adminId = DB::table('usuario')->insertGetId([
            'nombre'        => 'Admin Principal',
            'email'         => 'admin@test.com',
            'password_hash' => Hash::make('123456'),
            'cargo_u'       => 'Administrador',
            'area'          => 'TI',
            'activo'        => true,
            'created_at'    => now(),
        ]);
        DB::table('administrador')->insert([
            'id_admin'      => $adminId,
            'date_create_u' => now(),
        ]);

        // ── Empleado ───────────────────────────────────────────────
        $empleadoId = DB::table('usuario')->insertGetId([
            'nombre'        => 'Empleado Uno',
            'email'         => 'empleado@test.com',
            'password_hash' => Hash::make('123456'),
            'cargo_u'       => 'Empleado',
            'area'          => 'Ventas',
            'activo'        => true,
            'created_at'    => now(),
        ]);
        DB::table('empleado')->insert([
            'id_empleado' => $empleadoId,
        ]);

        // ── Técnico ────────────────────────────────────────────────
        $tecnicoId = DB::table('usuario')->insertGetId([
            'nombre'        => 'Tecnico Uno',
            'email'         => 'tecnico@test.com',
            'password_hash' => Hash::make('123456'),
            'cargo_u'       => 'Técnico',
            'area'          => 'Soporte',
            'activo'        => true,
            'created_at'    => now(),
        ]);
        DB::table('tecnico')->insert([
            'id_tecnico'    => $tecnicoId,
            'observaciones' => null,
        ]);
    }
}