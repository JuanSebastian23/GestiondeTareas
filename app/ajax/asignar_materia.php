<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');

// Verificar sesión
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'administrador') {
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Verificar parámetros requeridos
if (!isset($_POST['accion']) || !isset($_POST['grupo_id']) || !isset($_POST['materia_id'])) {
    echo json_encode(['error' => 'Faltan parámetros obligatorios']);
    exit;
}

$controller = new GrupoController();
$resultado = $controller->procesarAsignacionMateria($_POST);

// Devolver el resultado como JSON
echo json_encode($resultado);
?>
