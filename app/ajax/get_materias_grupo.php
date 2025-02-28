<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');

// Verificar sesión
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['rol'], ['administrador', 'profesor'])) {
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

// Verificar parámetro
if (!isset($_GET['grupo_id']) || !is_numeric($_GET['grupo_id'])) {
    echo json_encode(['error' => 'ID de grupo no válido']);
    exit;
}

$grupoId = intval($_GET['grupo_id']);
$controller = new GrupoController();

try {
    $materias = $controller->obtenerMateriasGrupo($grupoId);
    
    if (is_array($materias) && !isset($materias['error'])) {
        echo json_encode([
            'success' => true,
            'data' => $materias
        ]);
    } else {
        echo json_encode(['error' => $materias['error'] ?? 'Error al obtener materias del grupo']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
}
?>
