<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/TareaController.php');
session_start();

// Verificamos que el usuario esté autenticado como estudiante
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'estudiante') {
    die("Acceso denegado.");
}

// Crear instancia del controlador y obtener tareas
$tareaController = new TareaController();
$tareas = $tareaController->obtenerTareasParaEstudiantes();

foreach ($tareas as $tarea) {
    echo "<tr>
        <td>" . htmlspecialchars($tarea['titulo']) . "</td>
        <td>" . htmlspecialchars($tarea['materia_nombre']) . "</td>
        <td>" . htmlspecialchars($tarea['fecha_creacion']) . "</td>
        <td>" . htmlspecialchars($tarea['fecha_entrega']) . "</td>
        <td>" . htmlspecialchars($tarea['grupo_nombre']) . "</td>
        <td>" . ($tarea['estado_id'] == 1 ? '<span class="label bg-orange">Pendiente</span>' : 
                 ($tarea['estado_id'] == 2 ? '<span class="label bg-blue">En Progreso</span>' : 
                 '<span class="label bg-green">Completada</span>')) . "</td>
        <td><button class='btn btn-sm btn-primary'>Ver Detalles</button></td>
    </tr>";
}
?>
