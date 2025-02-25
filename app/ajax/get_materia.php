<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/MateriaController.php');

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
    exit;
}

$materiaController = new MateriaController();
$materia = $materiaController->obtenerPorId($_GET['id']);

if ($materia) {
    echo json_encode(['success' => true, 'data' => $materia]);
} else {
    echo json_encode(['success' => false, 'error' => 'Materia no encontrada']);
}
