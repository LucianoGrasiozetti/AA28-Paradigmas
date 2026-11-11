# Concurso de disfraces de Halloween

API REST en PHP (PDO) + MySQL, frontend en HTML/CSS/JS vanilla.

## Instalación en XAMPP
1. Copiá la carpeta `halloween` en `C:\xampp\htdocs\`.
2. Iniciá **Apache** y **MySQL** desde el panel de XAMPP.
3. En phpMyAdmin, importá `database/halloween.sql`.
4. Abrí http://localhost/halloween/public/

## Usuario administrador
- Usuario: `admin`
- Contraseña: `Admin123!`

## Estructura
- `public/` frontend · `api/` endpoints JSON · `config/` conexión · `includes/` helpers y autenticación · `database/` script SQL

## Paso 1 · Base de datos
`database/halloween.sql` crea la base `halloween` con las tablas `disfraces`, `usuarios` y `votos`
(claves primarias, `AUTO_INCREMENT`, `UNIQUE` para evitar votos duplicados y usuarios repetidos)
y el usuario administrador. Se agregó `disfraces.id_usuario` para saber quién subió cada disfraz.

## Paso 3 · Personalización
- **3a. Estilo Halloween:** `public/css/estilos.css` (fondo violeta, naranja calabaza y amarillo, sin animaciones, contraste AA).
- **3b. Subir disfraces:** cualquier usuario registrado puede subir foto y descripción desde `public/subir.html`.
  El administrador sigue gestionando todos los disfraces (editar y eliminar) en `admin.html`.

## Calidad
`informe/FICHA_CALIDAD_ISO25010.md` (matriz ISO 25010) e `informe/informe-calidad.pdf` (informe con campos para completar: integrantes, puntajes de Lighthouse y capturas).
