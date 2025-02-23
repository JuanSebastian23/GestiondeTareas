<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');

header('Content-Type: application/json');

$grupoController = new GrupoController();
$grupo_id = $_GET['grupo_id'] ?? 0;
$accion = $_GET['accion'] ?? '';

$resultado = match($accion) {
    'matriculados' => $grupoController->obtenerEstudiantesMatriculados($grupo_id),
    'no_matriculados' => $grupoController->obtenerEstudiantesNoMatriculados($grupo_id),
    default => ['error' => 'Acción no válida']
};

echo json_encode($resultado);
