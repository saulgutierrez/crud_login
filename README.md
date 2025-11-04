# 🗨️ Foro Social — Proyecto Final de Ingeniería Informática

## 📘 Descripción general

Este proyecto consiste en el desarrollo de un **foro social interactivo**, cuyo objetivo es permitir la **interacción dinámica entre usuarios** mediante la publicación de contenido, comentarios, reacciones, seguimiento y bloqueo de usuarios.  
El sistema está construido utilizando **PHP**, **AJAX**, **jQuery**, **JavaScript** y **MySQL**, aplicando una arquitectura modular orientada a la escalabilidad y la experiencia de usuario.

El proyecto fue desarrollado como **trabajo final de grado** para optar al título de **Ingeniero en Informática**, y tiene como propósito demostrar la aplicación de técnicas modernas de desarrollo web, comunicación asíncrona y manejo de datos en tiempo real.

---

## ⚙️ Características principales

- **Registro e inicio de sesión de usuarios.**  
- **Gestión de perfil:** edición de información personal, cambio o eliminación de la foto de perfil (respetando la imagen por defecto).  
- **Creación de posteos** con título, contenido y foto opcional.  
- **Sistema de comentarios**, que permite incluir texto y/o imagen.  
- **Reacciones ("likes")** a los posteos de otros usuarios.  
- **Funcionalidad de seguimiento** (seguir/dejar de seguir usuarios).  
- **Bloqueo de usuarios** para restringir interacciones no deseadas.  
- **Notificaciones** de acciones de los usuarios seguidos.  
- **Eliminación de datos relacionados**, asegurando la integridad de la base de datos y la limpieza del servidor (por ejemplo, al eliminar un usuario, también se eliminan sus posteos, comentarios e imágenes).  
- **Galería de imágenes interactiva** con **PhotoSwipe**, para visualizar fotos en pantalla completa.  
- **Interfaz dinámica sin recargas completas**, gracias al uso de **AJAX** y **fetch API**.  

---

## 🧩 Arquitectura del sistema

El sistema sigue una estructura modular basada en el patrón **MVC (Modelo–Vista–Controlador)**:

- **Modelo (Model):**  
  Contiene la lógica de conexión y consultas a la base de datos MySQL.  
- **Vista (View):**  
  Archivos PHP/HTML encargados de mostrar la información al usuario final.  
- **Controlador (Controller):**  
  Archivos PHP que gestionan las solicitudes del usuario, procesan la lógica del negocio y devuelven respuestas JSON o HTML parcial para las peticiones AJAX.

---

## 🗃️ Estructura general del proyecto
  ```bash
├───config
├───docs
├───public
│   ├───css
│   ├───icons
│   ├───img
│   ├───js
│   ├───resources
│   └───svg
└───src
    ├───handlers
    ├───models
    ├───ui
    └───views
        ├───includes
        └───uploads

  ```
---
## 💻 Tecnologías utilizadas
| Categoría | Tecnología |
|------------|-------------|
| **Backend** | PHP 8+, MySQL |
| **Frontend** | HTML5, CSS3, JavaScript, jQuery, Bootstrap |
| **Comunicación asíncrona** | AJAX, Fetch API |
| **Librerías** | PhotoSwipe (galería de imágenes), Intense.js, TinyMCE. date-fns |
| **Control de versiones** | Git / GitHub |
| **Arquitectura** | MVC |
---
## ⚙️ Instalación y configuración local
Sigue estos pasos para ejecutar el proyecto en tu entorno local (ej. **XAMPP**, **Laragon** o **Railway**):
### 1️⃣ Clonar el repositorio
```bash
git clone https://github.com/saulgutierrez/crud_login.git
```
### 2️⃣ Crear la base de datos
1. Abre phpMyAdmin o tu herramienta de gestión de MySQL.
2. Crea una base de datos llamada crud_login:
```sql
CREATE DATABASE crud_login CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```
3. Importa el archivo .sql incluido en el proyecto (dentro de /docs/crud_login.sql).
### 3️⃣ Configurar la conexión a la base de datos
Edita el archivo de conexión, ubicado en:
```bash
/includes/connection.php
```
Y ajusta los valores según tu entorno local:
```php
$host = "localhost";
$user = "root";
$password = "";
$database = "crud_login";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}
```
### 4️⃣ Iniciar el servidor local
Ejemplo con XAMPP:
- Asegúrate de que los módulos Apache y MySQL estén activos.
- Accede al proyecto en tu navegador:
```bash
http://localhost/crud_login/
```
### 5️⃣ Credenciales iniciales

Algunos archivos SQL incluyen usuarios de prueba.
Puedes iniciar sesión con una cuenta de ejemplo o registrar un nuevo usuario en la interfaz principal.

---
## 🚀 Funcionalidades destacadas
- **AJAX sin recarga:** todas las acciones (like, comentar, seguir, eliminar, etc.) se procesan en segundo plano sin recargar la página.
- **Seguridad:** validaciones de datos, control de sesión y eliminación de datos relacionados al eliminar cuentas.
- **Experiencia de usuario:** botones dinámicos con iconos persistentes, modales personalizados y navegación fluida.
- **Gestión multimedia:** control sobre imágenes subidas por usuarios, con eliminación automática del servidor al borrar contenido.
---
## 🧠 Posibles mejoras futuras
- Implementar notificaciones en tiempo real mediante WebSockets.
- Integrar un sistema de mensajería privada entre usuarios.
- Agregar paginación dinámica en listas de posteos y comentarios.
- Desarrollar una API REST para futuras integraciones móviles.

---
## 🧾 Autor
Saúl Gutiérrez <br>
Estudiante de Informática <br>
Proyecto final de carrera — Foro Social Interactivo
