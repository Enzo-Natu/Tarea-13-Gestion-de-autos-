<?php
class VehiculoController {
    public static function listar() {
        $conexion = Database::conectar();
        $consulta = $conexion->query(
            "SELECT v.id_vehiculo, v.id_marca, m.nombre_marca, m.pais_origen,
                    v.modelo, v.anio, v.precio, v.estado
             FROM VEHICULOS v
             INNER JOIN MARCAS m ON v.id_marca = m.id_marca
             ORDER BY v.id_vehiculo DESC"
        );
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscar($id) {
        $conexion = Database::conectar();
        $consulta = $conexion->prepare(
            "SELECT v.id_vehiculo, v.id_marca, m.nombre_marca, m.pais_origen,
                    v.modelo, v.anio, v.precio, v.estado
             FROM VEHICULOS v
             INNER JOIN MARCAS m ON v.id_marca = m.id_marca
             WHERE v.id_vehiculo = ?"
        );
        $consulta->execute([$id]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public static function crear($datos) {
        self::validar($datos);
        $conexion = Database::conectar();
        $consulta = $conexion->prepare(
            "INSERT INTO VEHICULOS (id_marca, modelo, anio, precio, estado)
             VALUES (?, ?, ?, ?, ?)"
        );
        $consulta->execute([
            $datos['id_marca'],
            trim($datos['modelo']),
            $datos['anio'] ?: null,
            $datos['precio'] ?: null,
            $datos['estado']
        ]);
        return $conexion->lastInsertId();
    }

    public static function actualizar($id, $datos) {
        self::validar($datos);
        $conexion = Database::conectar();
        $consulta = $conexion->prepare(
            "UPDATE VEHICULOS
             SET id_marca = ?, modelo = ?, anio = ?, precio = ?, estado = ?
             WHERE id_vehiculo = ?"
        );
        $consulta->execute([
            $datos['id_marca'],
            trim($datos['modelo']),
            $datos['anio'] ?: null,
            $datos['precio'] ?: null,
            $datos['estado'],
            $id
        ]);
        return $consulta->rowCount();
    }

    private static function validar($datos) {
        if (
            empty($datos['id_marca']) ||
            empty(trim($datos['modelo'] ?? '')) ||
            empty($datos['estado'])
        ) {
            throw new Exception('Completá los campos obligatorios.');
        }

        if (!is_numeric($datos['id_marca'])) {
            throw new Exception('La marca seleccionada no es válida.');
        }

        if (!empty($datos['anio']) && (!is_numeric($datos['anio']) || $datos['anio'] < 1886 || $datos['anio'] > 2100)) {
            throw new Exception('Ingresá un año válido.');
        }

        if (!empty($datos['precio']) && (!is_numeric($datos['precio']) || $datos['precio'] < 0)) {
            throw new Exception('Ingresá un precio válido.');
        }

        if (!in_array($datos['estado'], ['Nuevo', 'Usado'])) {
            throw new Exception('Seleccioná un estado válido.');
        }
    }
}