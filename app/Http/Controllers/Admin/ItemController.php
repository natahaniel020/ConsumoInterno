<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
   public function index()
{
    $items = Item::orderBy('name')
        ->when(request('search'), function ($q, $search) {
            $q->where('name', 'like', "%{$search}%");
        })
        ->when(request('active') !== null && request('active') !== '', function ($q) {
            $q->where('active', request('active'));
        })
        ->paginate(10);

    return view('admin.items.index', compact('items'));
}

    public function create()
    {
        return view('admin.items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255|unique:items,name',
            'description'     => 'nullable|string|max:255',
            'unit'            => 'required|in:unidad,caja,kg',
            'estimated_price' => 'required|numeric|min:0',
        ]);

        Item::create([
            'name'            => $request->name,
            'description'     => $request->description,
            'unit'            => $request->unit,
            'estimated_price' => $request->estimated_price,
            'active'          => $request->boolean('active'),
        ]);

        return redirect()->route('admin.items.index')
                         ->with('success', 'Ítem creado correctamente');
    }

    public function edit(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name'            => 'required|string|max:255|unique:items,name,' . $item->id,
            'description'     => 'nullable|string|max:255',
            'unit'            => 'required|in:unidad,caja,kg',
            'estimated_price' => 'required|numeric|min:0',
        ]);

        $item->update([
            'name'            => $request->name,
            'description'     => $request->description,
            'unit'            => $request->unit,
            'estimated_price' => $request->estimated_price,
        ]);

        return redirect()->route('admin.items.index')
                         ->with('success', 'Ítem actualizado correctamente');
    }

    public function toggle(Item $item)
    {
        $item->update(['active' => !$item->active]);

        $estado = $item->active ? 'activado' : 'desactivado';

        return redirect()->route('admin.items.index')
                         ->with('success', "Ítem {$estado} correctamente");
    }

    public function destroy(Item $item)
    {
        if ($item->requestItems()->exists()) {
            return redirect()->route('admin.items.index')
                             ->with('error', 'No se puede eliminar, el ítem tiene solicitudes asociadas');
        }

        $item->delete();

        return redirect()->route('admin.items.index')
                         ->with('success', 'Ítem eliminado correctamente');
    }
}