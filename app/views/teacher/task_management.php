<?php
if (!defined('ROOT_PATH')) {
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

require_once(CONTROLLERS_PATH . '/GrupoController.php');
require_once(CONTROLLERS_PATH . '/MateriaController.php');
require_once(CONTROLLERS_PATH . '/GestionTareaController.php');

// Definir correctamente el controlador
$materiaController = new MateriaController();
$grupoController = new GrupoController();
$gestionTareaController = new GestionTareaController();
$grupos = $grupoController->obtenerGrupos();
$profesores = $grupoController->obtenerProfesores();
$materias = $materiaController->obtenerTodas();
$tareasActivas = $gestionTareaController->obtenerTareasActivas();
$tareas = $gestionTareaController->obtenerTodasLasTareasConDetalles();

// Estadísticas básicas para el dashboard
$totalTareas = count($tareas);
$totalActivas = count($tareasActivas);
$totalPendientes = 0;
$totalCompletadas = 0;

foreach ($tareas as $tarea) {
    if (strtolower($tarea['estado_nombre']) === 'pendiente') {
        $totalPendientes++;
    } elseif (strtolower($tarea['estado_nombre']) === 'completada' || strtolower($tarea['estado_nombre']) === 'calificada') {
        $totalCompletadas++;
    }
}
?>
<!-- Asegurarse de que jQuery esté cargado -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión de Tareas - Colegio San Francisco de Asís</title>
        <link rel="icon" href="<?= BASE_URL ?>/public/assets/images/chart-line-solid.svg">
    </head>

    <body class="bg-light">

        <!-- Include Sidebar -->
        <?php require_once(LAYOUTS_PATH . '/sidebar_teacher.php'); ?>

        <div class="content">
            <!-- Include Header -->
            <?php require_once(LAYOUTS_PATH . '/header.php'); ?>

            <h1 class="position-relative header-page">Gestión de Tareas</h1>
            <div class="container-fluid py-4">
                <!-- Tarjetas de Estadísticas -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">
                    <!-- Tarjeta de Tareas Totales -->
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm bg-primary bg-opacity-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box bg-primary text-white rounded-3 me-3">
                                        <i class="fas fa-tasks fa-fw fa-lg"></i>
                                    </div>
                                    <h5 class="card-title mb-0 text-primary">Tareas Totales</h5>
                                </div>
                                <h2 class="display-5 mb-0 fw-bold"><?= $totalTareas ?></h2>
                                <p class="text-muted mb-0">Tareas gestionadas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de Tareas Activas -->
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm bg-success bg-opacity-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box bg-success text-white rounded-3 me-3">
                                        <i class="fas fa-clipboard-list fa-fw fa-lg"></i>
                                    </div>
                                    <h5 class="card-title mb-0 text-success">Tareas Activas</h5>
                                </div>
                                <h2 class="display-5 mb-0 fw-bold"><?= $totalActivas ?></h2>
                                <p class="text-muted mb-0">En progreso</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de Tareas Pendientes -->
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm bg-warning bg-opacity-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box bg-warning text-white rounded-3 me-3">
                                        <i class="fas fa-clock fa-fw fa-lg"></i>
                                    </div>
                                    <h5 class="card-title mb-0 text-warning">Pendientes</h5>
                                </div>
                                <h2 class="display-5 mb-0 fw-bold"><?= $totalPendientes ?></h2>
                                <p class="text-muted mb-0">Sin revisar</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de Tareas Completadas -->
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm bg-info bg-opacity-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box bg-info text-white rounded-3 me-3">
                                        <i class="fas fa-check-circle fa-fw fa-lg"></i>
                                    </div>
                                    <h5 class="card-title mb-0 text-info">Completadas</h5>
                                </div>
                                <h2 class="display-5 mb-0 fw-bold"><?= $totalCompletadas ?></h2>
                                <p class="text-muted mb-0">Finalizadas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Nueva Tarea Section -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm" data-aos="fade-up">
                            <div class="card-header bg-white py-3">
                                <h2 class="h5 mb-0 text-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Nueva Tarea
                                </h2>
                            </div>
                            <div class="card-body">
                                <form id="formCrearTarea" class="needs-validation" novalidate>
                                    <div class="mb-4">
                                        <input id="titulo" name="titulo" type="text" class="form-control form-control-lg bg-light" 
                                               placeholder="Título de la Tarea" required>
                                        <div class="invalid-feedback">
                                            Por favor, ingrese un título para la tarea.
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <textarea id="descripcion" name="descripcion" class="form-control bg-light" rows="4" 
                                                  placeholder="Descripción detallada de la tarea" required></textarea>
                                        <div class="invalid-feedback">
                                            La descripción es obligatoria.
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label for="fecha_entrega" class="form-label text-muted">Fecha de Entrega</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                                <input type="datetime-local" class="form-control bg-light border-start-0" 
                                                       id="fecha_entrega" name="fecha_entrega" required>
                                            </div>
                                            <div class="invalid-feedback">
                                                Seleccione una fecha de entrega válida.
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="grupo_id" class="form-label text-muted">Grupo</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-users"></i>
                                                </span>
                                                <select class="form-select bg-light border-start-0" id="grupo_id" name="grupo_id" required>
                                                    <option value="" selected disabled>Seleccione un grupo...</option>
                                                    <?php foreach ($grupos as $grupo): ?>
                                                        <option value="<?= $grupo['id'] ?>">
                                                            <?= htmlspecialchars($grupo['nombre']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback">
                                                Seleccione un grupo.
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="materia_id" class="form-label text-muted">Materia</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-book"></i>
                                                </span>
                                                <select class="form-select bg-light border-start-0" id="materia_id" name="materia_id" required>
                                                    <option value="" selected disabled>Seleccione una materia...</option>
                                                    <?php foreach ($materias as $materia): ?>
                                                        <option value="<?= $materia['id'] ?>">
                                                            <?= htmlspecialchars($materia['nombre']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback">
                                                Seleccione una materia.
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mt-3">
                                            <label for="profesor_id" class="form-label text-muted">Profesor Titular</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-user-tie"></i>
                                                </span>
                                                <select class="form-select bg-light border-start-0" id="profesor_id" name="profesor_id">
                                                    <option value="" selected disabled>Seleccione un profesor...</option>
                                                    <?php foreach ($profesores as $profesor): ?>
                                                        <option value="<?= $profesor['id'] ?>">
                                                            <?= htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellidos']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button id="submit" type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-plus-circle me-2"></i>Crear Tarea
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tareas Activas Section -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h2 class="h5 mb-0 text-success">
                                    <i class="fas fa-clipboard-list me-2"></i>Tareas Activas
                                </h2>
                                <span class="badge bg-success rounded-pill"><?= count($tareasActivas) ?></span>
                            </div>
                            <div class="card-body p-0">
                                <?php if (count($tareasActivas) > 0): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($tareasActivas as $tarea): 
                                            $fechaEntrega = strtotime($tarea['fecha_entrega']);
                                            $hoy = strtotime('today');
                                            $diasRestantes = round(($fechaEntrega - $hoy) / 86400);
                                            
                                            if ($diasRestantes < 0) {
                                                $badgeClass = 'bg-danger';
                                                $badgeText = 'Vencida';
                                            } elseif ($diasRestantes == 0) {
                                                $badgeClass = 'bg-warning';
                                                $badgeText = 'Hoy';
                                            } elseif ($diasRestantes == 1) {
                                                $badgeClass = 'bg-warning';
                                                $badgeText = 'Mañana';
                                            } else {
                                                $badgeClass = 'bg-info';
                                                $badgeText = "$diasRestantes días";
                                            }
                                        ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom py-3 px-4">
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= htmlspecialchars($tarea['titulo']) ?></h6>
                                                    <div class="small text-muted">
                                                        <i class="far fa-calendar-alt me-1"></i> 
                                                        Fecha entrega: <?php echo date('d/m/Y H:i', strtotime($tarea['fecha_entrega'])); ?>
                                                    </div>
                                                </div>
                                                <span class="badge <?= $badgeClass ?> rounded-pill">
                                                    <?= $badgeText ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-clipboard text-muted fa-3x mb-3"></i>
                                        <h5 class="text-muted">No hay tareas activas</h5>
                                        <p class="text-muted small">Las tareas activas aparecerán aquí</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white py-3">
                                <a href="<?= BASE_URL ?>?page=assigned_tasks&role=teacher" class="btn btn-sm btn-outline-success w-100">
                                    Ver todas las tareas <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Tareas -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h2 class="h5 mb-0 text-dark">
                                    <i class="fas fa-history me-2"></i>Historial de Tareas
                                </h2>
                                <div>
                                    <input type="text" id="searchTareas" class="form-control form-control-sm" placeholder="Buscar tarea...">
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="tareasTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Título</th>
                                                <th>Materia</th>
                                                <th>Grupo</th>
                                                <th>Fecha Creación</th>
                                                <th>Fecha Entrega</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($tareas) > 0): ?>
                                                <?php foreach ($tareas as $tarea): 
                                                    $estadoClass = match(strtolower($tarea['estado_nombre'])) {
                                                        'pendiente' => 'bg-warning',
                                                        'en_progreso' => 'bg-primary',
                                                        'completada' => 'bg-success',
                                                        'calificada' => 'bg-info',
                                                        'vencida' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                ?>
                                                    <tr>
                                                        <td class="ps-4">
                                                            <div class="fw-semibold"><?= htmlspecialchars($tarea['titulo']) ?></div>
                                                            <div class="small text-muted" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                <?= htmlspecialchars($tarea['descripcion'] ?? 'Sin descripción') ?>
                                                            </div>
                                                        </td>
                                                        <td><?= htmlspecialchars($tarea['materia_nombre']) ?></td>
                                                        <td><?= htmlspecialchars($tarea['grupo_nombre']) ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="far fa-calendar-alt text-muted me-2"></i>
                                                                <?= date('d/m/Y', strtotime($tarea['fecha_creacion'])) ?>
                                                            </div>
                                                            <div class="small text-muted">
                                                                <?= date('H:i', strtotime($tarea['fecha_creacion'])) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="far fa-calendar-check text-muted me-2"></i>
                                                                <?= date('d/m/Y', strtotime($tarea['fecha_entrega'])) ?>
                                                            </div>
                                                            <div class="small text-muted">
                                                                <?= date('H:i', strtotime($tarea['fecha_entrega'])) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge <?= $estadoClass ?> rounded-pill d-inline-flex align-items-center px-3 py-1">
                                                                <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                                                <?= ucfirst(htmlspecialchars($tarea['estado_nombre'])) ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center py-5">
                                                        <i class="fas fa-tasks text-muted fa-3x mb-3"></i>
                                                        <h5 class="text-muted">No hay tareas registradas</h5>
                                                        <p class="text-muted small">Crea tu primera tarea usando el formulario</p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white py-3">
                                <nav aria-label="Paginación de tareas">
                                    <ul class="pagination justify-content-center mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Siguiente</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                // Validación del formulario Bootstrap
                const forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
                
                // Enviar el formulario vía AJAX (mantener lógica existente)
                $('#formCrearTarea').submit(function (e) {
                    e.preventDefault();
                    
                    // Validación de fecha
                    let fechaEntrega = new Date($('#fecha_entrega').val());
                    let ahora = new Date();
                    if (fechaEntrega <= ahora) {
                        showAlert('Error', 'La fecha de entrega debe ser en el futuro.', 'error');
                        return false;
                    }

                    let formData = $(this).serialize();
                    console.log("Enviando datos:", formData);
                    $.ajax({
                        url: '/GestiondeTareas/app/controllers/GestionTareaController.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function (response) {
                            console.log("Respuesta del servidor:", response);
                            if (response.success) {
                                showAlert('Éxito', 'Tarea creada correctamente', 'success');
                                // Limpiar el formulario
                                $('#formCrearTarea').removeClass('was-validated')[0].reset();
                                // Recargar la página después de 1.5 segundos
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                showAlert('Error', response.message || 'Error al crear la tarea', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            showAlert('Error', 'Ocurrió un error en la comunicación con el servidor', 'error');
                            console.error("Error AJAX:", error);
                        }
                    });
                });
                
                // Búsqueda en la tabla de tareas
                $("#searchTareas").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#tareasTable tbody tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
                
                // Inicializar tooltips de Bootstrap
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
        <script src="<?= BASE_URL ?>/public/assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= BASE_URL ?>/public/assets/js/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({
                duration: 800,
                once: true
            });
        </script>

        <!-- Agregar estilo adicional para los elementos de la interfaz -->
        <style>
            /* Estilos para las tarjetas de estadísticas */
            .icon-box {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
                flex-shrink: 0;
            }
            
            /* Transición suave para hover en filas de tabla */
            #tareasTable tbody tr {
                transition: all 0.2s ease;
            }
            
            #tareasTable tbody tr:hover {
                background-color: rgba(0,0,0,0.03);
            }
            
            /* Estilo para el buscador */
            #searchTareas {
                min-width: 250px;
                border-radius: 20px;
                padding-left: 2.5rem;
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>');
                background-repeat: no-repeat;
                background-position: 12px center;
                background-size: 14px;
            }
            
            /* Ajustar tamaños para responsive */
            @media (max-width: 768px) {
                .display-5 {
                    font-size: 2rem;
                }
            }
        </style>
    </body>

</html>