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

        //$this->call(MateriasTableSeeder::class);
    }
}
