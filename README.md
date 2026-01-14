# ✅ Gestor de Tareas PRO (To-Do App)
**HTML + Bootstrap 5 + JavaScript + PHP + MySQL/MariaDB**

Aplicación tipo To-Do “nivel portafolio” con:
- Prioridades (alta/media/baja)
- Fechas límite
- Filtros por estado y fecha
- Estadísticas (pendientes/completadas/vencidas/para hoy)
- Persistencia real en **MySQL** (y respaldo opcional en **LocalStorage** si el backend no está disponible)

---

## ✨ Funcionalidades

### 🧾 Tareas
- Agregar tareas
- Marcar como completadas / pendientes
- Eliminar tareas

### ⭐ Prioridad
- **Alta / Media / Baja**
- Visual con badges

### 📅 Fecha límite
- Campo opcional `due_date`
- Etiqueta “Vencida” si:
  - tarea pendiente
  - due_date < hoy

### 🔎 Filtros
- **Estado**: todas / pendientes / completadas
- **Prioridad**: alta / media / baja
- **Fecha**: para hoy / vencidas / sin fecha
- Búsqueda por texto

### 📊 Estadísticas
- Total tareas
- Pendientes
- Completadas
- Vencidas
- Para hoy

---

## 🧱 Tecnologías
- PHP 8+
- MySQL/MariaDB (XAMPP recomendado)
- JavaScript (Fetch API)
- Bootstrap 5 (CDN)

---

## 📁 Estructura del proyecto

todo-app/
  config/
    db.php
  api/
    tasks_list.php
    tasks_create.php
    tasks_toggle.php
    tasks_delete.php
  public/
    index.php
    assets/
      app.js
  sql/
    schema.sql
README.md
LICENSE


---

## ✅ Instalación (XAMPP)

### 1) Copiar el proyecto
Coloca la carpeta en:
C:\xampp\htdocs\todo-app


### 2) Crear e importar la base de datos
En phpMyAdmin:

1. Crea una base:
   - `todo_app`
2. Importa el archivo:
   - `sql/schema.sql`

> Nota: el schema incluye datos demo.

### 3) Configurar conexión DB
Edita `config/db.php`:

```php
$DB_HOST = "localhost";
$DB_NAME = "todo_app";
$DB_USER = "root";
$DB_PASS = "";

### 4) Abrir la app
En el navegador:
http://localhost/todo-app/public/index.php
