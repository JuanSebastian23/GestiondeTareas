<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/GestionTareaController.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gestionTareaController = new GestionTareaController();
    $resultado = $gestionTareaController->procesarCreacionTarea($_POST);
    echo json_encode($resultado);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>