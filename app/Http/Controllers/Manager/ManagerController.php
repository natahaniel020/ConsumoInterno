<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\SupplyRequest;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function dashboard()
    {
        return view('manager.dashboard', [
            'pendingCount'            => SupplyRequest::where('status', 'pending')->count(),
            'approvedCount'           => SupplyRequest::where('status', 'approved')->count(),
            'rejectedCount'           => SupplyRequest::where('status', 'rejected')->count(),
            'deliveredCount'          => SupplyRequest::where('status', 'delivered')->count(),
            'approvedWaitingDelivery' => SupplyRequest::with('employee')
                                            ->where('status', 'approved')
                                            ->orderBy('approved_at')
                                            ->take(8)
                                            ->get(),
        ]);
    }
    /**
     * Lista todas las solicitudes enviadas (pendientes, aprobadas, rechazadas).
     * El administrador ve todas, no solo las propias.
     */
    public function index(Request $request)
    {
        $query = SupplyRequest::with(['employee', 'department'])
            ->whereIn('status', ['pending', 'approved', 'rejected', 'delivered'])
            ->orderBy('created_at', 'desc');

        // Filtro opcional por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro opcional por prioridad
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $requests = $query->paginate(15)->withQueryString();

        return view('manager.requests.index', compact('requests'));
    }

    /**
     * Detalle completo de una solicitud con sus ítems.
     */
    public function show(SupplyRequest $supplyRequest)
    {
        // Solo solicitudes que ya fueron enviadas
        if ($supplyRequest->status === 'draft') {
            abort(404);
        }

        $supplyRequest->load(['employee', 'department', 'requestItems.item', 'approver']);

        return view('manager.requests.show', compact('supplyRequest'));
    }

    /**
     * Aprueba una solicitud pendiente.
     */
    public function approve(Request $request, SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->status !== 'pending') {
            return redirect()->route('manager.requests.show', $supplyRequest)
                ->with('error', 'Solo se pueden aprobar solicitudes en estado pendiente.');
        }

        $supplyRequest->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('manager.requests.show', $supplyRequest)
            ->with('success', 'Solicitud aprobada correctamente.');
    }

    /**
     * Rechaza una solicitud pendiente.
     */
    public function reject(Request $request, SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->status !== 'pending') {
            return redirect()->route('manager.requests.show', $supplyRequest)
                ->with('error', 'Solo se pueden rechazar solicitudes en estado pendiente.');
        }

        $supplyRequest->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),   // registra quién rechazó
            'approved_at' => now(),
        ]);

        return redirect()->route('manager.requests.show', $supplyRequest)
            ->with('success', 'Solicitud rechazada.');
    }
}