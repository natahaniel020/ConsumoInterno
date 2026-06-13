<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Item;
use App\Models\SupplyRequest;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'departmentsCount'        => Department::count(),
            'itemsCount'              => Item::where('active', true)->count(),
            'managersCount'           => User::where('role', 'manager')->count(),
            'employeesCount'          => User::where('role', 'employee')->count(),
            'approvedWaitingDelivery' => SupplyRequest::with(['employee', 'department'])
                                            ->where('status', 'approved')
                                            ->orderBy('approved_at')
                                            ->get(),
        ]);
    }
    public function deliveryShow(SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->status !== 'approved') {
            abort(404);
        }

        $supplyRequest->load(['employee', 'department', 'requestItems.item', 'approver']);

        return view('admin.deliveries.show', compact('supplyRequest'));
    }

    public function deliver(SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->status !== 'approved') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Solo se pueden entregar solicitudes aprobadas.');
        }

        $supplyRequest->update([
            'status'       => 'delivered',
            'delivered_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Solicitud marcada como entregada correctamente.');
    }
}