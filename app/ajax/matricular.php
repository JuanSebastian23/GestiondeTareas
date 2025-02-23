<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');

header('Content-Type: application/json');

$grupoController = new GrupoController();
$resultado = $grupoController->procesarAccion();

echo json_encode($resultado);
