<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('department')
            ->where('role', '!=', 'admin')
            ->when(request('search'), fn($q, $s) => 
                $q->where('name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%"))
            ->when(request('role'), fn($q, $r) => $q->where('role', $r))
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.users.create', compact('departments'));
    }

    public function store(Request $request)
    {
       

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|in:manager,employee',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Usuario creado correctamente');
    }

    public function edit(User $user)
    {
        if ($user->isAdmin()) {
            abort(403);
        }
            
        $departments = Department::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'password'      => 'nullable|string|min:8|confirmed',
            'role'          => 'required|in:manager,employee',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'role'          => $request->role,
            'department_id' => $request->department_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'No puedes eliminarte a ti mismo');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'No se puede eliminar un administrador');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Usuario eliminado correctamente');
    }
}