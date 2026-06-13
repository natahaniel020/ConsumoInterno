<?php
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Employee\SupplyRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return match(auth()->user()->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'manager'  => redirect()->route('manager.dashboard'),
        'employee' => redirect()->route('employee.dashboard'),
        default    => abort(403),
    };
})->middleware(['auth'])->name('dashboard');

// ─── Perfil (Breeze) ──────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Zona Admin ───────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('departments', DepartmentController::class)->except('show');
        Route::resource('items',       ItemController::class)->except('show');
        Route::resource('users',       UserController::class)->except('show');
        Route::patch('items/{item}/toggle', [ItemController::class, 'toggle'])->name('items.toggle');
        Route::get('reports/departments', [DepartmentController::class, 'report'])->name('reports.departments');
        Route::get('deliveries/{supplyRequest}',         [AdminController::class, 'deliveryShow'])->name('deliveries.show');
        Route::patch('deliveries/{supplyRequest}/deliver',[AdminController::class, 'deliver'])->name('deliveries.deliver');

    });

// ─── Zona Manager ────────────────────────────────────
Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('requests',                           [ManagerController::class, 'index'])->name('requests.index');
        Route::get('requests/{supplyRequest}',           [ManagerController::class, 'show'])->name('requests.show');
        Route::patch('requests/{supplyRequest}/approve', [ManagerController::class, 'approve'])->name('requests.approve');
        Route::patch('requests/{supplyRequest}/reject',  [ManagerController::class, 'reject'])->name('requests.reject');
        Route::get('dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    });

// ─── Zona Employee ────────────────────────────────────   
Route::middleware(['auth', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {
        Route::get('dashboard', [SupplyRequestController::class, 'dashboard'])->name('dashboard');
        
        Route::get('catalog', function () {
            $items = \App\Models\Item::where('active', true)
                ->orderBy('name')
                ->get();

            return view('employee.catalog', compact('items'));
        })->name('catalog');

        Route::resource('requests', SupplyRequestController::class)
            ->only(['index', 'show', 'create', 'store', 'edit', 'update','destroy'])
            ->parameters(['requests' => 'supplyRequest']);

        Route::patch('requests/{supplyRequest}/submit', [SupplyRequestController::class, 'submit'])
             ->name('requests.submit');

        Route::post('requests/{supplyRequest}/items', [SupplyRequestController::class, 'addItem'])
             ->name('requests.items.add');

        Route::delete('requests/{supplyRequest}/items/{itemId}', [SupplyRequestController::class, 'removeItem'])
             ->name('requests.items.remove');
        
       
    });


require __DIR__.'/auth.php';