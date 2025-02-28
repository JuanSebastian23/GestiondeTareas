<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/MateriaController.php');

// Verificar sesión
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'administrador') {
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

header('Content-Type: application/json');

// Verificar parámetro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'ID de materia no válido']);
    exit;
}

$materiaId = intval($_GET['id']);
$controller = new MateriaController();

try {
    $materiaData = $controller->obtenerPorId($materiaId);
    
    if ($materiaData) {
        echo json_encode([
            'success' => true,
            'data' => $materiaData
        ]);
    } else {
        echo json_encode(['error' => 'Materia no encontrada']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al obtener materia: ' . $e->getMessage()]);
}
?>
