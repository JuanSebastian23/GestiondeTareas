<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

// Verificar que el usuario sea un profesor
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'profesor') {
    echo "<div class='alert alert-danger'>Acceso no autorizado</div>";
    exit;
}

// Cargar controladores necesarios
require_once(CONTROLLERS_PATH . '/TareaController.php');
require_once(CONTROLLERS_PATH . '/MateriaController.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');

// Inicializar controladores
$tareaController = new TareaController();
$materiaController = new MateriaController();
$grupoController = new GrupoController();

// Obtener datos para formularios
$profesorId = $_SESSION['user_id'];
$grupos = $grupoController->obtenerGruposPorProfesor($profesorId);
$materias = $materiaController->obtenerMateriasPorProfesor($profesorId);

// Manejar creación de tareas
$mensaje = null;
$tipoMensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    // Añadir el ID del profesor actual
    $_POST['profesor_id'] = $profesorId;
    
    // Crear la tarea
    $resultado = $tareaController->crearTarea($_POST);
    
    if (isset($resultado['success'])) {
        $mensaje = "Tarea creada exitosamente.";
        $tipoMensaje = "success";
    } else {
        $mensaje = "Error: " . ($resultado['error'] ?? "No se pudo crear la tarea");
        $tipoMensaje = "danger";
    }
}

// Obtener todas las tareas del profesor
$tareas = $tareaController->obtenerTareasAsignadasConEntregas($profesorId);
?>

<div class="container-fluid py-4">
    <h1 class="position-relative header-page">Gestión de Tareas</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipoMensaje ?> alert-dismissible fade show" role="alert">
            <?= $mensaje ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Crear Nueva Tarea</h5>
                </div>
                <div class="card-body">
                    <form method="post" id="formCrearTarea">
                        <input type="hidden" name="accion" value="crear">
                        <input type="hidden" name="profesor_id" value="<?= $profesorId ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="titulo" class="form-label">Título de la Tarea <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fecha_entrega" class="form-label">Fecha de Entrega <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="fecha_entrega" name="fecha_entrega" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="grupo_id" class="form-label">Grupo <span class="text-danger">*</span></label>
                                <select class="form-select" id="grupo_id" name="grupo_id" required>
                                    <option value="">Seleccione un grupo</option>
                                    <?php foreach ($grupos as $grupo): ?>
                                        <option value="<?= $grupo['id'] ?>"><?= htmlspecialchars($grupo['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="materia_id" class="form-label">Materia <span class="text-danger">*</span></label>
                                <select class="form-select" id="materia_id" name="materia_id" required>
                                    <option value="">Seleccione una materia</option>
                                    <?php foreach ($materias as $materia): ?>
                                        <option value="<?= $materia['id'] ?>"><?= htmlspecialchars($materia['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>Crear Tarea
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Mis Tareas Asignadas</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($tareas)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No tiene tareas asignadas.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Grupo</th>
                                        <th>Materia</th>
                                        <th>Fecha Entrega</th>
                                        <th>Entregas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tareas as $tarea): 
                                        // Determinar clase de badge según estado
                                        $estadoClase = match($tarea['estado']) {
                                            'pendiente' => 'bg-warning',
                                            'completada' => 'bg-success',
                                            'calificada' => 'bg-info',
                                            'vencida' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tarea['titulo']) ?></td>
                                            <td><?= htmlspecialchars($tarea['grupo_nombre']) ?></td>
                                            <td><?= htmlspecialchars($tarea['materia_nombre']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($tarea['fecha_entrega'])) ?></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?= $tarea['entregadas'] ?> / <?= $tarea['total_estudiantes'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $estadoClase ?>">
                                                    <?= ucfirst($tarea['estado']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?>?page=task_submissions&tarea_id=<?= $tarea['id'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Ver entregas
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha mínima como hoy
    const today = new Date();
    const fechaEntregaInput = document.getElementById('fecha_entrega');
    
    // Formatear fecha para el input datetime-local (YYYY-MM-DDThh:mm)
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const hours = String(today.getHours()).padStart(2, '0');
    const minutes = String(today.getMinutes()).padStart(2, '0');
    
    const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
    fechaEntregaInput.min = formattedDate;
    
    // Validación del formulario
    document.getElementById('formCrearTarea').addEventListener('submit', function(e) {
        const titulo = document.getElementById('titulo').value.trim();
        const fechaEntrega = document.getElementById('fecha_entrega').value;
        const grupoId = document.getElementById('grupo_id').value;
        const materiaId = document.getElementById('materia_id').value;
        
        if (!titulo || !fechaEntrega || !grupoId || !materiaId) {
            e.preventDefault();
            alert('Por favor, complete todos los campos requeridos.');
        }
    });
});
</script>            }
        </style>
    </body>

</html>