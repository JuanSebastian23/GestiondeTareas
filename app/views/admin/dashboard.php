<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

// Datos estáticos para demostración de interfaz
$statsUsuarios = [
    'total' => 250,
    'estudiantes' => 200,
    'profesores' => 45,
    'admins' => 5,
    'activos' => 230,
    'inactivos' => 20
];

$statsGrupos = [
    'total' => 15,
    'activos' => 12
];

$statsMaterias = [
    'total' => 45,
    'activas' => 40
];
?>

<!-- Incluir el CSS específico para el dashboard admin -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/admin_dashboard.css">

<h1 class="position-relative header-page">Panel de Administración</h1>

<div class="dashboard-page">
    <!-- Resumen General -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-4">
                    <h2 class="fs-4 fw-bold text-secondary mb-0">Bienvenido al Panel de Administración</h2>
                    <p class="text-muted mb-0">Gestiona usuarios, grupos y materias del sistema</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas Estadísticas Principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 card-stats">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 me-3">
                            <i class="fas fa-users fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Usuarios</h5>
                    </div>
                    <h2 class="mb-2 stats-number"><?= $statsUsuarios['total'] ?></h2>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-success rounded-pill badge-stat">
                            <i class="fas fa-user-check me-1"></i><?= $statsUsuarios['activos'] ?> activos
                        </span>
                        <span class="badge bg-danger rounded-pill badge-stat">
                            <i class="fas fa-user-slash me-1"></i><?= $statsUsuarios['inactivos'] ?> inactivos
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=user_management&role=admin" class="btn btn-sm btn-light w-100">
                        Gestionar Usuarios <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 me-3">
                            <i class="fas fa-user-graduate fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Estudiantes</h5>
                    </div>
                    <h2 class="mb-2"><?= $statsUsuarios['estudiantes'] ?></h2>
                    <div class="progress" style="height: 6px;">
                        <?php $porcentaje = ($statsUsuarios['estudiantes'] / $statsUsuarios['total']) * 100; ?>
                        <div class="progress-bar bg-success" style="width: <?= $porcentaje ?>%" 
                             aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted"><?= number_format($porcentaje, 1) ?>% del total de usuarios</small>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=user_management&role=admin&filter=estudiante" class="btn btn-sm btn-light w-100">
                        Ver Estudiantes <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3 me-3">
                            <i class="fas fa-chalkboard-teacher fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Profesores</h5>
                    </div>
                    <h2 class="mb-2"><?= $statsUsuarios['profesores'] ?></h2>
                    <div class="progress" style="height: 6px;">
                        <?php $porcentaje = ($statsUsuarios['profesores'] / $statsUsuarios['total']) * 100; ?>
                        <div class="progress-bar bg-warning" style="width: <?= $porcentaje ?>%" 
                             aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted"><?= number_format($porcentaje, 1) ?>% del total de usuarios</small>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=user_management&role=admin&filter=profesor" class="btn btn-sm btn-light w-100">
                        Ver Profesores <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 me-3">
                            <i class="fas fa-user-shield fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Administradores</h5>
                    </div>
                    <h2 class="mb-2"><?= $statsUsuarios['admins'] ?></h2>
                    <div class="progress" style="height: 6px;">
                        <?php $porcentaje = ($statsUsuarios['admins'] / $statsUsuarios['total']) * 100; ?>
                        <div class="progress-bar bg-info" style="width: <?= $porcentaje ?>%" 
                             aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted"><?= number_format($porcentaje, 1) ?>% del total de usuarios</small>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=user_management&role=admin&filter=administrador" class="btn btn-sm btn-light w-100">
                        Ver Administradores <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Estadísticas -->
    <div class="row mb-4 g-3">
        <!-- Distribución de Usuarios -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-semibold">Distribución de Usuarios</h5>
                </div>
                <div class="card-body">
                    <div id="usersChartContainer" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <!-- Grupos y Materias -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-semibold">Grupos y Materias</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Grupos</h5>
                                        <span class="badge bg-primary rounded-pill"><?= $statsGrupos['total'] ?></span>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <div style="width: 120px; height: 120px;">
                                            <!-- Circle Visualization -->
                                            <div class="position-relative" style="width: 120px; height: 120px;">
                                                <svg width="120" height="120" viewBox="0 0 120 120">
                                                    <circle cx="60" cy="60" r="54" fill="none" stroke="#e9ecef" stroke-width="12"></circle>
                                                    <?php $ratio = $statsGrupos['activos'] / $statsGrupos['total']; ?>
                                                    <circle cx="60" cy="60" r="54" fill="none" stroke="#0d6efd" stroke-width="12"
                                                            stroke-dasharray="<?= 339.292 * $ratio ?> 339.292"
                                                            stroke-dashoffset="0" 
                                                            transform="rotate(-90 60 60)"></circle>
                                                </svg>
                                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                                    <h3 class="mb-0 fw-bold"><?= $statsGrupos['activos'] ?></h3>
                                                    <div class="small text-muted">Activos</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <div class="mb-2">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <span>Activos: <?= $statsGrupos['activos'] ?></span>
                                            </div>
                                            <div>
                                                <i class="fas fa-times-circle text-danger"></i>
                                                <span>Inactivos: <?= $statsGrupos['total'] - $statsGrupos['activos'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <a href="<?= BASE_URL ?>?page=group_management&role=admin" class="btn btn-sm btn-light w-100">
                                        Gestionar Grupos <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Materias</h5>
                                        <span class="badge bg-success rounded-pill"><?= $statsMaterias['total'] ?></span>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <div style="width: 120px; height: 120px;">
                                            <!-- Circle Visualization -->
                                            <div class="position-relative" style="width: 120px; height: 120px;">
                                                <svg width="120" height="120" viewBox="0 0 120 120">
                                                    <circle cx="60" cy="60" r="54" fill="none" stroke="#e9ecef" stroke-width="12"></circle>
                                                    <?php $ratio = $statsMaterias['activas'] / $statsMaterias['total']; ?>
                                                    <circle cx="60" cy="60" r="54" fill="none" stroke="#198754" stroke-width="12"
                                                            stroke-dasharray="<?= 339.292 * $ratio ?> 339.292"
                                                            stroke-dashoffset="0" 
                                                            transform="rotate(-90 60 60)"></circle>
                                                </svg>
                                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                                    <h3 class="mb-0 fw-bold"><?= $statsMaterias['activas'] ?></h3>
                                                    <div class="small text-muted">Activas</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <div class="mb-2">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <span>Activas: <?= $statsMaterias['activas'] ?></span>
                                            </div>
                                            <div>
                                                <i class="fas fa-times-circle text-danger"></i>
                                                <span>Inactivas: <?= $statsMaterias['total'] - $statsMaterias['activas'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <a href="<?= BASE_URL ?>?page=subject_management&role=admin" class="btn btn-sm btn-light w-100">
                                        Gestionar Materias <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-semibold">Accesos Rápidos</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="<?= BASE_URL ?>?page=user_management&role=admin" class="card hover-effect text-decoration-none h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                        <i class="fas fa-users-gear fa-fw"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-dark">Gestión de Usuarios</h5>
                                        <span class="text-muted">Administrar perfiles de usuarios</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?= BASE_URL ?>?page=group_management&role=admin" class="card hover-effect text-decoration-none h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                        <i class="fas fa-user-group fa-fw"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-dark">Gestión de Grupos</h5>
                                        <span class="text-muted">Administrar grupos de clase</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?= BASE_URL ?>?page=subject_management&role=admin" class="card hover-effect text-decoration-none h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                                        <i class="fas fa-book fa-fw"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-dark">Gestión de Materias</h5>
                                        <span class="text-muted">Administrar asignaturas</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hover-effect {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-effect:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}
</style>

<!-- Incluyo Chart.js para los gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de distribución de usuarios
    if (document.getElementById('usersChartContainer')) {
        const ctx = document.createElement('canvas');
        ctx.id = 'usersDistributionChart';
        document.getElementById('usersChartContainer').appendChild(ctx);
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Estudiantes', 'Profesores', 'Administradores'],
                datasets: [{
                    data: [<?= $statsUsuarios['estudiantes'] ?>, <?= $statsUsuarios['profesores'] ?>, <?= $statsUsuarios['admins'] ?>],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(23, 162, 184, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(23, 162, 184, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
