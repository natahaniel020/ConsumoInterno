# ConsumoInterno — Documentación del Proyecto

## ¿Qué es este proyecto?

ConsumoInterno es una aplicación web para gestionar solicitudes de suministros dentro de una empresa. Los empleados piden materiales, los managers los aprueban o rechazan, y el administrador gestiona las entregas y el catálogo.

---

## Tecnologías que usamos

| Tecnología | ¿Para qué? |
|---|---|
| **Laravel 13** | Framework de PHP para el backend |
| **PHP 8.3** | Lenguaje de programación |
| **MySQL** | Base de datos |
| **Tailwind CSS** | Estilos visuales |
| **Blade** | Motor de plantillas de Laravel |
| **Breeze** | Autenticación (login/logout) |
| **Material Symbols** | Íconos de Google |
| **Vite** | Compilador de assets (CSS/JS) |

---

## Roles del sistema

El sistema tiene tres tipos de usuarios:

```
Admin       → Gestiona todo el sistema
Manager     → Aprueba o rechaza solicitudes
Employee    → Crea solicitudes de suministros
```

Cuando un usuario inicia sesión, Laravel lo redirige automáticamente a su área según su rol.

---

## Estructura de carpetas importantes

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AdminController.php       ← Dashboard y entregas del admin
│   │   │   ├── DepartmentController.php  ← CRUD de departamentos + reporte
│   │   │   ├── ItemController.php        ← CRUD de ítems del catálogo
│   │   │   └── UserController.php        ← CRUD de usuarios
│   │   ├── Manager/
│   │   │   └── ManagerController.php     ← Dashboard y gestión de solicitudes
│   │   ├── Employee/
│   │   │   └── SupplyRequestController.php ← Solicitudes del empleado
│   │   └── Auth/
│   │       └── AuthenticatedSessionController.php ← Login/Logout
│   └── Middleware/
│       └── CheckRole.php                 ← Verifica el rol del usuario
├── Models/
│   ├── User.php                          ← Usuario (admin, manager, employee)
│   ├── Department.php                    ← Departamentos
│   ├── Item.php                          ← Ítems del catálogo
│   ├── SupplyRequest.php                 ← Solicitudes de suministros
│   └── RequestItem.php                   ← Ítems dentro de una solicitud

resources/
└── views/
    ├── layouts/
    │   ├── app.blade.php                 ← Layout principal (con sidebar y navbar)
    │   ├── navbar.blade.php              ← Barra superior
    │   └── sidebar.blade.php            ← Menú lateral
    ├── auth/
    │   └── login.blade.php              ← Pantalla de inicio de sesión
    ├── admin/
    │   ├── dashboard.blade.php          ← Dashboard del administrador
    │   ├── departments/
    │   │   ├── index.blade.php          ← Lista de departamentos
    │   │   ├── create.blade.php         ← Crear departamento
    │   │   └── edit.blade.php           ← Editar departamento
    │   ├── items/
    │   │   ├── index.blade.php          ← Lista de ítems
    │   │   ├── create.blade.php         ← Crear ítem
    │   │   └── edit.blade.php           ← Editar ítem
    │   ├── users/
    │   │   ├── index.blade.php          ← Lista de usuarios
    │   │   ├── create.blade.php         ← Crear usuario
    │   │   └── edit.blade.php           ← Editar usuario
    │   ├── deliveries/
    │   │   └── show.blade.php           ← Detalle y confirmación de entrega
    │   ├── reports/
    │   │   └── departments.blade.php    ← Reporte de solicitudes por departamento
    │   └── components/
    │       └── crud-list-layout.blade.php ← Componente reutilizable para listados
    ├── manager/
    │   ├── dashboard.blade.php          ← Dashboard del manager
    │   └── requests/
    │       ├── index.blade.php          ← Lista de solicitudes
    │       └── show.blade.php           ← Detalle + aprobar/rechazar
    ├── employee/
    │   └── requests/
    │       └── index.blade.php          ← Mis solicitudes
    └── components/
        └── admin/
            ├── crud-list-layout.blade.php ← Layout de listados CRUD
            ├── crud-form-layout.blade.php ← Layout de formularios CRUD
            └── report-layout.blade.php    ← Layout de reportes

routes/
├── web.php                              ← Todas las rutas de la app
└── auth.php                             ← Rutas de login/logout (Breeze)
```

---

## Flujo de una solicitud

```
Employee crea solicitud (draft)
        ↓
Employee envía solicitud (pending)
        ↓
Manager aprueba o rechaza
        ↓
Si aprueba → (approved)
        ↓
Admin registra la entrega → (delivered)
```

---

## Rutas principales

### Públicas
| Método | URL | Qué hace |
|---|---|---|
| GET | `/login` | Muestra el formulario de login |
| POST | `/login` | Procesa el login |
| POST | `/logout` | Cierra sesión |

### Admin (`/admin/...`)
| Método | URL | Qué hace |
|---|---|---|
| GET | `/admin/dashboard` | Dashboard del administrador |
| GET | `/admin/departments` | Lista de departamentos |
| GET | `/admin/departments/create` | Formulario crear departamento |
| POST | `/admin/departments` | Guarda el departamento |
| GET | `/admin/departments/{id}/edit` | Formulario editar departamento |
| PUT | `/admin/departments/{id}` | Actualiza el departamento |
| DELETE | `/admin/departments/{id}` | Elimina el departamento |
| GET | `/admin/items` | Lista de ítems |
| GET | `/admin/users` | Lista de usuarios |
| GET | `/admin/deliveries/{id}` | Detalle de entrega |
| PATCH | `/admin/deliveries/{id}/deliver` | Marca como entregado |
| GET | `/admin/reports/departments` | Reporte por departamento |

### Manager (`/manager/...`)
| Método | URL | Qué hace |
|---|---|---|
| GET | `/manager/dashboard` | Dashboard del manager |
| GET | `/manager/requests` | Lista de solicitudes |
| GET | `/manager/requests/{id}` | Detalle de solicitud |
| PATCH | `/manager/requests/{id}/approve` | Aprueba solicitud |
| PATCH | `/manager/requests/{id}/reject` | Rechaza solicitud |

### Employee (`/employee/...`)
| Método | URL | Qué hace |
|---|---|---|
| GET | `/employee/dashboard` | Dashboard del empleado |
| GET | `/employee/requests` | Mis solicitudes |
| GET | `/employee/requests/create` | Crear solicitud |
| POST | `/employee/requests` | Guarda la solicitud |

---

## Componentes Blade reutilizables

Creamos tres componentes para no repetir código:

### 1. `crud-list-layout` — Listados
Se usa en todos los índices del admin (departments, items, users).
```blade
<x-admin.crud-list-layout
    title="Departamentos"
    :createRoute="route('admin.departments.create')"
    createLabel="Nuevo departamento">

    <x-slot:filters> ... </x-slot:filters>
    <x-slot:columns> ... </x-slot:columns>
    <x-slot:rows> ... </x-slot:rows>
    <x-slot:pagination> ... </x-slot:pagination>

</x-admin.crud-list-layout>
```

### 2. `crud-form-layout` — Formularios
Se usa en todos los create y edit del admin.
```blade
<x-admin.crud-form-layout
    title="Nuevo Departamento"
    :action="route('admin.departments.store')"
    method="POST"
    submitLabel="Crear"
    :cancelRoute="route('admin.departments.index')">

    <x-slot:formFields> ... </x-slot:formFields>

</x-admin.crud-form-layout>
```

---

## Sistema de diseño

Usamos tokens de color personalizados en `tailwind.config.js`. Los más importantes:

| Token | Color | Uso |
|---|---|---|
| `primary` | Azul oscuro `#003d9b` | Botones principales, enlaces |
| `secondary` | Gris azulado | Texto secundario |
| `on-surface` | Casi negro | Texto principal |
| `surface-container-lowest` | Blanco | Fondo de tarjetas |
| `outline-variant` | Gris claro | Bordes |
| `error` | Rojo | Errores y rechazos |

---

## Cómo correr el proyecto

```bash
# 1. Instalar dependencias
composer install
npm install

# 2. Configurar el entorno
cp .env.example .env
php artisan key:generate

# 3. Configurar la base de datos en .env
DB_DATABASE=ConsumoInterno
DB_USERNAME=root
DB_PASSWORD=

# 4. Crear las tablas
php artisan migrate

# 5. (Opcional) Datos de prueba
php artisan db:seed

# 6. Compilar assets
npm run dev

# 7. Correr el servidor
php artisan serve
```

> ⚠️ **Importante:** Antes de correr el proyecto, asegúrate de que MySQL esté corriendo (XAMPP, Laragon, etc.)

---

## Cosas pendientes

- [ ] Módulo de entregas completo con listado
- [ ] Exportar reporte a PDF
- [ ] Filtro por período en reportes
- [ ] Vistas del employee (dashboard, create, show de solicitudes)

---

## Autores

Proyecto desarrollado como práctica de técnico en programación.