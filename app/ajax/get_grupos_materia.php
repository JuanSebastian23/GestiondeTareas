<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/MateriaController.php');

header('Content-Type: application/json');

if (!isset($_GET['materia_id'])) {
    echo json_encode(['success' => false, 'error' => 'ID de materia no proporcionado']);
    exit;
}

$materiaController = new MateriaController();

try {
    $grupos = $materiaController->obtenerGrupos($_GET['materia_id']);
    echo json_encode(['success' => true, 'data' => $grupos]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
