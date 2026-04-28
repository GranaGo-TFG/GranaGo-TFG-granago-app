# GranaGO!

GranaGO es el proyecto base del TFG de DAW orientado a crear una aplicacion web para fomentar la exploracion urbana y el descubrimiento local entre jovenes, con foco inicial en Granada.

En esta fase estamos preparando la base tecnica del proyecto para poder desarrollar el MVP sobre una estructura moderna, mantenible y facil de ampliar a medida que el TFG evolucione.

## Tecnologias usadas hasta ahora

- Laravel 13 como framework principal del backend.
- PHP 8.3 como lenguaje de servidor.
- Composer para la gestion de dependencias PHP.
- SQLite como base de datos inicial para desarrollo.
- Node.js y npm para la parte frontend.
- Vite como herramienta de desarrollo y compilacion de assets.
- Tailwind CSS 4 como base de estilos incluida en la instalacion actual.

## Estado actual

- Proyecto Laravel 13 reinstalado y funcionando.
- Entorno `.env` generado.
- Clave de aplicacion creada.
- Migraciones iniciales ejecutadas.
- Dependencias de Composer instaladas.
- Dependencias frontend de npm instaladas.

## Comandos utiles

Instalar dependencias PHP:

```bash
composer install
```

Instalar dependencias frontend:

```bash
npm install
```

Levantar el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

Levantar Vite en desarrollo:

```bash
npm run dev
```

## Nota

Este README ira cambiando conforme avancemos con el TFG, se definan mejor los modulos de la aplicacion y se incorporen nuevas herramientas, integraciones y decisiones tecnicas.
