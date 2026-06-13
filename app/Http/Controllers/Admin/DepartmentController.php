<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\SupplyRequest;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount(['users', 'supplyRequests'])
                             ->orderBy('name')
                             ->paginate(10);

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string|max:255',
        ]);

        Department::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.departments.index')
                         ->with('success', 'Departamento creado correctamente');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string|max:255',
        ]);

        $department->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.departments.index')
                         ->with('success', 'Departamento actualizado correctamente');
    }

    public function destroy(Department $department)
    {
        if ($department->users()->exists()) {
            return redirect()->route('admin.departments.index')
                             ->with('error', 'No se puede eliminar, el departamento tiene usuarios asociados');
        }

       if ($department->supplyRequests()->exists()) {
            return redirect()->route('admin.departments.index')
                            ->with('error', 'No se puede eliminar, el departamento tiene solicitudes asociadas');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
                         ->with('success', 'Departamento eliminado correctamente');
    }

    public function report(Request $request)
    {
        $departments = Department::withCount([
            'supplyRequests',
            'supplyRequests as approved_count'  => fn($q) => $q->where('status', 'approved'),
            'supplyRequests as rejected_count'  => fn($q) => $q->where('status', 'rejected'),
            'supplyRequests as delivered_count' => fn($q) => $q->where('status', 'delivered'),
        ])->orderBy('name')->get();

        return view('admin.reports.departments', [
            'totalRequests'   => SupplyRequest::count(),
            'approvedCount'   => SupplyRequest::where('status', 'approved')->count(),
            'rejectedCount'   => SupplyRequest::where('status', 'rejected')->count(),
            'deliveredCount'  => SupplyRequest::where('status', 'delivered')->count(),
            'departmentStats' => $departments,
        ]);
    }
}