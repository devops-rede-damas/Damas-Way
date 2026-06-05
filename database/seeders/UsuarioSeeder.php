<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        // Criar nível Super Administrador
        $nivelSuperAdmin = DB::table('niveis')->insertGetId([
            'nivel' => 'Super Administrador',
            'status' => 1,
            'criado_em' => now(),
            'criado_por' => 1,
        ]);

        // Criar nível Administrador
        DB::table('niveis')->insertGetId([
            'nivel' => 'Administrador',
            'status' => 1,
            'criado_em' => now(),
            'criado_por' => 1,
        ]);

        // Criar nível Operador
        DB::table('niveis')->insertGetId([
            'nivel' => 'Operador',
            'status' => 1,
            'criado_em' => now(),
            'criado_por' => 1,
        ]);

        // Criar usuário super admin
        DB::table('usuarios')->insert([
            'chapa' => '000001',
            'nome' => 'Super Administrador',
            'email' => 'admin@damasway.com',
            'senha' => Hash::make('123456'),
            'nivel_id' => $nivelSuperAdmin,
            'status' => 1,
            'criado_em' => now(),
            'criado_por' => 1,
        ]);
    }
}
