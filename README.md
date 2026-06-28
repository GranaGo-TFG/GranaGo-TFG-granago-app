# GranaGO

GranaGO es una aplicacion web desarrollada como proyecto de TFG de DAW. La idea principal es descubrir Granada mediante retos urbanos, gamificacion y comunidad.

El usuario puede consultar retos, ver sus detalles, subir una prueba, ganar puntos, aparecer en el ranking y compartir publicaciones con otros usuarios. El proyecto esta planteado como un MVP academico, pero con una base preparada para crecer con nuevos retos, planes, tienda, logros y gestion administrativa.

## Funcionalidades principales

- Registro e inicio de sesion de usuarios.
- Roles diferenciados: usuario, creador y administrador.
- Home con mapa, resumen de actividad y acceso a retos.
- Listado de retos con filtros por estado, busqueda y ordenacion.
- Detalle de reto con imagen, descripcion, puntos, ubicacion e historia/leyenda opcional.
- Subida de pruebas mediante imagen.
- Validacion de pruebas por parte del administrador.
- Ranking de usuarios ordenado por puntos.
- Perfil de usuario y edicion de datos.
- Comunidad con publicaciones, imagenes, comentarios y me gusta.
- Logros visibles para el usuario.
- Tienda y planes como propuesta de monetizacion.
- Panel de administracion para gestionar retos, validaciones, usuarios, productos y logros.
- Paginas legales: privacidad, aviso legal y contacto.
- Modo claro y modo oscuro.
- Diseno responsive con enfoque mobile-first.

## Tecnologias

### Backend

- PHP 8.3
- Laravel 13
- Laravel UI para autenticacion
- MySQL como base de datos principal
- Eloquent ORM
- Middleware para control de acceso por rol
- PHPUnit para tests

### Frontend

- Blade
- Bootstrap 5
- SCSS
- JavaScript
- Vite
- Leaflet.js para el mapa

### Desarrollo local

- XAMPP / phpMyAdmin para gestionar MySQL en local
- Composer para dependencias PHP
- npm para dependencias frontend
- Git y GitHub para control de versiones

## Instalacion local

Clonar el repositorio e instalar dependencias:

```bash
composer install
npm install
```

Crear el archivo de entorno:

```bash
cp .env.example .env
php artisan key:generate
```

Configurar la base de datos en `.env`. Ejemplo para MySQL local:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=GranaGo
DB_USERNAME=root
DB_PASSWORD=
```

Ejecutar migraciones y seeders:

```bash
php artisan migrate --seed
```

Crear el enlace para que se vean las imagenes subidas desde formularios:

```bash
php artisan storage:link
```

Compilar assets:

```bash
npm run build
```

Levantar el servidor local:

```bash
php artisan serve
```

La aplicacion quedara disponible normalmente en:

```text
http://127.0.0.1:8000
```

## Comandos utiles

Servidor Laravel:

```bash
php artisan serve
```

Vite en modo desarrollo:

```bash
npm run dev
```

Compilar estilos y scripts:

```bash
npm run build
```

Ejecutar tests:

```bash
php artisan test
```

Limpiar caches:

```bash
php artisan optimize:clear
```

## Tests

El proyecto incluye una primera bateria de tests en la carpeta `tests/`.

Actualmente se comprueban partes importantes como:

- Ranking ordenado por puntos.
- Exclusion de administradores, creadores y usuarios baneados del ranking.
- Visualizacion de retos publicados y caducados.
- Filtros de retos por estado y busqueda.
- Creacion de retos por usuarios creadores en estado borrador.
- Bloqueo de creacion de retos para usuarios normales.
- Nombre publico del usuario usando nickname o nombre real.

Los tests usan la configuracion de `phpunit.xml`, con SQLite en memoria para no afectar a la base de datos real de desarrollo.

## Estructura principal

```text
app/Http/Controllers      Controladores de la aplicacion
app/Models                Modelos Eloquent
database/migrations       Estructura de la base de datos
database/seeders          Datos de prueba
resources/views           Vistas Blade
resources/sass            Estilos SCSS
resources/js              JavaScript principal
routes/web.php            Rutas web
tests                     Tests unitarios y feature
```

## Notas del proyecto

- Es un MVP academico, no una aplicacion desplegada en produccion.
- Las imagenes subidas en local se guardan en `storage/app/public`.
- Para que las imagenes sean visibles en navegador es necesario ejecutar `php artisan storage:link`.
- Algunas funcionalidades como pagos reales, despliegue cloud o validaciones avanzadas quedan planteadas como mejoras futuras.

## Autores

- ahoylin1012
- mingoranceevaristo001
- torrespedro-001
