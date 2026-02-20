# Networking - Gestor de Dispositivos y Tickets de Soporte

Sistema web de gestión de dispositivos de red y tickets de soporte técnico construido con Laravel, Vue 3 e Inertia.js.

## 📋 Descripción

Networking es una aplicación completa para:
- **Gestionar dispositivos de red** (routers, switches, firewalls, servidores, etc.)
- **Registrar y seguimiento de tickets** de soporte técnico
- **Control de usuarios** con roles (usuario, admin, superadmin)
- **Dashboard personalizado** según el rol del usuario

## 🚀 Características Principales

### Dispositivos de Red
- ✅ Crear, leer, actualizar y eliminar dispositivos
- ✅ Asignar dispositivos a usuarios
- ✅ Registrar estado (online, offline, mantenimiento)
- ✅ Guardar detalles técnicos (IP, MAC, ubicación)
- ✅ Vista de dispositivos disponibles

### Tickets de Soporte
- ✅ Crear tickets con descripción y prioridad
- ✅ Asignar prioridades (baja, media, alta, crítica)
- ✅ Estados de ticket (abierto, en progreso, cerrado, espera)
- ✅ Vincular tickets a dispositivos específicos
- ✅ Seguimiento de respuestas en tiempo real

### Sistema de Usuarios
- ✅ Roles: Usuario, Admin, Superadmin
- ✅ Autenticación segura
- ✅ Perfil de usuario personalizable
- ✅ Gestión de usuarios (solo Superadmin)

### Dashboard
- ✅ Estadísticas personalizadas por rol
- ✅ Dispositivos recientes
- ✅ Tickets recientes
- ✅ Acciones rápidas

## 🛠️ Stack Tecnológico

### Backend
- **Laravel 11** - Framework PHP
- **PHP 8.2+** - Lenguaje backend
- **MySQL** - Base de datos
- **Inertia.js** - Adapter frontend-backend

### Frontend
- **Vue 3** - Framework JavaScript
- **TypeScript** - Tipado estático
- **Tailwind CSS** - Estilos
- **Vite** - Bundler

### Herramientas
- **Ziggy** - Generación de rutas desde Vue
- **Composer** - Gestor de paquetes PHP
- **npm** - Gestor de paquetes Node

## 📦 Instalación

### Requisitos
- PHP 8.2 o superior
- Composer
- Node.js 16+ y npm
- MySQL 8.0+

### Pasos

1. **Clonar el repositorio**
```bash
git clone <repository-url>
cd networking
```

2. **Instalar dependencias PHP**
```bash
composer install
```

3. **Configurar archivo .env**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos en .env**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=networking
DB_USERNAME=root
DB_PASSWORD=
```

5. **Ejecutar migraciones**
```bash
php artisan migrate
```

6. **Instalar dependencias Node**
```bash
npm install
```

7. **Compilar assets**
```bash
npm run build
```

8. **Iniciar servidor**
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## 🔐 Roles y Permisos

| Rol | Permisos |
|-----|----------|
| **Usuario** | Ver dispositivos propios, crear/editar tickets propios |
| **Admin** | Ver todos los dispositivos, gestionar tickets |
| **Superadmin** | Acceso total, gestión de usuarios y dispositivos |

## 📁 Estructura del Proyecto

```
resources/
├── js/
│   ├── Pages/
│   │   ├── Dashboard.vue
│   │   ├── Tickets/
│   │   │   ├── Index.vue
│   │   │   ├── Create.vue
│   │   │   ├── Show.vue
│   │   │   └── Edit.vue
│   │   └── NetworkDevices/
│   │       ├── Index.vue
│   │       ├── Create.vue
│   │       ├── Show.vue
│   │       ├── Edit.vue
│   │       └── Available.vue
│   ├── Layouts/
│   │   ├── AuthenticatedLayout.vue
│   │   ├── GuestLayout.vue
│   │   └── AuthLayout.vue
│   └── Components/
│       ├── InputLabel.vue
│       ├── TextInput.vue
│       ├── PrimaryButton.vue
│       └── ...
├── css/
│   └── app.css
└── views/
    └── app.blade.php

app/
├── Http/
│   └── Controllers/
│       ├── DashboardController.php
│       ├── NetworkDeviceController.php
│       ├── TicketController.php
│       └── UserController.php
├── Models/
│   ├── User.php
│   ├── NetworkDevice.php
│   └── Ticket.php
└── ...

routes/
└── web.php
```

## 🔌 API Endpoints Principales

### Dispositivos
- `GET /devices` - Listar dispositivos
- `GET /devices/create` - Formulario crear
- `POST /devices` - Guardar dispositivo
- `GET /devices/{id}` - Ver dispositivo
- `GET /devices/{id}/edit` - Formulario editar
- `PUT /devices/{id}` - Actualizar dispositivo
- `DELETE /devices/{id}` - Eliminar dispositivo

### Tickets
- `GET /tickets` - Listar tickets
- `GET /tickets/create` - Formulario crear
- `POST /tickets` - Guardar ticket
- `GET /tickets/{id}` - Ver ticket
- `PUT /tickets/{id}` - Actualizar ticket
- `DELETE /tickets/{id}` - Eliminar ticket

### Dashboard
- `GET /dashboard` - Ver dashboard personalizado

## 🗄️ Modelos de Datos

### NetworkDevice
```php
id, name, type, ip_address, mac_address, location, 
status, owner_id, created_at, updated_at
```

### Ticket
```php
id, title, description, status, priority, user_id, 
device_id, assigned_to, created_at, updated_at
```

### User
```php
id, name, email, password, role, email_verified_at, 
created_at, updated_at
```

## 🚀 Comandos Útiles

```bash
# Compilar assets en desarrollo
npm run dev

# Compilar assets para producción
npm run build

# Ejecutar migraciones
php artisan migrate

# Crear usuario de prueba con Tinker
php artisan tinker

# Limpiar caché
php artisan cache:clear
php artisan config:clear

# Ver rutas registradas
php artisan route:list
```

## 📝 Crear Datos de Prueba

```bash
php artisan tinker
```

```php
use App\Models\User;
use App\Models\NetworkDevice;

# Crear usuario
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'superadmin'
]);

# Crear dispositivo
NetworkDevice::create([
    'name' => 'Router Principal',
    'type' => 'router',
    'ip_address' => '192.168.1.1',
    'status' => 'online'
]);

exit
```

## 🐛 Troubleshooting

### Error: "Page not found"
- Verifica que las rutas estén en el orden correcto en `routes/web.php`
- Ejecuta `npm run build`

### Error: "route() is not defined"
- Verifica que Ziggy esté configurado en `app.ts`
- Recarga la página (Ctrl+F5)

### Error: "Status truncated"
- Verifica que los valores de status sean: 'online', 'offline', 'maintenance'
- No uses otros valores como 'assigned'

### Base de datos vacía
- Ejecuta `php artisan migrate`
- Crea datos con Tinker o el formulario

## 📄 Licencia

Este proyecto es de código abierto bajo la licencia MIT.

## 👥 Autor

Desarrollado como sistema de gestión de red y soporte técnico.

## 📞 Soporte

Para reportar problemas o sugerencias, crea un issue en el repositorio.

---

**Última actualización:** 29 de noviembre de 2025
**Versión:** 1.0.0
