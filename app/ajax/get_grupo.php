<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');

header('Content-Type: application/json');

$grupoController = new GrupoController();
$id = $_GET['id'] ?? 0;

try {
    $grupo = $grupoController->obtenerPorId($id);
    echo json_encode([
        'success' => true,
        'data' => $grupo
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
