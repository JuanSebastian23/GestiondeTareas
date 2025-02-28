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
if (!isset($_GET['tarea_id']) || !is_numeric($_GET['tarea_id'])) {
    echo json_encode(['error' => 'ID de tarea no válido']);
    exit;
}

$tareaId = intval($_GET['tarea_id']);
$profesorId = $_SESSION['user_id'];

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$conn->set_charset("utf8mb4");

// Verificar que la tarea pertenece al profesor
$sqlVerificar = "SELECT t.*, g.nombre AS grupo_nombre, m.nombre AS materia_nombre 
                FROM tareas t
                INNER JOIN grupos g ON t.grupo_id = g.id
                INNER JOIN materias m ON t.materia_id = m.id
                WHERE t.id = ? AND t.profesor_id = ?";
$stmt = $conn->prepare($sqlVerificar);
$stmt->bind_param("ii", $tareaId, $profesorId);
$stmt->execute();
$resultVerificar = $stmt->get_result();

if ($resultVerificar->num_rows === 0) {
    echo json_encode(['error' => 'Tarea no encontrada o no autorizada']);
    exit;
}

$tarea = $resultVerificar->fetch_assoc();

// Obtener todas las entregas de la tarea
$sqlEntregas = "SELECT et.*, u.nombre AS estudiante_nombre, u.apellidos AS estudiante_apellidos, est.nombre AS estado
                FROM entregas_tarea et
                INNER JOIN usuarios u ON et.estudiante_id = u.id
                INNER JOIN estados_tarea est ON et.estado_id = est.id
                WHERE et.tarea_id = ?
                ORDER BY et.fecha_entrega DESC";
$stmt = $conn->prepare($sqlEntregas);
$stmt->bind_param("i", $tareaId);
$stmt->execute();
$resultEntregas = $stmt->get_result();
$entregas = $resultEntregas->fetch_all(MYSQLI_ASSOC);

// Obtener el total de estudiantes del grupo
$sqlEstudiantes = "SELECT COUNT(*) AS total_estudiantes FROM estudiante_grupo WHERE grupo_id = ?";
$stmt = $conn->prepare($sqlEstudiantes);
$stmt->bind_param("i", $tarea['grupo_id']);
$stmt->execute();
$resultEstudiantes = $stmt->get_result();
$estudiantesData = $resultEstudiantes->fetch_assoc();
$tarea['total_estudiantes'] = $estudiantesData['total_estudiantes'];

// Convertir datos a UTF-8 para evitar problemas con caracteres especiales
array_walk_recursive($entregas, function(&$item) {
    if (is_string($item)) {
        $item = utf8_encode($item);
    }
});

array_walk_recursive($tarea, function(&$item) {
    if (is_string($item)) {
        $item = utf8_encode($item);
    }
});

echo json_encode(['success' => true, 'data' => $entregas, 'tarea' => $tarea]);
?>
