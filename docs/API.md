# API v1 para React

Base: `/api/v1` en la raíz, `/veterinaria/api/v1` en MAMP con htdocs/veterinaria. Contrato OpenAPI 3.0: `docs/openapi.json` (importable en Swagger Editor/Postman). El frontend React no está incluido: esta entrega prepara el backend.

## Arranque

Con la base del proyecto y migración de vacunas preparada, desde la raíz:

```sh
php database/migrate.php
php -S localhost:8000 -t . public/dev-router.php
```

En Apache se usan las reglas `.htaccess`. Para Nginx, enviar `/api/v1/*` a `public/api.php` conservando REQUEST_URI, además de los bloqueos de directorios privados descritos en README. No servir esta API con `php -S ...` sin el router: las rutas REST no funcionarán. Los formularios PHP actuales continúan operativos.

## Sesión y CSRF

1. `GET /auth/csrf` establece la cookie de sesión y devuelve `data.csrf_token`.
2. `POST /auth/login` con JSON `{ "usuario": "...", "password": "..." }` y encabezado `X-CSRF-Token`.
3. Guardar el nuevo `csrf_token` del login; la sesión y el token se renuevan.
4. Enviar la cookie en todas las peticiones (`credentials: 'include'`). Los POST/PATCH/DELETE requieren `X-CSRF-Token`.
5. `GET /auth/me` devuelve `{ data: { id, nombre, usuario } }`. `POST /auth/logout` devuelve 204.

Nunca guardar la contraseña ni un token de sesión en localStorage. La cookie es HttpOnly, SameSite=Lax y Secure cuando PHP detecta HTTPS. En producción usar HTTPS; si hay proxy inverso, configurar correctamente HTTPS en PHP.

Todos los usuarios activos autenticados tienen acceso al mismo conjunto de datos, igual que en la aplicación existente. No incluye roles, separación por clínica ni login de servicios externos. Estos requerimientos necesitan autorización y autenticación específicas antes de abrirla a terceros.

## Rutas de entidades

Para `especies`, `propietarios`, `mascotas`, `consultas`, `vacunas`, `cuotas`:

| Método | Ruta | Resultado |
|---|---|---|
| GET | `/{entidad}` | Lista paginada |
| GET | `/{entidad}/{id}` | Detalle activo |
| POST | `/{entidad}` | Crear (201 y Location) |
| PATCH | `/{entidad}/{id}` | Modificar los campos enviados (200) |
| DELETE | `/{entidad}/{id}` | Baja lógica (204) |

Los cuerpos de POST y PATCH son objetos JSON con `Content-Type: application/json`. Campos desconocidos se rechazan; PATCH vacío es inválido. Relaciones nuevas deben apuntar a registros activos; se permite conservar una relación existente inactiva. Cuotas mantiene importes enteros como el esquema original; `valor` puede enviarse como string numérico o entero y se devuelve como string para evitar pérdida de precisión en JavaScript.

| Entidad | Campos públicos |
|---|---|
| especies | nombre |
| propietarios | nombre, apellido, telefono, direccion, email |
| mascotas | nombre, especie_id, propietario_id, fecha_nacimiento |
| cuotas | fecha, mascota_id, valor, observacion |
| consultas | fecha, mascota_id, motivo, diagnostico, tratamiento |
| vacunas | fecha_ingreso, mascota_id, fecha_vencimiento |

Fechas: YYYY-MM-DD. Teléfono, dirección, email, observación, diagnóstico y tratamiento son opcionales. Especies/propietarios/mascotas incluyen nombre; apellido es obligatorio en propietarios. Los demás campos de fecha/relación son obligatorios, así como motivo e importe en sus módulos. Las respuestas incluyen `id`, campos públicos, relaciones resumidas (`especie`, `propietario`, `mascota`) y slots de `adjuntos` en consultas, sin exponer rutas locales de archivos o contraseñas.

Listados: `page` (default 1), `per_page` (default 20, máximo 100), `buscar` en campos de texto y filtros de relación (`mascota_id`, `especie_id`, `propietario_id` según entidad). Cuotas/consultas filtran `desde` y `hasta` por su fecha; vacunas por vencimiento. `vencidas=1` selecciona fechas anteriores a hoy, `0` las demás. No incluye registros dados de baja.

```text
GET /api/v1/mascotas?propietario_id=12&page=1&per_page=20
GET /api/v1/consultas?mascota_id=24&desde=2026-01-01
GET /api/v1/vacunas?vencidas=1
```

```json
{
  "data": [{ "id": 24, "nombre": "Luna", "especie_id": 5, "propietario_id": 12, "fecha_nacimiento": "2020-06-15", "especie": { "id": 5, "nombre": "Canino" }, "propietario": { "id": 12, "nombre": "Ana", "apellido": "Pérez" } }],
  "meta": { "page": 1, "per_page": 20, "total": 1, "last_page": 1 }
}
```

## Reportes y archivos

- `GET /dashboard/resumen`: conteos activos y `vacunas_vencidas`.
- `GET /vacunas/vencimientos`: histórico activo paginado y ordenado por vencimiento; filtros `mascota_id`, `desde`, `hasta`, `vencidas`, `page`, `per_page`; añade teléfono del propietario y flag `vencida`.
- `GET /consultas/{id}/pdf`: PDF binario autenticado.
- `POST /consultas/{id}/adjuntos`: multipart con `archivo` y `slot` (1–3), más CSRF en el encabezado. PDF/JPG/PNG de hasta 5 MB. No fijar Content-Type manualmente desde fetch cuando se use FormData.
- `GET /consultas/{id}/adjuntos/{slot}`: descarga binaria autenticada.
- `DELETE /consultas/{id}/adjuntos/{slot}`: elimina la referencia del slot (204). El archivo físico se conserva, como al reemplazar archivos, para no perder datos; respaldar y gestionar la retención de storage aparte.

Los PDF y descargas pueden abrirse en otra pestaña si se sirven en el mismo origen. Con origen distinto, usar fetch con credentials y convertir la respuesta en Blob.

## Errores

400 JSON inválido, 401 sin sesión o credenciales inválidas, 403 CSRF/origen rechazado, 404 registro/ruta no encontrada, 405 método incorrecto, 413 JSON superior a 1 MB, 415 tipo de cuerpo incorrecto, 422 validación, 500 error interno registrado en la terminal de PHP. Los detalles SQL y trazas nunca se devuelven al cliente.

```json
{ "message": "Revisa los datos enviados.", "errors": { "fecha_nacimiento": ["La fecha de nacimiento no puede ser futura."] } }
```

## React y CORS

Preferir mismo origen mediante un proxy de Vite en desarrollo y un proxy web en producción. Si React accede directamente desde otro puerto, exportar antes de arrancar PHP:

```powershell
$env:CORS_ALLOWED_ORIGINS="http://localhost:5173"
php -S localhost:8000 -t . public/dev-router.php
```

En Bash: `CORS_ALLOWED_ORIGINS=http://localhost:5173 php -S localhost:8000 -t . public/dev-router.php`. Lista de orígenes exactos separados por comas, sin barras finales; no se admite wildcard. PHP permite credentials y OPTIONS únicamente para los orígenes autorizados. Usar el mismo hostname (`localhost` en ambos), no mezclar localhost y 127.0.0.1. Para dominios de sitios diferentes, SameSite=Lax bloquea cookies: usar mismo origen o un despliegue compatible, no basta añadir CORS.

Ejemplo de cliente JavaScript en `docs/react-api-client.js`. Importarlo en React, configurar API_BASE y llamar a login antes de las operaciones protegidas. El cliente conserva CSRF solo en memoria y maneja respuestas 204.

## Pruebas

```sh
PHP_BINARY=php MVC_BASE_URL=http://localhost:8000 python3 tests/api_smoke.py
```

Ejecutar sobre una base de desarrollo. Crea usuario y registros aislados, comprueba las seis entidades, login/CSRF, JSON/errores, filtros/paginación, PDF, upload/download/delete de archivos y bajas; elimina sus fixtures al finalizar. Las pruebas MVC originales siguen disponibles en `tests/entities_smoke.py`.

Para MAMP, ver [la guía de instalación](MAMP.md). Las respuestas Location y URLs de adjuntos incorporan el prefijo de instalación. El cliente JavaScript de ejemplo usa `/veterinaria/api/v1`; cambiar API_BASE si usas un virtual host en la raíz.
