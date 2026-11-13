<?php
class MarcaController {
    public static function listar() {
        $conexion = Database::conectar();
        $consulta = $conexion->query(
            "SELECT id_marca, nombre_marca, pais_origen FROM MARCAS ORDER BY nombre_marca"
        );
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}