<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/TareaController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/AuthController.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$tareaController = new TareaController();
$tareas = $tareaController->obtenerTareasParaEstudiantes();

if (empty($tareas)) {
    echo "No tienes tareas asignadas.";
} else {
    foreach ($tareas as $tarea) {
    ;
    }
}
?>

<tbody>
    <?php foreach ($tareas as $tarea): ?>
        <tr>
            <td><?= htmlspecialchars($tarea['titulo']) ?></td>
            <td><?= htmlspecialchars($tarea['materia_nombre']) ?></td>
            <td><?= htmlspecialchars($tarea['fecha_creacion']) ?></td>
            <td><?= htmlspecialchars($tarea['fecha_entrega']) ?></td>
            <td><?= htmlspecialchars($tarea['grupo_nombre']) ?></td>
            <td><?= htmlspecialchars($tarea['estado_nombre']) ?></td>
            <td>
                <?php if ($tarea['estado_nombre'] !== 'completada' && $tarea['estado_nombre'] !== 'vencida' && $tarea['estado_nombre'] !== 'calificada'): ?>
                    <button class="btn btn-sm btn-primary btn-ver-tarea" 
                data-bs-toggle="modal" 
                data-bs-target="#modalSubirTarea"
                data-id="<?= $tarea['id'] ?>">
            Ver
        </button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
