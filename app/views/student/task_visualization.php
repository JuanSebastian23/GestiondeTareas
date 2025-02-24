<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<div class="container-fluid py-4">
    <h1 class="position-relative header-page" data-aos="fade-down" data-aos-duration="800">Mis Tareas</h1>

    <!-- Contador de Tareas -->
    <div class="row g-4 mb-5">
        <div class="col-xl-4 col-md-4 mb-4" data-aos="fade-right" data-aos-delay="100" data-aos-duration="1000">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="circle-icon bg-warning-subtle mb-3">
                        <i class="fa-solid fa-clock fa-2x text-warning"></i>
                    </div>
                    <h3 class="counter display-5 fw-bold text-warning" data-goal="5">0</h3>
                    <p class="text-muted mb-0">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="circle-icon bg-primary-subtle mb-3">
                        <i class="fa-solid fa-spinner fa-2x text-primary"></i>
                    </div>
                    <h3 class="counter display-5 fw-bold text-primary" data-goal="3">0</h3>
                    <p class="text-muted mb-0">En Progreso</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 mb-4" data-aos="fade-left" data-aos-delay="500" data-aos-duration="1000">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="circle-icon bg-success-subtle mb-3">
                        <i class="fa-regular fa-circle-check fa-2x text-success"></i>
                    </div>
                    <h3 class="counter display-5 fw-bold text-success" data-goal="8">0</h3>
                    <p class="text-muted mb-0">Completadas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado de Tareas -->
    <div class="card shadow-sm mb-4" data-aos="zoom-in" data-aos-delay="700" data-aos-duration="1000">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h2 class="card-title h5 text-primary mb-0">Todas mis Tareas</h2>
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Tarea</th>
                            <th scope="col">Materia</th>
                            <th scope="col">Fecha Asignación</th>
                            <th scope="col">Fecha Entrega</th>
                            <th scope="col">Grupo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tareas-list">
                        <!-- Las tareas se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tareas Próximas -->
    <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="900" data-aos-duration="1000">
        <div class="card-header bg-white">
            <h2 class="card-title h5 text-primary mb-0">Tareas Próximas a Vencer</h2>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <h6 class="mb-1">Proyecto de Matemáticas</h6>
                        <small class="text-muted">Entrega: Mañana - 8:00 AM</small>
                    </div>
                    <div>
                        <span class="badge bg-warning text-dark rounded-pill">Pendiente</span>
                        <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                    </div>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <h6 class="mb-1">Ensayo de Literatura</h6>
                        <small class="text-muted">Entrega: En 2 días</small>
                    </div>
                    <div>
                        <span class="badge bg-primary rounded-pill">En Progreso</span>
                        <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.circle-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
</style>

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
