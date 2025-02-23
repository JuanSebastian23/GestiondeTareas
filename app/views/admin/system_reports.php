<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<h1 class="position-relative header-page">Reportes del Sistema</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <!-- Estadísticas Generales -->
        <div class="statistics mega" data-aos="fade-up">
            <h2 class="section-header">Métricas del Sistema</h2>
            <div class="row">
                <div class="col-lg-4 box text-center">
                    <i class="fa-solid fa-users c-blue"></i>
                    <span class="d-block" data-goal="168">0</span>
                    Usuarios Activos
                </div>
                <div class="col-lg-4 box text-center">
                    <i class="fa-solid fa-tasks c-green"></i>
                    <span class="d-block" data-goal="523">0</span>
                    Tareas Creadas
                </div>
                <div class="col-lg-4 box text-center">
                    <i class="fa-solid fa-clock c-orange"></i>
                    <span class="d-block" data-goal="98">0</span>
                    Uso del Sistema
                </div>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="latest mega" data-aos="fade-up">
            <h2 class="section-header">Actividad Reciente</h2>
            <div class="data">
                <div class="d-flex align-items-center item">
                    <i class="fa-solid fa-user-plus fa-2x c-blue me-3"></i>
                    <div class="info">
                        <h3>Nuevo Usuario Registrado</h3>
                        <p>Profesor: Carlos Martínez - Hace 2 horas</p>
                    </div>
                    <button class="btn btn-sm btn-primary">Ver Detalles</button>
                </div>
                <div class="d-flex align-items-center item">
                    <i class="fa-solid fa-user-group fa-2x c-green me-3"></i>
                    <div class="info">
                        <h3>Grupo Creado</h3>
                        <p>2° Año B - Hace 5 horas</p>
                    </div>
                    <button class="btn btn-sm btn-primary">Ver Detalles</button>
                </div>
                <div class="d-flex align-items-center item">
                    <i class="fa-solid fa-book fa-2x c-orange me-3"></i>
                    <div class="info">
                        <h3>Nueva Materia Asignada</h3>
                        <p>Matemáticas - 1° Año A - Hace 1 día</p>
                    </div>
                    <button class="btn btn-sm btn-primary">Ver Detalles</button>
                </div>
            </div>
        </div>

        <!-- Resumen del Sistema -->
        <div class="table mega" data-aos="fade-up">
            <h2 class="section-header">Resumen del Sistema</h2>
            <div class="responsive-table">
                <table>
                    <thead>
                        <tr>
                            <th>Módulo</th>
                            <th>Estado</th>
                            <th>Uso de Recursos</th>
                            <th>Última Actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gestión de Usuarios</td>
                            <td><span class="label bg-green">Activo</span></td>
                            <td>25%</td>
                            <td>Hace 1 hora</td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="fas fa-sync"></i></button>
                                <button class="btn btn-sm btn-info"><i class="fas fa-info-circle"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Sistema de Tareas</td>
                            <td><span class="label bg-green">Activo</span></td>
                            <td>45%</td>
                            <td>Hace 30 minutos</td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="fas fa-sync"></i></button>
                                <button class="btn btn-sm btn-info"><i class="fas fa-info-circle"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
