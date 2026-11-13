<?php
class Database {
    public static function conectar() {
        $archivo = __DIR__ . '/../.env';
        $datos = file_exists($archivo) ? parse_ini_file($archivo) : [];

        $host = getenv('DB_HOST') ?: ($datos['DB_HOST'] ?? 'db');
        $nombre = getenv('DB_NAME') ?: ($datos['DB_NAME'] ?? 'automotora');
        $usuario = getenv('DB_USER') ?: ($datos['DB_USER'] ?? 'root');
        $clave = getenv('DB_PASS') ?: ($datos['DB_PASS'] ?? 'root');

        $conexion = new PDO(
            "mysql:host=$host;dbname=$nombre;charset=utf8mb4",
            $usuario,
            $clave
        );
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    }
}