<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

require_once(CONTROLLERS_PATH . '/TareaController.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');
require_once(CONTROLLERS_PATH . '/MateriaController.php');

$tareaController = new TareaController();
$grupoController = new GrupoController();
$materiaController = new MateriaController();

// Procesar calificación si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'calificar':
            $resultado = $tareaController->calificarEntrega($_POST);
            if (isset($resultado['success'])) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert('Éxito', '{$resultado['success']}', 'success');
                    });
                </script>";
            } elseif (isset($resultado['error'])) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert('Error', '{$resultado['error']}', 'error');
                    });
                </script>";
            }
            break;
        case 'cambiar_estado':
            $resultado = $tareaController->cambiarEstadoTarea($_POST);
            if (isset($resultado['success'])) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert('Éxito', '{$resultado['success']}', 'success');
                    });
                </script>";
            } elseif (isset($resultado['error'])) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showAlert('Error', '{$resultado['error']}', 'error');
                    });
                </script>";
            }
            break;
    }
}

// Obtener filtros
$filtroGrupo = isset($_GET['grupo']) ? $_GET['grupo'] : null;
$filtroMateria = isset($_GET['materia']) ? $_GET['materia'] : null;
$filtroEstado = isset($_GET['estado']) ? $_GET['estado'] : null;

// Obtener tareas asignadas al profesor actual con sus entregas
$tareasAsignadas = $tareaController->obtenerTareasAsignadasConEntregas(
    $_SESSION['user_id'],
    $filtroGrupo,
    $filtroMateria,
    $filtroEstado
);

// Obtener grupos y materias para los filtros
$grupos = $grupoController->obtenerGruposPorProfesor($_SESSION['user_id']);
$materias = $materiaController->obtenerMateriasPorProfesor($_SESSION['user_id']);
$estadosTarea = $tareaController->obtenerEstadosTarea();

// Estadísticas
$stats = [
    'total' => count($tareasAsignadas),
    'pendientes' => 0,
    'entregadas' => 0,
    'calificadas' => 0,
    'vencidas' => 0
];

foreach ($tareasAsignadas as $tarea) {
    if (isset($tarea['estado']) && $tarea['estado'] === 'pendiente') {
        $stats['pendientes']++;
    } elseif (isset($tarea['estado']) && $tarea['estado'] === 'entregada') {
        $stats['entregadas']++;
    } elseif (isset($tarea['estado']) && $tarea['estado'] === 'calificada') {
        $stats['calificadas']++;
    } elseif (isset($tarea['estado']) && $tarea['estado'] === 'vencida') {
        $stats['vencidas']++;
    }
}
?>

<h1 class="position-relative header-page">Tareas Asignadas</h1>

<div class="container-fluid py-4">
    <!-- Estadísticas de Tareas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="icon-box bg-primary text-white mb-3">
                        <i class="fas fa-tasks fa-lg"></i>
                    </div>
                    <h3 class="fs-1 fw-bold text-primary mb-0"><?= $stats['total'] ?></h3>
                    <p class="text-muted mb-0">Total de Tareas</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-warning bg-opacity-10">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="icon-box bg-warning text-white mb-3">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <h3 class="fs-1 fw-bold text-warning mb-0"><?= $stats['pendientes'] ?></h3>
                    <p class="text-muted mb-0">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-success bg-opacity-10">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="icon-box bg-success text-white mb-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <h3 class="fs-1 fw-bold text-success mb-0"><?= $stats['entregadas'] + $stats['calificadas'] ?></h3>
                    <p class="text-muted mb-0">Entregas Recibidas</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-info bg-opacity-10">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="icon-box bg-info text-white mb-3">
                        <i class="fas fa-star fa-lg"></i>
                    </div>
                    <h3 class="fs-1 fw-bold text-info mb-0"><?= $stats['calificadas'] ?></h3>
                    <p class="text-muted mb-0">Calificadas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3">
                <input type="hidden" name="page" value="assigned_tasks">
                <input type="hidden" name="role" value="teacher">
                
                <div class="col-md-3">
                    <label class="form-label">Grupo</label>
                    <select class="form-select" name="grupo">
                        <option value="">Todos los grupos</option>
                        <?php foreach ($grupos as $grupo): ?>
                            <option value="<?= $grupo['id'] ?>" <?= $filtroGrupo == $grupo['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($grupo['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Materia</label>
                    <select class="form-select" name="materia">
                        <option value="">Todas las materias</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= $materia['id'] ?>" <?= $filtroMateria == $materia['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($materia['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select" name="estado">
                        <option value="">Todos los estados</option>
                        <?php foreach ($estadosTarea as $estado): ?>
                            <option value="<?= $estado['id'] ?>" <?= $filtroEstado == $estado['id'] ? 'selected' : '' ?>>
                                <?= ucfirst(htmlspecialchars($estado['nombre'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Tareas Asignadas -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h2 class="h5 mb-0">Lista de Tareas Asignadas</h2>
        </div>
        <div class="card-body p-0">
            <?php if (count($tareasAsignadas) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tarea</th>
                                <th>Grupo</th>
                                <th>Materia</th>
                                <th>Fecha Entrega</th>
                                <th>Estado</th>
                                <th>Entregas</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tareasAsignadas as $tarea): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($tarea['titulo']) ?></div>
                                        <small class="text-muted"><?= mb_strimwidth(htmlspecialchars($tarea['descripcion']), 0, 50, "...") ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($tarea['grupo_nombre']) ?></td>
                                    <td><?= htmlspecialchars($tarea['materia_nombre']) ?></td>
                                    <td>
                                        <?php 
                                        $fechaEntrega = new DateTime($tarea['fecha_entrega']);
                                        $hoy = new DateTime();
                                        $diff = $hoy->diff($fechaEntrega);
                                        $vencida = $hoy > $fechaEntrega;
                                        
                                        echo $fechaEntrega->format('d/m/Y H:i');
                                        
                                        if ($vencida) {
                                            echo '<br><span class="badge bg-danger">Vencida</span>';
                                        } elseif ($diff->days <= 1) {
                                            echo '<br><span class="badge bg-warning">Hoy/Mañana</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $estadoClass = match($tarea['estado']) {
                                            'pendiente' => 'bg-warning',
                                            'entregada' => 'bg-primary',
                                            'completada', 'calificada' => 'bg-success',
                                            'vencida' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $estadoClass ?>"><?= ucfirst($tarea['estado']) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2"><?= $tarea['entregadas'] ?>/<?= $tarea['total_estudiantes'] ?></span>
                                            <?php $porcentaje = $tarea['total_estudiantes'] > 0 ? round(($tarea['entregadas'] / $tarea['total_estudiantes']) * 100) : 0; ?>
                                            <div class="progress flex-grow-1" style="height: 5px;">
                                                <div class="progress-bar bg-success" style="width: <?= $porcentaje ?>%" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-primary me-1" onclick="verDetallesTarea(<?= $tarea['id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info me-1" onclick="revisarEntregas(<?= $tarea['id'] ?>)">
                                            <i class="fas fa-clipboard-check"></i>
                                        </button>
                                        <?php if ($tarea['estado'] === 'pendiente'): ?>
                                            <button class="btn btn-sm btn-danger" onclick="marcarVencida(<?= $tarea['id'] ?>)">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                    <h3 class="h5 text-muted">No hay tareas que coincidan con los filtros</h3>
                    <p class="text-muted">Intenta cambiar los criterios de búsqueda</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para Mostrar Detalles de Tarea -->
<div class="modal fade" id="detallesTareaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detalles de la Tarea</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="detallesTareaModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Revisar Entregas -->
<div class="modal fade" id="revisionEntregasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Revisión de Entregas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="revisionEntregasModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Calificar Entrega -->
<div class="modal fade" id="calificarEntregaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Calificar Entrega</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formCalificar" method="POST" action="">
                    <input type="hidden" name="accion" value="calificar">
                    <input type="hidden" id="entrega_id" name="entrega_id" value="">
                    
                    <div class="mb-3">
                        <label for="calificacion" class="form-label">Calificación (0-10)</label>
                        <input type="number" class="form-control" id="calificacion" name="calificacion" min="0" max="10" step="0.1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comentarios" class="form-label">Comentarios</label>
                        <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">Guardar Calificación</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones para manipular los modales
function verDetallesTarea(tareaId) {
    const modal = new bootstrap.Modal(document.getElementById('detallesTareaModal'));
    const modalBody = document.getElementById('detallesTareaModalBody');
    
    // Mostrar spinner mientras carga
    modalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Cargar detalles de la tarea mediante AJAX
    fetch('<?= BASE_URL ?>/app/ajax/get_tarea_detalles.php?id=' + tareaId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tarea = data.data;
                const fechaCreacion = new Date(tarea.fecha_creacion).toLocaleString();
                const fechaEntrega = new Date(tarea.fecha_entrega).toLocaleString();
                
                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Título</h6>
                            <p>${tarea.titulo}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Estado</h6>
                            <p><span class="badge bg-${getStatusClass(tarea.estado)}">${tarea.estado}</span></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Grupo</h6>
                            <p>${tarea.grupo_nombre}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Materia</h6>
                            <p>${tarea.materia_nombre}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Fecha de Creación</h6>
                            <p>${fechaCreacion}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Fecha de Entrega</h6>
                            <p>${fechaEntrega}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold">Descripción</h6>
                            <p>${tarea.descripcion}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold">Progreso de Entregas</h6>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: ${tarea.porcentaje_entregadas}%" role="progressbar"></div>
                            </div>
                            <small class="text-muted">${tarea.entregas_recibidas} de ${tarea.total_estudiantes} estudiantes (${tarea.porcentaje_entregadas}%)</small>
                        </div>
                    </div>
                `;
            } else {
                modalBody.innerHTML = `<div class="alert alert-danger">Error: ${data.error}</div>`;
            }
        })
        .catch(error => {
            modalBody.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        });
}

function revisarEntregas(tareaId) {
    const modal = new bootstrap.Modal(document.getElementById('revisionEntregasModal'));
    const modalBody = document.getElementById('revisionEntregasModalBody');
    
    // Mostrar spinner mientras carga
    modalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-info" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Cargar entregas de la tarea mediante AJAX
    fetch('<?= BASE_URL ?>/app/ajax/get_entregas.php?tarea_id=' + tareaId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const entregas = data.data;
                const tarea = data.tarea;
                
                let html = `
                    <div class="mb-4">
                        <h5>${tarea.titulo}</h5>
                        <p class="text-muted small">Fecha de entrega: ${new Date(tarea.fecha_entrega).toLocaleString()}</p>
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">Progreso: ${entregas.length}/${tarea.total_estudiantes}</div>
                            <div class="progress flex-grow-1" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: ${entregas.length / tarea.total_estudiantes * 100}%"></div>
                            </div>
                        </div>
                    </div>
                `;
                
                if (entregas.length > 0) {
                    html += `
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Fecha de Entrega</th>
                                        <th>Estado</th>
                                        <th>Calificación</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    entregas.forEach(entrega => {
                        const fechaEntrega = entrega.fecha_entrega ? new Date(entrega.fecha_entrega).toLocaleString() : 'No entregada';
                        const estadoClass = getStatusClass(entrega.estado);
                        
                        html += `
                            <tr>
                                <td>${entrega.estudiante_nombre} ${entrega.estudiante_apellidos}</td>
                                <td>${fechaEntrega}</td>
                                <td><span class="badge bg-${estadoClass}">${entrega.estado}</span></td>
                                <td>${entrega.calificacion !== null ? entrega.calificacion : 'No calificada'}</td>
                                <td class="text-end">
                                    ${entrega.fecha_entrega ? `
                                        <button class="btn btn-sm btn-success" onclick="calificarEntrega(${entrega.id})">
                                            <i class="fas fa-star me-1"></i> Calificar
                                        </button>
                                    ` : `
                                        <span class="badge bg-secondary">Pendiente</span>
                                    `}
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard fa-3x text-muted mb-3"></i>
                            <h3 class="h5 text-muted">No hay entregas para esta tarea</h3>
                        </div>
                    `;
                }
                
                modalBody.innerHTML = html;
            } else {
                modalBody.innerHTML = `<div class="alert alert-danger">Error: ${data.error}</div>`;
            }
        })
        .catch(error => {
            modalBody.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        });
}

function calificarEntrega(entregaId) {
    const modal = new bootstrap.Modal(document.getElementById('calificarEntregaModal'));
    document.getElementById('entrega_id').value = entregaId;
    
    // Limpiar el formulario
    document.getElementById('calificacion').value = '';
    document.getElementById('comentarios').value = '';
    
    // Opcionalmente, cargar datos existentes si ya fue calificada
    fetch('<?= BASE_URL ?>/app/ajax/get_calificacion.php?entrega_id=' + entregaId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                document.getElementById('calificacion').value = data.data.calificacion;
                document.getElementById('comentarios').value = data.data.comentarios;
            }
        });
    
    modal.show();
}

function marcarVencida(tareaId) {
    confirmAction('¿Estás seguro?', 'La tarea se marcará como vencida y no se aceptarán más entregas.', 'warning')
        .then((result) => {
            if (result.isConfirmed) {
                // Crear un formulario dinámico para enviar la acción
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="accion" value="cambiar_estado">
                    <input type="hidden" name="tarea_id" value="${tareaId}">
                    <input type="hidden" name="estado_id" value="4"> <!-- ID del estado 'vencida' -->
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
}

function getStatusClass(estado) {
    return {
        'pendiente': 'warning',
        'en_progreso': 'primary',
        'completada': 'success',
        'calificada': 'success',
        'vencida': 'danger'
    }[estado] || 'secondary';
}
</script>
