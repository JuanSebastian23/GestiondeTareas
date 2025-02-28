<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');

// Verificar sesión
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['rol'], ['administrador', 'profesor'])) {
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

// Verificar parámetros
if (!isset($_GET['accion']) || !isset($_GET['grupo_id']) || !is_numeric($_GET['grupo_id'])) {
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

$accion = $_GET['accion'];
$grupoId = intval($_GET['grupo_id']);
$controller = new GrupoController();

try {
    $estudiantes = [];
    
    if ($accion === 'matriculados') {
        $estudiantes = $controller->obtenerEstudiantesMatriculados($grupoId);
    } elseif ($accion === 'no_matriculados') {
        $estudiantes = $controller->obtenerEstudiantesNoMatriculados($grupoId);
    } else {
        echo json_encode(['error' => 'Acción no válida']);
        exit;
    }
    
    // Verificar si es un arreglo (éxito) o un objeto con un campo error
    if (is_array($estudiantes) && !isset($estudiantes['error'])) {
        echo json_encode($estudiantes);
    } else {
        echo json_encode(['error' => $estudiantes['error'] ?? 'Error al obtener estudiantes']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
}
?>
