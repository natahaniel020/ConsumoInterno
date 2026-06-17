<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $veterinaria = Department::where('name', 'Veterinaria')->first()->id;
        $grooming    = Department::where('name', 'Grooming')->first()->id;

        User::create([
            'name'     => 'Admin Principal',
            'email'    => 'admin@petshop.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'          => 'Carlos Mendoza',
            'email'         => 'manager@petshop.com',
            'password'      => Hash::make('password'),
            'role'          => 'manager',
            'department_id' => $veterinaria,
        ]);

        User::create([
            'name'          => 'Ana Torres',
            'email'         => 'ana@petshop.com',
            'password'      => Hash::make('password'),
            'role'          => 'employee',
            'department_id' => $veterinaria,
        ]);

        User::create([
            'name'          => 'Luis Ramírez',
            'email'         => 'luis@petshop.com',
            'password'      => Hash::make('password'),
            'role'          => 'employee',
            'department_id' => $veterinaria,
        ]);

        User::create([
            'name'          => 'Sofía Gómez',
            'email'         => 'sofia@petshop.com',
            'password'      => Hash::make('password'),
            'role'          => 'employee',
            'department_id' => $grooming,
        ]);

        User::create([
            'name'          => 'Diego Castro',
            'email'         => 'diego@petshop.com',
            'password'      => Hash::make('password'),
            'role'          => 'employee',
            'department_id' => $grooming,
        ]);
    }
}
