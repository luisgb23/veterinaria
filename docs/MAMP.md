# Instalación en MAMP (Mac)

1. Descargar el ZIP del proyecto y descomprimirlo. Renombrar la carpeta extraída a `veterinaria`.
2. Colocar **la carpeta completa**, incluidos vendor, public, config, src, views, css, js, img, database y `.htaccess`, en `/Applications/MAMP/htdocs/veterinaria` (o el htdocs que hayas elegido en MAMP). No mover solamente public.
3. En MAMP, usar Apache, PHP 8.1 o superior y el document root `htdocs`. Iniciar Apache y MySQL. Verificar `mysqli`, `mbstring`, `fileinfo`, `dom` e `iconv` en el PHP seleccionado.
4. Copiar `config/local.example.php` a `config/local.php`. Revisar puerto MySQL, usuario, contraseña y nombre de base con los valores reales de tu MAMP. El puerto de ejemplo es 8889, pero no se asume que sea tu puerto. La contraseña de ejemplo está vacía: completarla localmente cuando corresponda. No subir `local.php` a Git.
5. Crear/importar la base `itapebi` con tu esquema y datos válidos. El dump antiguo no es una importación limpia; conserva los defectos ya documentados. Ejecutar la migración de vacunas si falta esa tabla con el PHP de MAMP, por ejemplo:

```sh
cd /Applications/MAMP/htdocs/veterinaria
/Applications/MAMP/bin/php/phpX.Y.Z/bin/php database/migrate.php
```

Sustituir `phpX.Y.Z` por la carpeta de la versión instalada, visible bajo `/Applications/MAMP/bin/php/`.

6. Abrir `http://localhost:8888/veterinaria/`, usando el puerto Apache real de MAMP. API: `http://localhost:8888/veterinaria/api/v1/auth/csrf`.

Las URLs de formularios, CSS, JS, imágenes, PDF, descargas y respuestas de la API detectan `/veterinaria` a partir del document root. También funcionan en la raíz de un virtual host, sin modificar el código. Para alias o configuraciones especiales, fijar `'base_path' => '/veterinaria'` en `config/local.php` (o `APP_BASE_PATH`), o `''` si el sitio ocupa la raíz.

La configuración local también permite declarar `cors_allowed_origins` para React. Las variables DB_* y CORS_ALLOWED_ORIGINS del proceso, si existen, tienen prioridad sobre local.php. No hace falta exportar variables desde una terminal para que Apache lea esta configuración.

## Apache y archivos

`.htaccess` ya usa reglas relativas compatibles con la subcarpeta; no requiere escribir una ruta fija en RewriteBase. Apache necesita mod_rewrite y AllowOverride apropiado para aplicarlas. Conservar `.htaccess` en el ZIP; en Finder puedes mostrar archivos ocultos con Cmd+Shift+.

Si el login funciona pero `/veterinaria/api/v1/auth/csrf` devuelve un 404 de Apache, revisar mod_rewrite/AllowOverride. Si aparece un 500, revisar el log de Apache/PHP. Si no conecta a MySQL, confirmar host `127.0.0.1`, puerto y credenciales en local.php: esa dirección usa TCP y evita sockets de otra instalación PHP.

PHP debe poder escribir en storage/uploads; para adjuntos configurar upload_max_filesize=5M y post_max_size=17M en el php.ini de MAMP, y reiniciar sus servidores. Respaldar storage/uploads y archivos junto con la base de datos. Al actualizar desde otro ZIP, conservar config/local.php y los archivos subidos.

## Desarrollo desde terminal

El comando original sigue funcionando desde la carpeta del proyecto:

```sh
php -S localhost:8000 -t . public/dev-router.php
```

En ese caso la URL es `http://localhost:8000/` y la API `/api/v1`. Si sirves el htdocs padre con el router del proyecto, la URL será `/veterinaria/`. El cliente de ejemplo docs/react-api-client.js usa `/veterinaria/api/v1`; cambiarlo a `/api/v1` únicamente si React se conecta al despliegue en la raíz.
