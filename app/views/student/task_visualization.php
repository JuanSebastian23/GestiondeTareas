<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión solo si no está activa
}

if (!isset($_SESSION['user_id'])) {
    die("Error: Sesión no iniciada. Por favor, inicia sesión.");
}


require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/MateriaController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/MateriaModelController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/EstadoController.php');

$materiaController = new MateriaModelController();
$estadoController = new EstadoController();

$materias = $materiaController->obtenerMateriasEstudiante($_SESSION['user_id']);
$estados = $estadoController->obtenerEstados();
?>

<h1 class="position-relative header-page">Mis Tareas</h1>
<div class="container mt-4">
    <div class="row">
        <!-- Contador de Tareas -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Estado de Mis Tareas</h2>
                    <p class="card-text">Resumen de tareas asignadas</p>
                    <div class="row text-center">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <i class="fa-solid fa-spinner text-warning"></i>
                                    <h5 class="card-title">En Progreso</h5>
                                    <p class="card-text"><span>0</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <i class="fa-regular fa-circle-check text-success"></i>
                                    <h5 class="card-title">Completadas</h5>
                                    <p class="card-text"><span>0</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <i class="far fa-times-circle text-danger"></i>
                                    <h5 class="card-title">Vencida</h5>
                                    <p class="card-text"><span>0</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <i class="far fa-times-circle text-primary"></i>
                                    <h5 class="card-title">Calificada</h5>
                                    <p class="card-text"><span>0</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de Tareas -->
<div class="col-12 mb-4">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Todas mis Tareas</h2>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="card-text">Lista completa de tareas asignadas</p>
                <div class="d-flex">
                    <select id="filter-materia" class="form-select form-select-sm me-2">
                        <option value="">Todas las Materias</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= htmlspecialchars($materia['nombre']) ?>">
                                <?= htmlspecialchars($materia['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select id="filter-estado" class="form-select form-select-sm">
                        <option value="">Todos los Estados</option>
                        <option value="En Progreso">En Progreso</option>
                        <option value="Completada">Completada</option>
                        <option value="Vencida">Vencida</option>
                        <option value="Vencida">Calificada</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tarea</th>
                            <th>Materia</th>
                            <th>Fecha Asignación</th>
                            <th>Fecha Entrega</th>
                            <th>Grupo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tareas-list">
                        <!-- Las tareas se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para subir tarea -->
<div class="modal fade" id="modalSubirTarea" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Subir Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formSubirTarea" enctype="multipart/form-data">
                    <input type="hidden" name="tarea_id" id="modalTareaId">

                    <div class="mb-3">
                        <label for="comentarios" class="form-label">Comentarios:</label>
                        <textarea name="comentarios" id="comentarios" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="archivo" class="form-label">Subir archivo:</label>
                        <input type="file" name="archivo" id="archivo" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Entregar Tarea</button>
                </form>
            </div>
        </div>
    </div>
</div>



        <!-- Tareas Próximas -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Tareas Próximas a Vencer</h2>
                    <p class="card-text">Tareas con fecha límite cercana</p>
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Proyecto de Matemáticas</h5>
                                <small>Entrega: Mañana - 8:00 AM</small>
                            </div>
                            <div>
                                <span class="badge bg-warning">Pendiente</span>
                                <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Ensayo de Literatura</h5>
                                <small>Entrega: En 2 días</small>
                            </div>
                            <div>
                                <span class="badge bg-primary">En Progreso</span>
                                <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/assets/js/Tareas.js"></script>
<script src="public\assets\js\Modal_tarea.js"></script>