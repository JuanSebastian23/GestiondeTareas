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

                <div class="row">
                    <!-- Nueva Tarea Section -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm" data-aos="fade-up">
                            <div class="card-header bg-white py-3">
                                <h2 class="h5 mb-0 text-gray-800">Nueva Tarea</h2>
                            </div>
                            <div class="card-body">
                                <form id="formCrearTarea">
                                    <div class="mb-3">
                                        <input id="titulo" name="titulo" type="text" class="form-control form-control-lg" placeholder="Título de la Tarea" required>
                                    </div>
                                    <div class="mb-3">
                                        <textarea id="descripcion"  name="descripcion" class="form-control" rows="4" placeholder="Descripción detallada de la tarea" required></textarea>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Fecha de Entrega</label>
                                            <input type="datetime-local" class="form-control" id="fecha_entrega" name="fecha_entrega" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Grupo</label>
                                            <select class="form-select"  id="grupo_id" name="grupo_id"required>
                                                <option value="">Seleccione un grupo...</option>
                                                <?php foreach ($grupos as $grupo): ?>
                                                <option value="<?= $grupo['id'] ?>">
                                                    <?= htmlspecialchars($grupo['nombre']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">materias</label>
                                            <select class="form-select" id="materia_id" name="materia_id" required>
                                                <option value="">Seleccione una materia...</option>
                                                <?php foreach ($materias as $materia): ?>
                                                <option value="<?= $materia['id'] ?>">
                                                    <?= htmlspecialchars($materia['nombre']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="profesor_id" class="form-label">Profesor Titular</label>
                                            <select class="form-select" id="profesor_id" name="profesor_id">
                                                <option value="">Seleccione un profesor...</option>
                                                <?php foreach ($profesores as $profesor): ?>
                                                <option value="<?= $profesor['id'] ?>">
                                                    <?= htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellidos']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
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
                        <div class="card shadow-sm" data-aos="fade-up">
                            <div class="card-header bg-white py-3">
                                <h2 class="h5 mb-0 text-gray-800">Tareas Activas</h2>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <?php foreach ($tareasActivas as $tarea): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($tarea['titulo']) ?></strong><br>
                                        Entrega: <?= date('d/m/Y H:i', strtotime($tarea['fecha_entrega'])) ?>
                                    </li>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de Entregas Section -->
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm" data-aos="fade-up">
                            <div class="card-header bg-white py-3">
                                <h2 class="h5 mb-0 text-gray-800">Estado de Entregas</h2>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="border rounded p-3 text-center">
                                            <i class="fas fa-file-alt fs-2 text-primary mb-2"></i>
                                            <h3 class="h2 mb-0" data-goal="12">0</h3>
                                            <p class="text-muted mb-0">Tareas Activas</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="border rounded p-3 text-center">
                                            <i class="fas fa-clock fs-2 text-warning mb-2"></i>
                                            <h3 class="h2 mb-0" data-goal="25">0</h3>
                                            <p class="text-muted mb-0">Pendientes</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="border rounded p-3 text-center">
                                            <i class="fa-regular fa-circle-check c-green"></i>
                                            <h3 class="h2 mb-0" data-goal="89">0</h3>
                                            <p class="text-muted mb-0">Entregadas</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="border rounded p-3 text-center">
                                            <i class="fa-solid fa-xmark c-red"></i>
                                            <h3 class="h2 mb-0" data-goal="8">0</h3>
                                            <p class="text-muted mb-0">Sin Entregar</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Tareas -->
                    <div class="col-12">
                        <div class="card shadow-sm" data-aos="fade-up">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h2 class="h5 mb-0 text-gray-800">Historial de Tareas</h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Título</th>
                                                <th>Materia</th>
                                                <th>Grupo</th>
                                                <th>Fecha Creación</th>
                                                <th>Fecha Entrega</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tareas as $tarea): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($tarea['titulo']) ?></td>
                                                <td><?= htmlspecialchars($tarea['materia_nombre']) ?></td>
                                                <td><?= htmlspecialchars($tarea['grupo_nombre']) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($tarea['fecha_entrega'])) ?></td>
                                                <td><?= htmlspecialchars($tarea['estado_nombre']) ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                    <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
    // Enviar el formulario vía AJAX
    $('#formCrearTarea').submit(function (e) {
        e.preventDefault();

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
                    alert('Tarea creada correctamente');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    });
});

            $(document).ready(function () {
                $('#formCrearTarea').submit(function (e) {
                    let fechaEntrega = new Date($('#fecha_entrega').val());
                    let ahora = new Date();

                    if (fechaEntrega <= ahora) {
                        alert('La fecha de entrega debe ser en el futuro.');
                        e.preventDefault();
                    }
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
    </body>

</html>