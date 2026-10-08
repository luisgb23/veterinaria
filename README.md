# Veterinaria Itapebí — MVC

Aplicación PHP con autenticación, inicio, especies, propietarios, mascotas, cuotas, consultas, vacunas e histórico migrados a MVC. Conserva la navegación lateral y el login responsive. Requiere PHP 8.1+, mysqli, mbstring, fileinfo, dom, iconv y MySQL/MariaDB. Dompdf y el autoload PSR-4 están incluidos en vendor.

## Estructura

- `public/index.php`: punto de entrada; rutas explícitas en `config/routes.php`.
- `config/entities.php`: campos, relaciones y validaciones de cada módulo; identificadores SQL solo desde esta configuración.
- `src/Controllers/`: controlador por entidad y comportamiento CRUD compartido.
- `src/Models/`: conexión y consultas preparadas.
- `src/Services/`: almacenamiento de adjuntos y generación de PDF.
- `src/Http/`: router, autenticación, CSRF, renderizado.
- `views/`: vistas compartidas, login, navegación, formularios, detalles, listados y PDF.
- `database/migrations/`: cambios de esquema aditivos.
- `tests/`: pruebas HTTP con fixtures aislados que se eliminan al finalizar.

Los archivos PHP originales delegan a rutas MVC para preservar las URLs. Las escrituras y el logout son POST con CSRF; las eliminaciones conservan la baja lógica. Las relaciones nuevas deben existir y estar activas; una relación ya inactiva se puede conservar al editar. Las contraseñas SHA-1 se actualizan a bcrypt al iniciar sesión correctamente. No se crean usuarios o contraseñas permanentes automáticamente.

## Preparación y ejecución

Exportar `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`, opcionalmente `DB_PORT` y `DB_SOCKET`. Por defecto se mantiene localhost/root/itapebi para desarrollo local. No guardar credenciales en Git. La zona horaria es America/Montevideo.

La base existente debe tener especies, propietarios, mascotas, cuotas, consultas y usuario. El volcado original tiene fechas inválidas y una referencia a usuarios en vez de usuario; no se carga automáticamente. Usar un esquema y datos de desarrollo válidos.

La tabla vacunas faltaba: la migración reconstruye los campos utilizados por el código anterior, añade timestamps y una relación con mascotas. No sustituye tablas existentes ni sobrescribe datos; si ya existe una tabla vacunas, conserva su esquema y datos. El modelo admite tablas antiguas sin VacunaFchCreacion o VacunaFchModificacion; los campos de ingreso, vencimiento, mascota, identificador y estado siguen siendo necesarios. Ejecutar explícitamente:

```sh
php database/migrate.php
php -S 127.0.0.1:8080 -t . public/dev-router.php
```

Mantener la raíz del proyecto como document root durante la transición de assets. El router de desarrollo bloquea acceso HTTP a configuración, SQL, dependencias, adjuntos y directorios internos. En Apache, `.htaccess` requiere mod_rewrite y AllowOverride apropiado. En Nginx configurar bloqueos equivalentes antes de publicar. No arrancar el servidor de desarrollo sin el router: PHP no aplica `.htaccess`.

En el entorno cloud preparado:

```sh
/workspace/.veterinaria-env/bin/php database/migrate.php
bash /workspace/.veterinaria-env/start.sh
PHP_BINARY=/workspace/.veterinaria-env/bin/php python3 tests/entities_smoke.py
```

## Adjuntos y PDF

Las consultas admiten tres archivos opcionales PDF/JPG/PNG de hasta 5 MB cada uno, con validación del MIME real y nombres aleatorios. Se guardan en `storage/uploads/` (ignorado por Git). Un formulario sin archivo mantiene el adjunto existente. Reemplazar un archivo conserva el anterior en almacenamiento; no se purga automáticamente para evitar pérdidas. Los archivos antiguos de `archivos/` siguen disponibles mediante la descarga autenticada. Respaldar ambos directorios junto con la base de datos.

Las descargas requieren sesión, una consulta activa y un slot válido. Los PDF se renderizan con vistas escapadas, sin recursos remotos. La carpeta de uploads debe ser escribible por PHP. Ajustar `upload_max_filesize` y `post_max_size` para admitir los archivos necesarios; usar `post_max_size` superior al total de los tres adjuntos.

## Pruebas

Con servidor y base de desarrollo disponibles:

```sh
PHP_BINARY=php MVC_BASE_URL=http://127.0.0.1:8080 python3 tests/entities_smoke.py
```

Ejecuta también la suite inicial de autenticación y especies. Comprueba CRUD de todas las entidades, validación de fechas/importes/relaciones, métodos y CSRF, escape HTML, bajas lógicas, migración de contraseña, URLs originales, histórico, PDF, conservación y descarga de adjuntos y rechazo de archivos ejecutables. Crea y elimina fixtures; requiere permiso de escritura y no debe ejecutarse contra producción.

Los helpers `test.php` y `testAgregar.php` son demostraciones heredadas, no forman parte del flujo MVC ni de la suite; no deben desplegarse públicamente. Usuarios se administran fuera de estas pantallas; la autenticación está migrada, pero no se añadió una pantalla de administración de cuentas.

Prueba de compatibilidad con vacunas antiguas (usa tablas temporales, sin cambiar los datos existentes):

```sh
php tests/vacunas_legacy.php
```

## PDF y versión de PHP

Dompdf está actualizado a 3.1.6, junto con php-font-lib y php-svg-lib. Si usas una copia anterior y aparecen avisos Deprecated al abrir un PDF, actualizar también vendor o ejecutar `composer install` con el composer.lock actualizado. No editar archivos individuales dentro de vendor ni desactivar los avisos para corregir esta incompatibilidad.

```sh
php tests/pdf_smoke.php
```

Esta prueba activa E_ALL y convierte los avisos en excepciones antes de verificar el contenido PDF. Para diagnosticar extensiones faltantes: `composer check-platform-reqs`; para los adjuntos se necesita además fileinfo (`php -r "var_dump(class_exists('finfo'));"`).
