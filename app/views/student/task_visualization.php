<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<h1 class="position-relative header-page">Mis Tareas</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <!-- Contador de Tareas -->
        <div class="statistics mega" data-aos="fade-up">
            <div>
                <h2 class="section-header">Estado de Mis Tareas</h2>
                <span class="section-des">Resumen de tareas asignadas</span>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 box text-center">
                    <i class="fa-solid fa-clock c-orange"></i>
                    <span class="d-block" data-goal="5">0</span>
                    Pendientes
                </div>
                <div class="col-lg-4 col-md-6 box text-center">
                    <i class="fa-solid fa-spinner c-blue"></i>
                    <span class="d-block" data-goal="3">0</span>
                    En Progreso
                </div>
                <div class="col-lg-4 col-md-6 box text-center">
                    <i class="fa-regular fa-circle-check c-green"></i>
                    <span class="d-block" data-goal="8">0</span>
                    Completadas
                </div>
            </div>
        </div>
        <!-- Listado de Tareas -->
        <div class="table mega" data-aos="fade-up">
            <div>
                <h2 class="section-header">Todas mis Tareas</h2>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="section-des">Lista completa de tareas asignadas</span>
                    <div class="filters">
                        <select class="form-select form-select-sm d-inline-block w-auto me-2">
                            <option>Todas las Materias</option>
                            <option>Matemáticas</option>
                            <option>Literatura</option>
                            <option>Ciencias</option>
                        </select>
                        <select class="form-select form-select-sm d-inline-block w-auto">
                            <option>Todos los Estados</option>
                            <option>Pendiente</option>
                            <option>En Progreso</option>
                            <option>Completada</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="responsive-table">
                <table>
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

        <!-- Tareas Próximas -->
        <div class="tasks mega" data-aos="fade-up">
            <div>
                <h2 class="section-header">Tareas Próximas a Vencer</h2>
                <span class="section-des">Tareas con fecha límite cercana</span>
            </div>
            <div class="data">
                <div class="d-flex align-items-center item">
                    <div class="info">
                        <h3>Proyecto de Matemáticas</h3>
                        <p>Entrega: Mañana - 8:00 AM</p>
                    </div>
                    <div>
                        <span class="label bg-orange">Pendiente</span>
                        <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                    </div>
                </div>
                <div class="d-flex align-items-center item">
                    <div class="info">
                        <h3>Ensayo de Literatura</h3>
                        <p>Entrega: En 2 días</p>
                    </div>
                    <div>
                        <span class="label bg-blue">En Progreso</span>
                        <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Cargar tareas con AJAX -->
<script>
    function cargarTareas() {
    fetch('http://localhost/GestiondeTareas/app/views/student/listar_tareas.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('tareas-list').innerHTML = data;
        })
        .catch(error => console.error('Error cargando tareas:', error));
}

document.addEventListener('DOMContentLoaded', cargarTareas);
</script>
