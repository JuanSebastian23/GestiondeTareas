<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<h1 class="position-relative header-page">Reportes y Seguimiento</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <!-- Gráficos -->
        <div class="chart mega" data-aos="fade-up">
            <h2 class="section-header">Rendimiento por Grupo</h2>
            <canvas id="performanceChart"></canvas>
        </div>

        <!-- Estadísticas Generales -->
        <div class="statistics mega" data-aos="fade-up">
            <h2 class="section-header">Estadísticas Generales</h2>
            <div class="row">
                <div class="col-lg-4 box text-center">
                    <i class="fa-solid fa-graduation-cap c-blue"></i>
                    <span class="d-block" data-goal="87">0</span>
                    Promedio General
                </div>
                <div class="col-lg-4 box text-center">
                    <i class="fa-solid fa-users c-green"></i>
                    <span class="d-block" data-goal="95">0</span>
                    Participación
                </div>
                <div class="col-lg-4 box text-center">
                    <i class="fa-solid fa-chart-line c-orange"></i>
                    <span class="d-block" data-goal="78">0</span>
                    Tasa de Completitud
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Código del gráfico
const ctx = document.getElementById('performanceChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['1°A', '1°B', '2°A', '2°B', '3°A'],
        datasets: [{
            label: 'Rendimiento Promedio',
            data: [85, 78, 92, 88, 76],
            backgroundColor: '#0075ff'
        }]
    }
});
</script>
