# Veterinaria Itapebí

Aplicación PHP en migración gradual a MVC. Requiere PHP 8.1 o superior, MySQL/MariaDB y las extensiones mysqli, mbstring, dom e iconv. Composer ya configura `App\` → `src/`; no fue necesario regenerar el autoload versionado.

## Estado de la migración

Autenticación y especies usan controladores, modelos y vistas. Los archivos PHP originales de estos módulos delegan al punto de entrada MVC. Propietarios, mascotas, consultas, cuotas y vacunas mantienen su implementación anterior. No se migraron adjuntos ni PDF en esta etapa.

- `public/index.php`: punto de entrada MVC; selecciona rutas mediante `?route=especies`.
- `config/`: inicialización, rutas y conexión.
- `src/Controllers/`: peticiones, validación y respuestas.
- `src/Models/`: conexión y consultas preparadas.
- `src/Http/`: router, autenticación, CSRF y renderizado.
- `views/`: HTML; valores dinámicos escapados con `View::escape()`.
- `tests/mvc_smoke.py`: pruebas HTTP y persistencia con fixtures temporales.

Las rutas aceptan un método explícito. Altas, cambios, bajas y logout usan POST con token CSRF. La eliminación de especies conserva la baja lógica (`EspecieEstado=0`). Los enlaces antiguos de eliminación mediante GET devuelven 405. El login verifica usuarios activos, regenera la sesión y actualiza contraseñas SHA-1 a bcrypt tras una autenticación correcta. Todas las entradas de login del proyecto delegan al nuevo controlador.

## Desarrollo

Durante la transición, servir la raíz del checkout para que las pantallas heredadas sigan accesibles:

```sh
cd /workspace/veterinaria
php -S 127.0.0.1:8080 -t .
```

La pantalla de entrada está en `/index.php`; las rutas MVC en `/public/index.php?route=especies`. Las URLs actuales asumen instalación en la raíz del dominio. Este servidor es para desarrollo local. Cambiar el document root únicamente a `public/` requiere migrar las pantallas restantes, los enlaces y los recursos estáticos; todavía no es compatible con el proyecto completo.

En el entorno cloud preparado:

```sh
bash /workspace/.veterinaria-env/start.sh
PHP_BINARY=/workspace/.veterinaria-env/bin/php python3 tests/mvc_smoke.py
```

Para otros entornos, exportar `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`, y opcionalmente `DB_PORT` y `DB_SOCKET` antes de arrancar PHP. Los valores por defecto conservan localhost, root e itapebi para desarrollo local. No guardar contraseñas en el repositorio. Las pantallas heredadas todavía usan `includes/conexion.php`, de modo que los overrides de la conexión MVC no se aplican a ellas. `config/bootstrap.php` inicializa sesión y zona horaria America/Montevideo.

## Validación

Con una base de desarrollo disponible y el servidor iniciado:

```sh
PHP_BINARY=php MVC_BASE_URL=http://127.0.0.1:8080 python3 tests/mvc_smoke.py
```

El test crea un usuario y una especie con nombres únicos, verifica autenticación, migración de contraseña, métodos HTTP, CSRF, errores de validación, escape de HTML, alta/edición/baja lógica y compatibilidad. Elimina sus fixtures incluso ante una aserción fallida. Ejecutarlo contra una base de desarrollo donde se permitan estas escrituras, no producción.

## Pendientes

El SQL versionado tiene fechas inválidas y referencias a `usuarios` aunque crea `usuario`. No incluye la tabla `vacunas`. El entorno cloud tiene solo el esquema disponible, sin usuario permanente. Se necesita un usuario de desarrollo válido para entrar manualmente; las pruebas generan uno temporal. Estos problemas no se corrigen inventando tablas o datos en la migración MVC.

Continuar migrando propietarios y mascotas antes de consultas. Añadir servicios reales de adjuntos y PDF cuando se migren esos flujos; no se agregaron clases vacías. Separar esquema y seeds válidos, recuperar vacunas y completar la migración de recursos a `public/`. Las acciones heredadas de los módulos pendientes aún requieren aplicar los controles de autenticación, CSRF y validación del nuevo router.
