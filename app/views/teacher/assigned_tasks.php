<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<h1 class="position-relative header-page">Tareas Asignadas</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <!-- Estadísticas -->
        <div class="statistics mega" data-aos="fade-up">
            <h2 class="section-header">Resumen de Entregas</h2>
            <div class="row">
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-file-alt c-blue"></i>
                    <span class="d-block" data-goal="45">0</span>
                    Total Asignadas
                </div>
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-check-circle c-green"></i>
                    <span class="d-block" data-goal="32">0</span>
                    Entregadas
                </div>
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-clock c-orange"></i>
                    <span class="d-block" data-goal="8">0</span>
                    Pendientes
                </div>
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-exclamation-circle c-red"></i>
                    <span class="d-block" data-goal="5">0</span>
                    Vencidas
                </div>
            </div>
        </div>

        <!-- Tabla de Tareas -->
        <div class="table mega" data-aos="fade-up">
            <h2 class="section-header">Entregas por Revisar</h2>
            <div class="responsive-table">
                <table>
                    <thead>
                        <tr>
                            <th>Tarea</th>
                            <th>Estudiante</th>
                            <th>Grupo</th>
                            <th>Fecha Entrega</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contenido dinámico de tareas -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
