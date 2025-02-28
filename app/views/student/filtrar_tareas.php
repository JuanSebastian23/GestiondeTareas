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
        // Convertimos el estado a minúsculas y eliminamos espacios extra
        $estado_tarea = strtolower(trim($tarea['estado_nombre']));
        $estados_excluidos = ['completada', 'vencida', 'calificada'];

        echo "<tr>
                <td>" . htmlspecialchars($tarea['titulo']) . "</td>
                <td>" . htmlspecialchars($tarea['materia_nombre']) . "</td>
                <td>" . htmlspecialchars($tarea['fecha_creacion']) . "</td>
                <td>" . htmlspecialchars($tarea['fecha_entrega']) . "</td>
                <td>" . htmlspecialchars($tarea['grupo_nombre']) . "</td>
                <td>" . htmlspecialchars($tarea['estado_nombre']) . "</td>
                <td>";

        // Solo mostramos el botón "Ver" si el estado NO está en la lista de excluidos
        if ($tarea['estado_nombre'] !== 'completada' && $tarea['estado_nombre'] !== 'vencida' && $tarea['estado_nombre'] !== 'calificada'): ?>
            "<button class="btn btn-sm btn-primary btn-ver-tarea" data-id="<?= $tarea['id'] ?>" data-bs-toggle="modal" data-bs-target="#modalSubirTarea">
    Ver
</button>";
        <?php endif; ?>
        </td>   
        </tr>
        <?php
    }
}
?>
  
        echo "</td></tr>";
    }
}
?>
