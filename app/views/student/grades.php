<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<h1 class="position-relative header-page">Sistema de Calificaciones</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <!-- Resumen de Calificaciones -->
        <div class="statistics mega" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="section-header">Dashboard Académico</h2>
                    <span class="section-des">Año Lectivo 2024 - Primer Semestre</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="periodoDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar-alt"></i> Periodo Actual
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">2024-1</a></li>
                        <li><a class="dropdown-item" href="#">2023-2</a></li>
                        <li><a class="dropdown-item" href="#">2023-1</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-star c-gold"></i>
                    <span class="d-block" data-goal="85">0</span>
                    <p class="text-muted">Promedio General</p>
                    <small class="text-success">↑ 2.3% vs periodo anterior</small>
                </div>
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-check-circle c-green"></i>
                    <span class="d-block" data-goal="6">0</span>
                    Materias Aprobadas
                </div>
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-exclamation-circle c-orange"></i>
                    <span class="d-block" data-goal="1">0</span>
                    Materias en Riesgo
                </div>
                <div class="col-lg-3 col-md-6 box text-center">
                    <i class="fa-solid fa-graduation-cap c-blue"></i>
                    <span class="d-block" data-goal="7">0</span>
                    Total Materias
                </div>
            </div>
        </div>

        <!-- Tabla de Calificaciones Detallada -->
        <div class="table mega" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-header">Detalle por Asignatura</h2>
                <div class="btn-group">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-file-excel"></i> Exportar
                    </button>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </div>
            <div class="responsive-table">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Asignatura</th>
                            <th>P1</th>
                            <th>P2</th>
                            <th>P3</th>
                            <th>Promedio</th>
                            <th>Tendencia</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Matemáticas</td>
                            <td>85</td>
                            <td>90</td>
                            <td>88</td>
                            <td>87.6</td>
                            <td><span class="label bg-green">Aprobado</span></td>
                        </tr>
                        <tr>
                            <td>Literatura</td>
                            <td>75</td>
                            <td>68</td>
                            <td>--</td>
                            <td>71.5</td>
                            <td><span class="label bg-orange">En Riesgo</span></td>
                        </tr>
                        <tr>
                            <td>Ciencias</td>
                            <td>92</td>
                            <td>88</td>
                            <td>95</td>
                            <td>91.6</td>
                            <td><span class="label bg-green">Aprobado</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Rendimiento
    const ctxRendimiento = document.getElementById('graficoRendimiento').getContext('2d');
    new Chart(ctxRendimiento, {
        type: 'line',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
            datasets: [{
                label: 'Promedio General',
                data: [82, 85, 87, 86, 88, 90],
                borderColor: '#0075ff',
                tension: 0.4,
                fill: {
                    target: 'origin',
                    above: 'rgba(0, 117, 255, 0.1)',
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 60,
                    max: 100
                }
            }
        }
    });

    // Gráfico de Distribución
    const ctxPie = document.getElementById('graficoPie').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Excelente', 'Bueno', 'Regular', 'Necesita Mejorar'],
            datasets: [{
                data: [4, 2, 1, 1],
                backgroundColor: [
                    '#22c55e',
                    '#0075ff',
                    '#fbbf24',
                    '#ef4444'
                ]
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
});

// Inicializar tooltips de Bootstrap
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>
