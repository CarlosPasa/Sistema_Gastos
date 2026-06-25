# 💰 Sistema de Gestión de Gastos

Sistema moderno para la administración de gastos personales desarrollado con Laravel 12 siguiendo buenas prácticas de desarrollo, arquitectura por servicios y preparado para ejecutarse mediante Docker.

---

# 🚀 Tecnologías

- Laravel 12
- PHP 8.3
- MySQL 8
- TailwindCSS
- Livewire
- Sanctum
- Docker
- Docker Compose
- Nginx
- Node.js + Vite
- PhpMyAdmin

---

# ✨ Características

## Autenticación

- Login
- Logout
- Protección mediante middleware
- API Authentication con Laravel Sanctum

---

## Categorías

- Crear categorías
- Editar categorías
- Eliminar categorías
- Validaciones

---

## Gastos

- Registro de gastos
- Edición
- Eliminación
- Filtros por mes
- Filtros por año

---

## Presupuestos

- Presupuesto por categoría
- Comparación presupuesto vs gasto
- Alertas de sobreconsumo

---

## Dashboard

- Total gastado
- Total presupuestado
- Balance
- Últimos gastos
- Indicadores
- Gráfico de torta por categorías

---

## Reportes

- Exportación a Excel
- Exportación a PDF

---

## API REST

Endpoints protegidos mediante Sanctum.

```
POST   /api/login
POST   /api/logout

GET    /api/user

GET    /api/categories
POST   /api/categories
PUT    /api/categories/{id}
DELETE /api/categories/{id}

GET    /api/expenses
POST   /api/expenses
PUT    /api/expenses/{id}
DELETE /api/expenses/{id}

GET    /api/budgets
POST   /api/budgets
PUT    /api/budgets/{id}
DELETE /api/budgets/{id}
```

---

# 🐳 Docker

El proyecto incluye un entorno completo de desarrollo mediante Docker.

Servicios incluidos:

- Laravel
- PHP-FPM
- Nginx
- MySQL
- Node (Vite)
- PhpMyAdmin

## Levantar proyecto

```bash
docker compose up -d --build
```

---

## Detener proyecto

```bash
docker compose down
```

---

## Reconstruir imágenes

```bash
docker compose up -d --build
```

---

## Ejecutar comandos Artisan

```bash
docker exec -it sistema_gastos_app php artisan migrate
```

```bash
docker exec -it sistema_gastos_app php artisan db:seed
```

---

## URLs

Aplicación

```
http://localhost:8000
```

PhpMyAdmin

```
http://localhost:8080
```

Vite

```
http://localhost:5173
```

---

# Base de datos

Al iniciar el contenedor:

- espera que MySQL esté disponible
- ejecuta migraciones automáticamente
- ejecuta seeders automáticamente

---

# Usuario Demo

```
Email:
demo@test.com

Password:
12345678
```

---

# Ejecutar Tests

```bash
php artisan test
```

o

```bash
docker exec -it sistema_gastos_app php artisan test
```

---

# Arquitectura

El proyecto utiliza una arquitectura basada en servicios.

```
Controllers
      │
      ▼
Services
      │
      ▼
Models
      │
      ▼
Database
```

Se siguen principios de:

- Clean Code
- SOLID
- Separación de responsabilidades

---

# Roadmap

## ✅ Fase 1

- Login
- Usuarios
- Roles
- Categorías
- Gastos

## ✅ Fase 2

- Dashboard
- Indicadores
- Gráficos
- Presupuestos

## ✅ Fase 3

- Exportación PDF
- Exportación Excel
- API REST
- Docker

## 🔜 Fase 4

- ETLs con Python
- Dashboard Power BI
- Indicadores Financieros
- Deploy en la nube

---

# Autor

Desarrollado como proyecto de portafolio utilizando Laravel 12 y Docker.
