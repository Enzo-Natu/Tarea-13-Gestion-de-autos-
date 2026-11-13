<?php
header('Content-Type: application/json; charset=utf-8');

function responder($codigo, $datos) {
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $metodo = $_SERVER['REQUEST_METHOD'];
    $ruta = trim($_GET['path'] ?? '', '/');
    $partes = $ruta === '' ? [] : explode('/', $ruta);

    if ($ruta === 'marcas' && $metodo === 'GET') {
        responder(200, MarcaController::listar());
    }

    if ($ruta === 'vehiculos' && $metodo === 'GET') {
        responder(200, VehiculoController::listar());
    }

    if (count($partes) === 2 && $partes[0] === 'vehiculos' && $metodo === 'GET') {
        $vehiculo = VehiculoController::buscar($partes[1]);
        if (!$vehiculo) {
            responder(404, ['mensaje' => 'No se encontró el vehículo.']);
        }
        responder(200, $vehiculo);
    }

    if ($ruta === 'vehiculos' && $metodo === 'POST') {
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];
        $id = VehiculoController::crear($datos);
        responder(201, ['mensaje' => 'El vehículo fue agregado correctamente.', 'id' => $id]);
    }

    if (count($partes) === 2 && $partes[0] === 'vehiculos' && $metodo === 'PUT') {
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];
        VehiculoController::actualizar($partes[1], $datos);
        responder(200, ['mensaje' => 'Los datos del vehículo fueron modificados correctamente.']);
    }

    responder(404, ['mensaje' => 'La dirección solicitada no existe.']);
} catch (PDOException $error) {
    responder(500, ['mensaje' => 'No se pudo realizar la operación en la base de datos.']);
} catch (Exception $error) {
    responder(400, ['mensaje' => $error->getMessage()]);
}
