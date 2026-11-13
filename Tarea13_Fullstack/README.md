# Tarea 13 — Fullstack

Sistema web sencillo para consultar, agregar y modificar vehículos de una automotora.

## Requisitos

- Docker
- Docker Compose

## Cómo iniciar el proyecto

1. Abrir una terminal en la carpeta principal del proyecto.
2. Ejecutar:

   docker compose up -d --build

3. Abrir en el navegador:

   http://localhost:8080

La API se encuentra disponible bajo `/api/index.php`.

## Base de datos

MySQL crea la base `automotora` e importa automáticamente `api/Config/init.sql` la primera vez que se crea el volumen.

La conexión se configura con las variables `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASS` del archivo `.env` y de Docker Compose.

## Funciones disponibles

- Listar vehículos con su marca.
- Consultar y modificar los datos de un vehículo.
- Agregar un vehículo y relacionarlo con una marca existente.
- Validar los campos obligatorios y mostrar mensajes de resultado.

## Detener los servicios

   docker compose down

Para borrar también los datos guardados en MySQL y volver a importar el SQL desde cero:

   docker compose down -v

## Integrantes

Completar con nombre y apellido de los integrantes antes de entregar.
