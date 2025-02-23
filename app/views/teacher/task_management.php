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

<body>

    <!-- Include Sidebar -->
    <?php require_once(LAYOUTS_PATH . '/sidebar_teacher.php'); ?>

    <div class="content">
        <!-- Include Header -->
        <?php require_once(LAYOUTS_PATH . '/header.php'); ?>

        <h1 class="position-relative header-page">Gestión de Tareas</h1>
        <div class="dashboard-page">
            <div class="wrapper">
                <!-- Nueva Tarea Section -->
                <div class="quick-draft mega" data-aos="fade-up">
                    <div>
                        <h2 class="section-header">Nueva Tarea</h2>
                        <span class="section-des">Crear una nueva tarea para los estudiantes</span>
                    </div>
                    <form>
                        <input class="d-block mb-3 w-100 p-2" type="text" placeholder="Título de la Tarea" required>
                        <textarea class="d-block mb-3 w-100 p-2" placeholder="Descripción detallada de la tarea" required></textarea>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Entrega</label>
                                <input type="datetime-local" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Grupo</label>
                                <select class="form-control" required>
                                    <option value="">Seleccionar Grupo</option>
                                    <option value="1">1° Año A</option>
                                    <option value="2">1° Año B</option>
                                    <option value="3">2° Año A</option>
                                </select>
                            </div>
                        </div>
                        <input class="save d-block btn btn-primary" type="submit" value="Crear Tarea">
                    </form>
                </div>

                <!-- Tareas Activas Section -->
                <div class="tasks mega" data-aos="fade-up">
                    <div>
                        <h2 class="section-header">Tareas Activas</h2>
                        <span class="section-des">Tareas pendientes de entrega</span>
                    </div>
                    <div class="data">
                        <div class="d-flex align-items-center item">
                            <div class="info">
                                <h3>Proyecto de Ciencias</h3>
                                <p>Entrega: 15/03/2024 - 1° Año A</p>
                            </div>
                            <div class="buttons d-flex gap-2">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center item">
                            <div class="info">
                                <h3>Trabajo Práctico de Historia</h3>
                                <p>Entrega: 18/03/2024 - 2° Año A</p>
                            </div>
                            <div class="buttons d-flex gap-2">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado de Entregas Section -->
                <div class="statistics mega" data-aos="fade-up">
                    <div>
                        <h2 class="section-header">Estado de Entregas</h2>
                        <span class="section-des">Resumen de entregas por tarea</span>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 box text-center">
                            <i class="fa-regular fa-file-lines c-blue"></i>
                            <span class="d-block" data-goal="12">0</span>
                            Tareas Activas
                        </div>
                        <div class="col-lg-3 col-md-6 box text-center">
                            <i class="fa-solid fa-clock c-orange"></i>
                            <span class="d-block" data-goal="25">0</span>
                            Pendientes
                        </div>
                        <div class="col-lg-3 col-md-6 box text-center">
                            <i class="fa-regular fa-circle-check c-green"></i>
                            <span class="d-block" data-goal="89">0</span>
                            Entregadas
                        </div>
                        <div class="col-lg-3 col-md-6 box text-center">
                            <i class="fa-solid fa-xmark c-red"></i>
                            <span class="d-block" data-goal="8">0</span>
                            Sin Entregar
                        </div>
                    </div>
                </div>

                <!-- Tabla de Tareas -->
                <div class="table mega" data-aos="fade-up">
                    <div>
                        <h2 class="section-header">Historial de Tareas</h2>
                    </div>
                    <div class="responsive-table">
                        <table>
                            <thead>
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
                                    <td><span class="label bg-blue">En Curso</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ensayo de Literatura</td>
                                    <td>2° Año A</td>
                                    <td>28/02/2024</td>
                                    <td>10/03/2024</td>
                                    <td><span class="label bg-green">Completada</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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