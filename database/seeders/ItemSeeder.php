<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create(['name' => 'Guantes de látex (caja x100)', 'description' => 'Para procedimientos veterinarios', 'unit' => 'caja', 'estimated_price' => 18000, 'active' => true]);
        Item::create(['name' => 'Shampoo antipulgas', 'description' => 'Shampoo medicado para baño de mascotas', 'unit' => 'unidad', 'estimated_price' => 25000, 'active' => true]);
        Item::create(['name' => 'Jeringas desechables 5ml', 'description' => 'Uso veterinario', 'unit' => 'caja', 'estimated_price' => 12000, 'active' => true]);
        Item::create(['name' => 'Alimento muestra cachorro', 'description' => 'Bolsas pequeñas para entregar a clientes', 'unit' => 'kg', 'estimated_price' => 8000, 'active' => true]);
        Item::create(['name' => 'Desinfectante de superficies', 'description' => 'Para limpieza de jaulas y mesas de trabajo', 'unit' => 'unidad', 'estimated_price' => 15000, 'active' => true]);
    }
}
