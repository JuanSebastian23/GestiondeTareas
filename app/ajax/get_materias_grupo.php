<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');

header('Content-Type: application/json');

if (!isset($_GET['grupo_id'])) {
    echo json_encode(['success' => false, 'error' => 'ID de grupo no proporcionado']);
    exit;
}

$grupoController = new GrupoController();

try {
    $materias = $grupoController->obtenerMateriasGrupo($_GET['grupo_id']);
    echo json_encode(['success' => true, 'data' => $materias]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
