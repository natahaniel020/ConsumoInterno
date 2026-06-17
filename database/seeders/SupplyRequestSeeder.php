<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\SupplyRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupplyRequestSeeder extends Seeder
{
    public function run(): void
    {
        $ana    = User::where('email', 'ana@petshop.com')->first();
        $luis   = User::where('email', 'luis@petshop.com')->first();
        $sofia  = User::where('email', 'sofia@petshop.com')->first();
        $diego  = User::where('email', 'diego@petshop.com')->first();
        $manager = User::where('email', 'manager@petshop.com')->first();

        $guantes  = Item::where('name', 'like', 'Guantes%')->first();
        $shampoo  = Item::where('name', 'like', 'Shampoo%')->first();
        $jeringas = Item::where('name', 'like', 'Jeringas%')->first();

        // Borrador de Ana (sin enviar todavía)
        $draft = SupplyRequest::create([
            'employee_id'   => $ana->id,
            'department_id' => $ana->department_id,
            'status'        => 'draft',
            'priority'      => 'low',
            'notes'         => 'Aún reuniendo lo que necesito',
        ]);
        $draft->requestItems()->create(['item_id' => $guantes->id, 'quantity' => 2]);

        // Pendiente de Luis (esperando revisión del manager)
        $pending = SupplyRequest::create([
            'employee_id'   => $luis->id,
            'department_id' => $luis->department_id,
            'status'        => 'pending',
            'priority'      => 'medium',
            'notes'         => 'Necesario para esta semana',
        ]);
        $pending->requestItems()->create(['item_id' => $jeringas->id, 'quantity' => 3]);

        // Aprobada de Sofía (esperando entrega)
        $approved = SupplyRequest::create([
            'employee_id'   => $sofia->id,
            'department_id' => $sofia->department_id,
            'status'        => 'approved',
            'priority'      => 'high',
            'notes'         => 'Urgente para grooming',
            'approved_by'   => $manager->id,
            'approved_at'   => now()->subDays(1),
        ]);
        $approved->requestItems()->create(['item_id' => $shampoo->id, 'quantity' => 5]);

        // Rechazada de Diego
        $rejected = SupplyRequest::create([
            'employee_id'   => $diego->id,
            'department_id' => $diego->department_id,
            'status'        => 'rejected',
            'priority'      => 'low',
            'notes'         => 'Solicitud duplicada',
            'approved_by'   => $manager->id,
            'approved_at'   => now()->subDays(2),
        ]);
        $rejected->requestItems()->create(['item_id' => $guantes->id, 'quantity' => 1]);

        // Entregada de Luis
        $delivered = SupplyRequest::create([
            'employee_id'   => $luis->id,
            'department_id' => $luis->department_id,
            'status'        => 'delivered',
            'priority'      => 'medium',
            'notes'         => 'Ya recibido',
            'approved_by'   => $manager->id,
            'approved_at'   => now()->subDays(5),
            'delivered_at'  => now()->subDays(3),
        ]);
        $delivered->requestItems()->create(['item_id' => $jeringas->id, 'quantity' => 2]);
    }
}