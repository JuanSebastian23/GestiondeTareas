<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/TareaController.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$tareaController = new TareaController();
$materia = isset($_GET['materia']) ? $_GET['materia'] : null;
$estado = isset($_GET['estado']) ? $_GET['estado'] : null;

$tareas = $tareaController->obtenerTareasFiltradas($materia, $estado);

if (empty($tareas)) {
    echo "<tr><td colspan='7'>No hay tareas que coincidan con los filtros.</td></tr>";
} else {
    foreach ($tareas as $tarea) {
        echo "<tr>
                <td>" . htmlspecialchars($tarea['titulo']) . "</td>
                <td>" . htmlspecialchars($tarea['materia_nombre']) . "</td>
                <td>" . htmlspecialchars($tarea['fecha_creacion']) . "</td>
                <td>" . htmlspecialchars($tarea['fecha_entrega']) . "</td>
                <td>" . htmlspecialchars($tarea['grupo_nombre']) . "</td>
                <td>" . htmlspecialchars($tarea['estado_nombre']) . "</td>
                <td><button class='btn btn-sm btn-primary'>Ver</button></td>
              </tr>";
    }
}
?>
