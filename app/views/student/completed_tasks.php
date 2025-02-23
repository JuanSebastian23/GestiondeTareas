<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<h1 class="position-relative header-page">Tareas Completadas</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <!-- Estadísticas de tareas -->
        <div class="statistics mega" data-aos="fade-right" data-aos-delay="250">
            <h2 class="section-header">Estadísticas de Tareas</h2>
            <div class="row">
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-check-double c-green"></i>
                    <span class="d-block" data-goal="15">0</span>
                    Total Completadas
                </div>
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-star c-gold"></i>
                    <span class="d-block" data-goal="12">0</span>
                    Calificación Alta
                </div>
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-clock c-blue"></i>
                    <span class="d-block" data-goal="3">0</span>
                    A Tiempo
                </div>
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-calendar c-orange"></i>
                    <span class="d-block" data-goal="2">0</span>
                    Retrasadas
                </div>
            </div>
        </div>

        <!-- Tabla de tareas completadas -->
        <div class="table mega" data-aos="fade-up" data-aos-delay="300">
            <h2 class="section-header">Historial de Tareas Completadas</h2>
            <div class="responsive-table">
                <table>
                    <thead>
                        <tr>
                            <th>Tarea</th>
                            <th>Materia</th>
                            <th>Fecha Entrega</th>
                            <th>Calificación</th>
                            <th>Estado</th>
                            <th>Comentarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Proyecto Final</td>
                            <td>Matemáticas</td>
                            <td>10 Mar 2024</td>
                            <td>95/100</td>
                            <td><span class="label bg-green">Excelente</span></td>
                            <td>Trabajo excepcional</td>
                        </tr>
                        <tr>
                            <td>Ensayo Literatura</td>
                            <td>Español</td>
                            <td>05 Mar 2024</td>
                            <td>88/100</td>
                            <td><span class="label bg-blue">Muy Bien</span></td>
                            <td>Buenos argumentos</td>
                        </tr>
                        <!-- Más filas según necesites -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
