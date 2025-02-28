<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

// Datos estáticos para demostración de interfaz
$statsTareas = [
    'total' => 45,
    'pendientes' => 12,
    'completadas' => 28,
    'vencidas' => 5
];

$statsGrupos = [
    'total' => 4,
    'estudiantes' => 120
];

$statsRecientes = [
    [
        'id' => 1,
        'titulo' => 'Proyecto Final de Ciencias',
        'grupo' => 'Grupo A - 3º ESO',
        'fecha_entrega' => '2023-12-15',
        'completadas' => 18,
        'pendientes' => 7
    ],
    [
        'id' => 2,
        'titulo' => 'Ejercicios de Matemáticas',
        'grupo' => 'Grupo B - 2º ESO',
        'fecha_entrega' => '2023-12-10',
        'completadas' => 22,
        'pendientes' => 3
    ],
    [
        'id' => 3,
        'titulo' => 'Ensayo de Literatura',
        'grupo' => 'Grupo C - 4º ESO',
        'fecha_entrega' => '2023-12-18',
        'completadas' => 15,
        'pendientes' => 10
    ],
    [
        'id' => 4,
        'titulo' => 'Práctica de Laboratorio',
        'grupo' => 'Grupo A - 3º ESO',
        'fecha_entrega' => '2023-12-08',
        'completadas' => 25,
        'pendientes' => 0
    ]
];

// Ordenar tareas por fecha de entrega
usort($statsRecientes, function($a, $b) {
    return strtotime($a['fecha_entrega']) - strtotime($b['fecha_entrega']);
});

// Para gráfico de rendimiento por grupo (datos ficticios)
$rendimientoGrupos = [
    ['nombre' => 'Grupo A - 3º ESO', 'promedio' => 85],
    ['nombre' => 'Grupo B - 2º ESO', 'promedio' => 78],
    ['nombre' => 'Grupo C - 4º ESO', 'promedio' => 92],
    ['nombre' => 'Grupo D - 1º ESO', 'promedio' => 73],
];
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/teacher_dashboard.css">

<h1 class="position-relative header-page">Panel del Profesor</h1>

<div class="dashboard-page">
    <!-- Resumen General -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-4">
                    <h2 class="fs-4 fw-bold text-secondary mb-0">Bienvenido al Panel del Profesor</h2>
                    <p class="text-muted mb-0">Gestiona tus tareas, grupos y calificaciones</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas Estadísticas Principales -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
        <!-- Tarjeta de Tareas Totales -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 me-3">
                            <i class="fas fa-tasks fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Tareas</h5>
                    </div>
                    <h2 class="stats-number mb-2"><?= $statsTareas['total'] ?></h2>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: 100%" 
                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="progress-label">
                        <small class="text-muted">Tareas asignadas</small>
                        <small class="progress-percentage text-primary">Total</small>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=task_management&role=teacher" class="btn btn-sm btn-light w-100">
                        Gestionar Tareas <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Tareas Pendientes -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3 me-3">
                            <i class="fas fa-clock fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Pendientes</h5>
                    </div>
                    <h2 class="stats-number mb-2"><?= $statsTareas['pendientes'] ?></h2>
                    <div class="progress" style="height: 8px;">
                        <?php $porcentajePendientes = ($statsTareas['total'] > 0) ? ($statsTareas['pendientes'] / $statsTareas['total']) * 100 : 0; ?>
                        <div class="progress-bar bg-warning" style="width: <?= $porcentajePendientes ?>%" 
                             aria-valuenow="<?= $porcentajePendientes ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="progress-label">
                        <small class="text-muted">Pendientes de revisión</small>
                        <small class="progress-percentage text-warning"><?= number_format($porcentajePendientes, 1) ?>%</small>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=assigned_tasks&role=teacher&filter=pending" class="btn btn-sm btn-light w-100">
                        Ver Pendientes <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Tareas Completadas -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 me-3">
                            <i class="fas fa-check-circle fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Completadas</h5>
                    </div>
                    <h2 class="stats-number mb-2"><?= $statsTareas['completadas'] ?></h2>
                    <div class="progress" style="height: 8px;">
                        <?php $porcentajeCompletadas = ($statsTareas['total'] > 0) ? ($statsTareas['completadas'] / $statsTareas['total']) * 100 : 0; ?>
                        <div class="progress-bar bg-success" style="width: <?= $porcentajeCompletadas ?>%" 
                             aria-valuenow="<?= $porcentajeCompletadas ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="progress-label">
                        <small class="text-muted">Tareas entregadas</small>
                        <small class="progress-percentage text-success"><?= number_format($porcentajeCompletadas, 1) ?>%</small>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=assigned_tasks&role=teacher&filter=completed" class="btn btn-sm btn-light w-100">
                        Ver Completadas <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Grupos Asignados -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 me-3">
                            <i class="fas fa-users fa-fw fa-lg"></i>
                        </div>
                        <h5 class="card-title mb-0">Grupos</h5>
                    </div>
                    <h2 class="stats-number mb-2"><?= $statsGrupos['total'] ?></h2>
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-light rounded-3 me-2" style="width: 30px; height: 30px;">
                            <i class="fas fa-user-graduate fa-sm text-secondary"></i>
                        </div>
                        <div>
                            <span class="text-secondary"><?= $statsGrupos['estudiantes'] ?> estudiantes</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="<?= BASE_URL ?>?page=reports&role=teacher" class="btn btn-sm btn-light w-100">
                        Ver Grupos <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Tareas Recientes -->
    <div class="row g-3 mb-4">
        <!-- Gráfico de rendimiento -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-semibold">Rendimiento por Grupo</h5>
                </div>
                <div class="card-body">
                    <div id="rendimientoChartContainer" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Tareas Recientes -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Tareas Próximas</h5>
                    <a href="<?= BASE_URL ?>?page=task_management&role=teacher" class="btn btn-sm btn-outline-primary">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($statsRecientes as $index => $tarea): 
                            $daysLeft = round((strtotime($tarea['fecha_entrega']) - time()) / 86400);
                            $badgeClass = $daysLeft < 3 ? 'bg-danger' : ($daysLeft < 7 ? 'bg-warning' : 'bg-success');
                            $totalEntregas = $tarea['completadas'] + $tarea['pendientes'];
                            $porcentajeCompletado = $totalEntregas > 0 ? ($tarea['completadas'] / $totalEntregas) * 100 : 0;
                        ?>
                        <div class="list-group-item px-4 py-3 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($tarea['titulo']) ?></h6>
                                <span class="badge <?= $badgeClass ?> rounded-pill">
                                    <?php echo $daysLeft > 0 ? "Faltan $daysLeft días" : "¡Hoy vence!"; ?>
                                </span>
                            </div>
                            <p class="text-muted mb-2 small"><?= htmlspecialchars($tarea['grupo']) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: <?= $porcentajeCompletado ?>%" 
                                         aria-valuenow="<?= $porcentajeCompletado ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="small text-nowrap">
                                    <span class="text-success"><?php echo $tarea['completadas']; ?></span>/<?php echo $totalEntregas; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
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
                            <a href="<?= BASE_URL ?>?page=task_management&role=teacher&action=new" class="card hover-effect text-decoration-none h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                        <i class="fas fa-plus-circle fa-fw"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-dark">Nueva Tarea</h5>
                                        <span class="text-muted">Crear una tarea para tus grupos</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="<?= BASE_URL ?>?page=assigned_tasks&role=teacher" class="card hover-effect text-decoration-none h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                        <i class="fas fa-clipboard-check fa-fw"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-dark">Revisar Entregas</h5>
                                        <span class="text-muted">Calificar tareas entregadas</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="<?= BASE_URL ?>?page=reports&role=teacher" class="card hover-effect text-decoration-none h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                                        <i class="fas fa-chart-bar fa-fw"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-dark">Reportes</h5>
                                        <span class="text-muted">Ver estadísticas de rendimiento</span>
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
    // Gráfico de rendimiento por grupo
    if (document.getElementById('rendimientoChartContainer')) {
        const ctx = document.createElement('canvas');
        ctx.id = 'rendimientoChart';
        document.getElementById('rendimientoChartContainer').appendChild(ctx);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($rendimientoGrupos, 'nombre')) ?>,
                datasets: [{
                    label: 'Promedio de Calificaciones',
                    data: <?= json_encode(array_column($rendimientoGrupos, 'promedio')) ?>,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Calificación'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Grupos'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
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