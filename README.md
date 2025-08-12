# miweb-Backend

Backend de la web **CWM**, desarrollado con **[Slim PHP](https://www.slimframework.com/)**.  
Gestiona el formulario de contacto, guarda mensajes en la base de datos y envía notificaciones por correo.

🚀 Ideal para proyectos pequeños y medianos que necesitan una API ligera y eficiente.

---

## ✅ Funcionalidades

- ✅ API REST para recibir mensajes de contacto
- ✅ Persistencia en base de datos MySQL (tabla `mensajes`)
- ✅ Envío automático de correos con **PHPMailer**
- ✅ Validación básica de datos
- ✅ Configuración segura con variables de entorno
- ✅ Listo para conectar con un frontend en React

---

## 🛠️ Tecnologías usadas

- **PHP 8+**
- **Slim 4** – Framework ligero para APIs
- **PDO** – Conexión a base de datos
- **PHPMailer** – Envío de correos
- **vlucas/phpdotenv** – Gestión de variables de entorno
- **Composer** – Gestión de dependencias

---

## 📦 Instalación

### 1. Clona el repositorio
```bash
git clone https://github.com/gpalomino1/miweb-Backend.git
cd miweb-Backend
2. Instala las dependencias
composer install
Crea el archivo .env
cp env.example .env

Edita .env con tus datos:
MAILER_USERNAME=infocodewm@gmail.com
MAILER_PASSWORD=Cohe2021

DB_HOST=127.0.0.1
DB_NAME=mi_web
DB_USER=xxxxxx
DB_PASS=xxxxxx

4. Crea la base de datos
Asegúrate de tener una base de datos llamada mi_web y ejecuta este SQL para crear la tabla:
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);

5. Inicia el servidor
php -S localhost:8080 -t public
✅ Tu API estará disponible en:
👉 http://localhost:8080

🌐 Endpoints
POST /api/contacto
Recibe un mensaje del formulario y:

Lo guarda en la base de datos
Envía un correo a infocodewm@gmail.com
Ejemplo de solicitud (JSON):

{
  "nombre": "Ana",
  "email": "ana@email.com",
  "mensaje": "Hola, quiero contactaros."
}
Respuesta:

{
  "status": "ok",
  "message": "Hemos recibido tu mensaje. Nos pondremos en contacto pronto."
}
🔒 Seguridad
Las contraseñas y datos sensibles se gestionan con .env (ignorado en Git).
Se usa phpdotenv para cargar variables de entorno.
El archivo env.example sirve como plantilla para nuevos desarrolladores.
🚀 Despliegue
Este backend puede desplegarse en cualquier servidor con PHP y MySQL, como:

000webhost
Hostinger
Railway
Render
VPS (DigitalOcean, etc.)
🙌 Autor
Desarrollado por Gemma Palomino
📧 infocodewm@gmail.com

📂 Frontend asociado
Este backend funciona con un frontend en React:
👉 https://github.com/gpalomino1/miweb-Frontend 
