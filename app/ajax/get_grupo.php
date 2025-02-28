<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');

// Verificar sesión
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'administrador') {
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

// Verificar parámetro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'ID de grupo no válido']);
    exit;
}

$grupoId = intval($_GET['id']);
$controller = new GrupoController();

try {
    $grupoData = $controller->obtenerPorId($grupoId);
    
    if ($grupoData) {
        echo json_encode([
            'success' => true,
            'data' => $grupoData
        ]);
    } else {
        echo json_encode(['error' => 'Grupo no encontrado']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al obtener grupo: ' . $e->getMessage()]);
}
?>
