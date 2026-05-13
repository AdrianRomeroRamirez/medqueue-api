<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Doctores
        User::create([
            'name'     => 'Dr. García',
            'email'    => 'garcia@medqueue.com',
            'password' => 'password',
            'role'     => 'doctor',
        ]);

        User::create([
            'name'     => 'Dra. Martínez',
            'email'    => 'martinez@medqueue.com',
            'password' => 'password',
            'role'     => 'doctor',
        ]);

        // Pacientes
        User::create([
            'name'     => 'Adrian Romero',
            'email'    => 'adrian@medqueue.com',
            'password' => 'password',
            'role'     => 'patient',
        ]);

        User::create([
            'name'     => 'Laura Sánchez',
            'email'    => 'laura@medqueue.com',
            'password' => 'password',
            'role'     => 'patient',
        ]);
    }
}