<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/DbConfig.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$cdb = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$cdb->set_charset("utf8mb4");

if ($cdb->connect_error) {
    echo json_encode(["error" => "Error de conexión: " . $cdb->connect_error]);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT et.nombre AS estado, COUNT(t.id) as total 
          FROM tareas t
          JOIN estados_tarea et ON t.estado_id = et.id
          GROUP BY et.nombre";

$stmt = $cdb->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$estadisticas = [
    "Pendiente" => 0,
    "En Progreso" => 0,
    "Completada" => 0,
    "Vencida" => 0
];

while ($row = $result->fetch_assoc()) {
    $estadisticas[$row['estado']] = $row['total'];
}

echo json_encode($estadisticas);