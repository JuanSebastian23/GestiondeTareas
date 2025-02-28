<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

// Cargar los controladores necesarios
require_once(CONTROLLERS_PATH . '/UsuarioController.php');
require_once(CONTROLLERS_PATH . '/GrupoController.php');
require_once(CONTROLLERS_PATH . '/MateriaController.php');

// Inicializar los controladores
$usuarioController = new UsuarioController();
$grupoController = new GrupoController();
$materiaController = new MateriaController();

// Obtener datos de usuarios
$usuarioModel = new Usuario();
$statsUsuariosPorRol = $usuarioModel->getConteoUsuariosPorRol();
$totalUsuarios = 0;
$totalEstudiantes = 0;
$totalProfesores = 0;
$totalAdmins = 0;
$totalActivos = 0;
$totalInactivos = 0;

// Procesar los datos de usuarios
foreach ($statsUsuariosPorRol as $stat) {
    $totalUsuarios += $stat['total'];
    $totalActivos += $stat['activos'];
    $totalInactivos += $stat['inactivos'];
    
    switch(strtolower($stat['rol'])) {
        case 'estudiante':
            $totalEstudiantes = $stat['total'];
            $estudiantesActivos = $stat['activos'];
            break;
        case 'profesor':
            $totalProfesores = $stat['total'];
            $profesoresActivos = $stat['activos'];
            break;
        case 'administrador':
            $totalAdmins = $stat['total'];
            $adminsActivos = $stat['activos'];
            break;
    }
}

// Obtener datos de grupos
$grupoStats = $grupoController->obtenerEstadisticas();

// Corregir los datos de grupos si es necesario
$statsGrupos = [
    'total' => $grupoStats['total_grupos'] ?? 0,
    'activos' => $grupoStats['grupos_activos'] ?? $grupoStats['total_grupos'] ?? 0,
];
$statsGrupos['inactivos'] = $statsGrupos['total'] - $statsGrupos['activos'];

// Verificar si hay inconsistencias
if ($statsGrupos['activos'] > $statsGrupos['total']) {
    $statsGrupos['activos'] = $statsGrupos['total'];
    $statsGrupos['inactivos'] = 0;
} elseif ($statsGrupos['activos'] < 0) {
    $statsGrupos['activos'] = 0;
    $statsGrupos['inactivos'] = $statsGrupos['total'];
}

// Obtener datos de materias
$materiaStats = $materiaController->obtenerEstadisticas();

// Corregir los datos de materias si es necesario
$statsMaterias = [
    'total' => $materiaStats['total_materias'] ?? 0,
    'activas' => $materiaStats['materias_activas'] ?? $materiaStats['total_materias'] ?? 0,
];
$statsMaterias['inactivas'] = $statsMaterias['total'] - $statsMaterias['activas'];

// Verificar si hay inconsistencias
if ($statsMaterias['activas'] > $statsMaterias['total']) {
    $statsMaterias['activas'] = $statsMaterias['total'];
    $statsMaterias['inactivas'] = 0;
} elseif ($statsMaterias['activas'] < 0) {
    $statsMaterias['activas'] = 0;
    $statsMaterias['inactivas'] = $statsMaterias['total'];
}

// Depuración (opcional)
// echo "<pre>Grupos: "; print_r($statsGrupos); echo "</pre>";
// echo "<pre>Materias: "; print_r($statsMaterias); echo "</pre>";
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
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
        <!-- Tarjeta de Usuarios Totales -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 me-3">
                            <i class="fas fa-users fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Usuarios</h5>
                    </div>
                    <h2 class="stats-number mb-2"><?= $totalUsuarios ?></h2>
                    <div class="d-flex flex-wrap stats-badges-container">
                        <span class="badge bg-success rounded-pill badge-stat me-2 mb-2 mb-md-0">
                            <i class="fas fa-user-check me-1"></i><?= $totalActivos ?> activos
                        </span>
                        <span class="badge bg-danger rounded-pill badge-stat">
                            <i class="fas fa-user-slash me-1"></i><?= $totalInactivos ?> inactivos
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

        <!-- Tarjeta de Estudiantes -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 me-3">
                            <i class="fas fa-user-graduate fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Estudiantes</h5>
                    </div>
                    <h2 class="stats-number mb-2"><?= $totalEstudiantes ?></h2>
                    <div class="progress" style="height: 8px;">
                        <?php $porcentajeEstudiantes = ($totalUsuarios > 0) ? ($totalEstudiantes / $totalUsuarios) * 100 : 0; ?>
                        <div class="progress-bar bg-success" style="width: <?= $porcentajeEstudiantes ?>%" 
                             aria-valuenow="<?= $porcentajeEstudiantes ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="progress-label">
                        <small class="text-muted">Proporción de usuarios</small>
                        <small class="progress-percentage text-success"><?= number_format($porcentajeEstudiantes, 1) ?>%</small>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=user_management&role=admin&filter=estudiante" class="btn btn-sm btn-light w-100">
                        Ver Estudiantes <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Profesores -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3 me-3">
                            <i class="fas fa-chalkboard-teacher fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Profesores</h5>
                    </div>
                    <h2 class="stats-number mb-2"><?= $totalProfesores ?></h2>
                    <div class="progress" style="height: 8px;">
                        <?php $porcentajeProfesores = ($totalUsuarios > 0) ? ($totalProfesores / $totalUsuarios) * 100 : 0; ?>
                        <div class="progress-bar bg-warning" style="width: <?= $porcentajeProfesores ?>%" 
                             aria-valuenow="<?= $porcentajeProfesores ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="progress-label">
                        <small class="text-muted">Proporción de usuarios</small>
                        <small class="progress-percentage text-warning"><?= number_format($porcentajeProfesores, 1) ?>%</small>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=user_management&role=admin&filter=profesor" class="btn btn-sm btn-light w-100">
                        Ver Profesores <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Administradores -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 me-3">
                            <i class="fas fa-user-shield fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Administradores</h5>
                    </div>
                    <h2 class="stats-number mb-2"><?= $totalAdmins ?></h2>
                    <div class="progress" style="height: 8px;">
                        <?php $porcentajeAdmins = ($totalUsuarios > 0) ? ($totalAdmins / $totalUsuarios) * 100 : 0; ?>
                        <div class="progress-bar bg-info" style="width: <?= $porcentajeAdmins ?>%" 
                             aria-valuenow="<?= $porcentajeAdmins ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="progress-label">
                        <small class="text-muted">Proporción de usuarios</small>
                        <small class="progress-percentage text-info"><?= number_format($porcentajeAdmins, 1) ?>%</small>
                    </div>
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
    <div class="row g-3 mb-4">
        <!-- Distribución de Usuarios -->
        <div class="col-lg-6">
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
        <div class="col-lg-6">
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
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-12 col-xl-6">
                                            <!-- Circle Visualization -->
                                            <div class="position-relative circle-chart mb-3 mb-sm-0 mb-lg-3 mb-xl-0" style="width: 100%; max-width: 120px; margin: 0 auto;">
                                                <svg width="100%" height="120" viewBox="0 0 120 120">
                                                    <circle cx="60" cy="60" r="54" fill="none" stroke="#e9ecef" stroke-width="12" class="circle-bg"></circle>
                                                    <?php 
                                                    // Asegurarse de que el ratio es calculado correctamente
                                                    $ratio = ($statsGrupos['total'] > 0) ? ($statsGrupos['activos'] / $statsGrupos['total']) : 0;
                                                    // Limitar el ratio entre 0 y 1 para evitar valores inválidos
                                                    $ratio = max(0, min(1, $ratio));
                                                    ?>
                                                    <circle cx="60" cy="60" r="54" fill="none" stroke="#0d6efd" stroke-width="12"
                                                            stroke-dasharray="<?= 339.292 * $ratio ?> 339.292"
                                                            stroke-dashoffset="0" 
                                                            transform="rotate(-90 60 60)"
                                                            class="circle-progress"></circle>
                                                </svg>
                                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                                    <h3 class="mb-0 fw-bold"><?= $statsGrupos['activos'] ?></h3>
                                                    <div class="small text-muted">Activos</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-12 col-xl-6 d-flex flex-column justify-content-center">
                                            <div class="mb-2">
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                <span>Activos: <?= $statsGrupos['activos'] ?></span>
                                            </div>
                                            <div>
                                                <i class="fas fa-times-circle text-danger me-1"></i>
                                                <span>Inactivos: <?= $statsGrupos['inactivos'] ?></span>
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
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-12 col-xl-6">
                                            <!-- Circle Visualization -->
                                            <div class="position-relative circle-chart mb-3 mb-sm-0 mb-lg-3 mb-xl-0" style="width: 100%; max-width: 120px; margin: 0 auto;">
                                                <svg width="100%" height="120" viewBox="0 0 120 120">
                                                    <circle cx="60" cy="60" r="54" fill="none" stroke="#e9ecef" stroke-width="12" class="circle-bg"></circle>
                                                    <?php 
                                                    // Asegurarse de que el ratio es calculado correctamente
                                                    $ratio = ($statsMaterias['total'] > 0) ? ($statsMaterias['activas'] / $statsMaterias['total']) : 0;
                                                    // Limitar el ratio entre 0 y 1 para evitar valores inválidos
                                                    $ratio = max(0, min(1, $ratio));
                                                    ?>
                                                    <circle cx="60" cy="60" r="54" fill="none" stroke="#198754" stroke-width="12"
                                                            stroke-dasharray="<?= 339.292 * $ratio ?> 339.292"
                                                            stroke-dashoffset="0" 
                                                            transform="rotate(-90 60 60)"
                                                            class="circle-progress"></circle>
                                                </svg>
                                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                                    <h3 class="mb-0 fw-bold"><?= $statsMaterias['activas'] ?></h3>
                                                    <div class="small text-muted">Activas</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-12 col-xl-6 d-flex flex-column justify-content-center">
                                            <div class="mb-2">
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                <span>Activas: <?= $statsMaterias['activas'] ?></span>
                                            </div>
                                            <div>
                                                <i class="fas fa-times-circle text-danger me-1"></i>
                                                <span>Inactivas: <?= $statsMaterias['inactivas'] ?></span>
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
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        <div class="col">
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
                        <div class="col">
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
                        <div class="col">
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
                    data: [<?= $totalEstudiantes ?>, <?= $totalProfesores ?>, <?= $totalAdmins ?>],
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
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

    // Asegurar que los gráficos se redimensionen cuando la ventana cambie de tamaño
    window.addEventListener('resize', function() {
        if (window.Chart && window.Chart.instances) {
            for (let id in window.Chart.instances) {
                window.Chart.instances[id].resize();
            }
        }
    });
});
</script>
