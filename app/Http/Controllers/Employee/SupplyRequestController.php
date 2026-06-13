<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\SupplyRequest;
use Illuminate\Http\Request;

class SupplyRequestController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        $stats = [
            'draft'    => SupplyRequest::where('employee_id', $userId)->where('status', 'pending')->count(),
            'pending'  => SupplyRequest::where('employee_id', $userId)->where('status', 'pending')->count(),
            'approved' => SupplyRequest::where('employee_id', $userId)->where('status', 'approved')->count(),
            'rejected' => SupplyRequest::where('employee_id', $userId)->where('status', 'rejected')->count(),
        ];

        $recentRequests = SupplyRequest::where('employee_id', $userId)
            ->with('requestItems')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('employee.dashboard', compact('stats', 'recentRequests'));
    }

    public function index()
    {
        $requests = SupplyRequest::where('employee_id', auth()->id())
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10);

        return view('employee.requests.index', compact('requests'));
    }

    public function create()
    {
        return view('employee.requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high',
            'notes'    => 'nullable|string|max:500',
        ]);

        $supplyRequest = SupplyRequest::create([
            'employee_id'   => auth()->id(),
            'department_id' => auth()->user()->department_id,
            'priority'      => $request->priority,
            'notes'         => $request->notes,
        ]);

        return redirect()->route('employee.requests.show', $supplyRequest)
                        ->with('success', 'Solicitud creada, ahora agrega los ítems');
                        
    }

    public function show(SupplyRequest $supplyRequest)
    {
        
        if ($supplyRequest->employee_id !== auth()->id()) {
            abort(403);
        }

        $supplyRequest->load('requestItems.item');
        $items = Item::where('active', true)->orderBy('name')->get(); 

        return view('employee.requests.show', compact('supplyRequest','items'));
    }

    public function edit(SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->employee_id !== auth()->id()) {
            abort(403);
        }

        if (!$supplyRequest->isDraft()) {
            return redirect()->route('employee.requests.index')
                             ->with('error', 'Solo se pueden editar solicitudes en borrador');
        }

         return redirect()->route('employee.requests.show', $supplyRequest);
    }

    public function update(Request $request, SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->employee_id !== auth()->id()) {
            abort(403);
        }

        if (!$supplyRequest->isDraft()) {
            return redirect()->route('employee.requests.index')
                             ->with('error', 'Solo se pueden editar solicitudes en borrador');
        }

        $request->validate([
            'priority' => 'required|in:low,medium,high',
            'notes'    => 'nullable|string|max:500',
        ]);

        $supplyRequest->update([
            'priority' => $request->priority,
            'notes'    => $request->notes,
        ]);

        return redirect()->route('employee.requests.show', $supplyRequest)
                         ->with('success', 'Solicitud actualizada correctamente');
    }

    public function submit(SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->employee_id !== auth()->id()) {
            abort(403);
        }

        if (!$supplyRequest->isDraft()) {
            return redirect()->route('employee.requests.index')
                             ->with('error', 'Solo se pueden enviar solicitudes en borrador');
        }

        if ($supplyRequest->requestItems()->count() === 0) {
            return redirect()->route('employee.requests.show', $supplyRequest)
                             ->with('error', 'Debes agregar al menos un ítem antes de enviar');
        }

        $supplyRequest->update(['status' => 'pending']);

        return redirect()->route('employee.requests.index')
                         ->with('success', 'Solicitud enviada correctamente');
    }


    public function destroy(SupplyRequest $supplyRequest)
    {
            if ($supplyRequest->employee_id !== auth()->id()) {
            abort(403);
        }

        if (!$supplyRequest->isDraft()) {
            return redirect()
                ->route('employee.requests.index')
                ->with('error', 'Solo se pueden eliminar solicitudes en borrador');
        }

        // elimina primero los ítems relacionados (por seguridad)
        $supplyRequest->requestItems()->delete();

        // elimina la solicitud
        $supplyRequest->delete();

        return redirect()
            ->route('employee.requests.index')
            ->with('success', 'Solicitud eliminada correctamente');
    }
    
    
    public function addItem(Request $request, SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->employee_id !== auth()->id()) {
            abort(403);
        }

        if (!$supplyRequest->isDraft()) {
            return redirect()->route('employee.requests.show', $supplyRequest)
                            ->with('error', 'No se pueden agregar ítems a una solicitud enviada');
        }

        $request->validate([
            'item_id'       => 'required|exists:items,id',
            'quantity'      => 'required|integer|min:1',
            'justification' => 'nullable|string|max:255',
        ]);

        $supplyRequest->requestItems()->create([
            'item_id'       => $request->item_id,
            'quantity'      => $request->quantity,
            'justification' => $request->justification,
        ]);

        return redirect()->route('employee.requests.show', $supplyRequest)
                        ->with('success', 'Ítem agregado correctamente');
    }

    public function removeItem(SupplyRequest $supplyRequest, $itemId)
    {
        if ($supplyRequest->employee_id !== auth()->id()) {
            abort(403);
        }

        if (!$supplyRequest->isDraft()) {
            return redirect()->route('employee.requests.show', $supplyRequest)
                            ->with('error', 'No se pueden eliminar ítems de una solicitud enviada');
        }

        $supplyRequest->requestItems()->where('id', $itemId)->delete();

        return redirect()->route('employee.requests.show', $supplyRequest)
                        ->with('success', 'Ítem eliminado correctamente');
    }

}

