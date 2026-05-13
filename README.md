# MedQueue API 🏥

REST API para la gestión de citas médicas, construida con Laravel 11 y desplegada en AWS.

## Stack Técnico

- **Backend:** PHP 8.2 + Laravel 11
- **Autenticación:** Laravel Sanctum (token-based)
- **Base de datos:** MySQL 8.0
- **Colas:** Laravel Jobs (compatible con AWS SQS)
- **Infraestructura:** Docker (local) / AWS Elastic Beanstalk + RDS (producción)

## Arquitectura

Client (Vue 3)
│
▼
Laravel API  ──►  MySQL (RDS en producción)
│
▼
Queue Worker ──►  Jobs asíncronos (SQS en producción)

## Decisiones técnicas

- **Sanctum sobre Passport:** Para una API SPA/mobile, Sanctum es más ligero y suficiente. Passport añade complejidad innecesaria sin OAuth.
- **Jobs en cola:** El envío de notificaciones es asíncrono para no bloquear la respuesta de la API. En producción se conectaría a AWS SQS cambiando `QUEUE_CONNECTION=sqs` en el `.env`.
- **Roles en tabla users:** Para este scope, un campo `enum` es suficiente y evita joins innecesarios. Con más roles se migraría a una tabla `roles` con Spatie Permission.
- **Form Requests:** La validación está separada del controlador para mantenerlo limpio y reutilizable.

## Endpoints

### Públicos
| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | `/api/register` | Registro de usuario |
| POST | `/api/login` | Login |

### Protegidos (Bearer Token)
| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | `/api/logout` | Cerrar sesión |
| GET | `/api/me` | Usuario autenticado |
| GET | `/api/doctors` | Listado de médicos |
| GET | `/api/appointments` | Citas del usuario |
| POST | `/api/appointments` | Crear cita (paciente) |
| GET | `/api/appointments/{id}` | Detalle de cita |
| PUT | `/api/appointments/{id}` | Actualizar estado (médico) |
| DELETE | `/api/appointments/{id}` | Eliminar cita |

## Instalación local

### Requisitos
- Docker
- Docker Compose

### Pasos

```bash
# 1. Clona el repositorio
git clone https://github.com/AdrianRomeroRamirez/medqueue-api.git
cd medqueue-api

# 2. Levanta los contenedores
docker compose up -d --build

# 3. Copia el archivo de entorno
cp .env.example .env

# 4. Configura la base de datos en .env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=medqueue
DB_USERNAME=medqueue
DB_PASSWORD=secret

# 5. Genera la clave de la aplicación
docker compose exec app php artisan key:generate

# 6. Ejecuta las migraciones y seeders
docker compose exec app php artisan migrate --seed

# 7. La API estará disponible en http://localhost:8000
```

### Usuarios de prueba

| Nombre | Email | Password | Rol |
|--------|-------|----------|-----|
| Dr. García | garcia@medqueue.com | password | doctor |
| Dra. Martínez | martinez@medqueue.com | password | doctor |
| Adrian Romero | adrian@medqueue.com | password | patient |
| Laura Sánchez | laura@medqueue.com | password | patient |

### Procesar colas manualmente

```bash
docker compose exec app php artisan queue:work
```

## Qué mejoraría con más tiempo

- Tests con PHPUnit (feature tests por endpoint)
- Envío real de emails con Laravel Mail + AWS SES
- Paginación en el listado de citas
- Swagger/OpenAPI para documentación de la API
- Rate limiting por usuario
- Política de autorización con Laravel Gates para validar que el médico solo edita sus propias citas