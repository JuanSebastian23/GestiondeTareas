<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

// Verificar sesión
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'profesor') {
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

// Verificar parámetro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'ID de tarea no válido']);
    exit;
}

$tareaId = intval($_GET['id']);
$profesorId = $_SESSION['user_id'];

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$conn->set_charset("utf8mb4");

// Consultar detalles de la tarea
$sql = "SELECT t.*, 
           g.nombre AS grupo_nombre, 
           m.nombre AS materia_nombre,
           e.nombre AS estado 
        FROM tareas t
        INNER JOIN grupos g ON t.grupo_id = g.id
        INNER JOIN materias m ON t.materia_id = m.id
        INNER JOIN estados_tarea e ON t.estado_id = e.id
        WHERE t.id = ? AND t.profesor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $tareaId, $profesorId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Tarea no encontrada o no autorizada']);
    exit;
}

$tarea = $result->fetch_assoc();

// Obtener información adicional
$sqlEntregas = "SELECT COUNT(*) AS entregas_recibidas FROM entregas_tarea WHERE tarea_id = ?";
$stmt = $conn->prepare($sqlEntregas);
$stmt->bind_param("i", $tareaId);
$stmt->execute();
$resultEntregas = $stmt->get_result();
$entregasData = $resultEntregas->fetch_assoc();
$tarea['entregas_recibidas'] = $entregasData['entregas_recibidas'];

// Obtener el total de estudiantes del grupo
$sqlEstudiantes = "SELECT COUNT(*) AS total_estudiantes FROM estudiante_grupo WHERE grupo_id = ?";
$stmt = $conn->prepare($sqlEstudiantes);
$stmt->bind_param("i", $tarea['grupo_id']);
$stmt->execute();
$resultEstudiantes = $stmt->get_result();
$estudiantesData = $resultEstudiantes->fetch_assoc();
$tarea['total_estudiantes'] = $estudiantesData['total_estudiantes'];

// Calcular porcentaje de entregas
$tarea['porcentaje_entregadas'] = $tarea['total_estudiantes'] > 0 ? 
    round(($tarea['entregas_recibidas'] / $tarea['total_estudiantes']) * 100) : 0;

// Convertir datos a UTF-8 para evitar problemas con caracteres especiales
array_walk_recursive($tarea, function(&$item) {
    if (is_string($item)) {
        $item = utf8_encode($item);
    }
});

echo json_encode(['success' => true, 'data' => $tarea]);
?>
