<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create(['name' => 'Veterinaria', 'description' => 'Atención clínica y cuidado de mascotas']);
        Department::create(['name' => 'Grooming', 'description' => 'Baño, corte y estética animal']);
    }
}
