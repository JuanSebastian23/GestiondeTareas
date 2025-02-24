<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>
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
                            <form>
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-lg" placeholder="Título de la Tarea" required>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" rows="4" placeholder="Descripción detallada de la tarea" required></textarea>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha de Entrega</label>
                                        <input type="datetime-local" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Grupo</label>
                                        <select class="form-select" required>
                                            <option value="">Seleccionar Grupo</option>
                                            <option value="1">1° Año A</option>
                                            <option value="2">1° Año B</option>
                                            <option value="3">2° Año A</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">
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
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <h6 class="mb-1">Proyecto de Ciencias</h6>
                                        <small class="text-muted">Entrega: 15/03/2024 - 1° Año A</small>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <h6 class="mb-1">Trabajo Práctico de Historia</h6>
                                        <small class="text-muted">Entrega: 18/03/2024 - 2° Año A</small>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
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
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-download me-2"></i>Exportar
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Título</th>
                                            <th>Grupo</th>
                                            <th>Fecha Creación</th>
                                            <th>Fecha Entrega</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Proyecto Final Matemáticas</td>
                                            <td>1° Año A</td>
                                            <td>01/03/2024</td>
                                            <td>15/03/2024</td>
                                            <td><span class="badge bg-primary">En Curso</span></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></button>
                                                    <button class="btn btn-outline-warning btn-sm"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ensayo de Literatura</td>
                                            <td>2° Año A</td>
                                            <td>28/02/2024</td>
                                            <td>10/03/2024</td>
                                            <td><span class="badge bg-green">Completada</span></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></button>
                                                    <button class="btn btn-outline-warning btn-sm"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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