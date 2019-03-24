<?php

use Illuminate\Database\Seeder;

class UsuarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $docente_admin = \App\User::create([
            'name' => 'Gorky Suquinagua',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('51lv3r'),
        ]);

        //Creacion de materias
        \App\Materia::create([
            "nombre" => "Algebra lineal",
            "estado" => "ACTIVO"
        ]);

        \App\Materia::create([
            "nombre" => "Investigacion de operaciones",
            "estado" => "ACTIVO"
        ]);

        \App\Materia::create([
            "nombre" => "Centro de computo",
            "estado" => "ACTIVO"
        ]);

        \App\Materia::create([
            "nombre" => "Auditoria",
            "estado" => "ACTIVO"
        ]);

        \App\Materia::create([
            "nombre" => "Evaluacion de proyectos",
            "estado" => "ACTIVO"
        ]);

        \App\Materia::create([
            "nombre" => "POO",
            "estado" => "ACTIVO"
        ]);

        \App\Materia::create([
            "nombre" => "Analisis y Diseño de Sist.",
            "estado" => "ACTIVO"
        ]);

        //$this->call(MateriasTableSeeder::class);
    }
}
