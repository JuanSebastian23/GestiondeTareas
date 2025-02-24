<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<div class="container-fluid py-4">
    <h1 class="position-relative header-page" data-aos="fade-down" data-aos-duration="800">Tareas Completadas</h1>
    
    <!-- Estadísticas de tareas -->
    <div class="row g-4 mb-5">
        <div class="col-xl-4 col-md-4 mb-4" data-aos="fade-right" data-aos-delay="100" data-aos-duration="1000">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="circle-icon bg-success-subtle mb-3">
                        <i class="fa-solid fa-check-double fa-2x text-success"></i>
                    </div>
                    <h3 class="counter display-5 fw-bold text-success" data-goal="15">0</h3>
                    <p class="text-muted mb-0">Total Completadas</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="circle-icon bg-primary-subtle mb-3">
                        <i class="fa-solid fa-clock fa-2x text-primary"></i>
                    </div>
                    <h3 class="counter display-5 fw-bold text-primary" data-goal="3">0</h3>
                    <p class="text-muted mb-0">A Tiempo</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4 mb-4" data-aos="fade-left" data-aos-delay="500" data-aos-duration="1000">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="circle-icon bg-danger-subtle mb-3">
                        <i class="fa-solid fa-calendar fa-2x text-danger"></i>
                    </div>
                    <h3 class="counter display-5 fw-bold text-danger" data-goal="2">0</h3>
                    <p class="text-muted mb-0">Retrasadas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de tareas completadas -->
    <div class="card shadow-sm" data-aos="zoom-in" data-aos-delay="700" data-aos-duration="1000">
        <div class="card-header bg-white">
            <h2 class="card-title h5 text-primary mb-0">Historial de Tareas Completadas</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Tarea</th>
                            <th scope="col">Materia</th>
                            <th scope="col">Fecha Entrega</th>
                            <th scope="col">Calificación</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Comentarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-medium">Proyecto Final</td>
                            <td>Matemáticas</td>
                            <td><span class="badge bg-light text-dark">10 Mar 2024</span></td>
                            <td><span class="badge bg-success">95/100</span></td>
                            <td><span class="badge bg-success rounded-pill">Excelente</span></td>
                            <td>Trabajo excepcional</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Ensayo Literatura</td>
                            <td>Español</td>
                            <td><span class="badge bg-light text-dark">05 Mar 2024</span></td>
                            <td><span class="badge bg-primary">88/100</span></td>
                            <td><span class="badge bg-primary rounded-pill">Muy Bien</span></td>
                            <td>Buenos argumentos</td>
                        </tr>
                    </tbody>
                </table>
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
