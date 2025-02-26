<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión solo si no está activa
}

if (!isset($_SESSION['user_id'])) {
    die("Error: Sesión no iniciada. Por favor, inicia sesión.");
}


require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/MateriaController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/MateriaModelController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/EstadoController.php');

$materiaController = new MateriaModelController();
$estadoController = new EstadoController();

$materias = $materiaController->obtenerMateriasEstudiante($_SESSION['user_id']);
$estados = $estadoController->obtenerEstados();
?>

<h1 class="position-relative header-page">Mis Tareas</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <!-- Contador de Tareas -->
        <div class="statistics mega" data-aos="fade-up">
            <div>
                <h2 class="section-header">Estado de Mis Tareas</h2>
                <span class="section-des">Resumen de tareas asignadas</span>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 box text-center">
                    <i class="fa-solid fa-spinner c-orange"></i>
                    <span class="d-block">0</span> En Progreso
                </div>
                <div class="col-lg-4 col-md-6 box text-center">
                    <i class="fa-regular fa-circle-check c-green"></i>
                    <span class="d-block">0</span> Completadas
                </div>
                <div class="col-lg-4 col-md-6 box text-center">
                    <i class="far fa-times-circle c-red"></i>
                    <span class="d-block">0</span> Vencida
                </div>
</div>

        </div>
        <!-- Listado de Tareas -->
        <div class="table mega" data-aos="fade-up">
            <div>
                <h2 class="section-header">Todas mis Tareas</h2>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="section-des">Lista completa de tareas asignadas</span>
                    <div class="filters">
                    <select id="filter-materia" class="form-select form-select-sm d-inline-block w-auto me-2">
                            <option value="">Todas las Materias</option>
                            <?php foreach ($materias as $materia): ?>
                                <option value="<?= htmlspecialchars($materia['nombre']) ?>">
                                    <?= htmlspecialchars($materia['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="filter-estado" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="">Todos los Estados</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Progreso">En Progreso</option>
                            <option value="Completada">Completada</option>
                            <option value="Completada">Vencida</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="responsive-table">
                <table>
                    <thead>
                        <tr>
                            <th>Tarea</th>
                            <th>Materia</th>
                            <th>Fecha Asignación</th>
                            <th>Fecha Entrega</th>
                            <th>Grupo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tareas-list">
                        <!-- Las tareas se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tareas Próximas -->
        <div class="tasks mega" data-aos="fade-up">
            <div>
                <h2 class="section-header">Tareas Próximas a Vencer</h2>
                <span class="section-des">Tareas con fecha límite cercana</span>
            </div>
            <div class="data">
                <div class="d-flex align-items-center item">
                    <div class="info">
                        <h3>Proyecto de Matemáticas</h3>
                        <p>Entrega: Mañana - 8:00 AM</p>
                    </div>
                    <div>
                        <span class="label bg-orange">Pendiente</span>
                        <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                    </div>
                </div>
                <div class="d-flex align-items-center item">
                    <div class="info">
                        <h3>Ensayo de Literatura</h3>
                        <p>Entrega: En 2 días</p>
                    </div>
                    <div>
                        <span class="label bg-blue">En Progreso</span>
                        <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Script para actualizar contadores -->
<script>
    function actualizarContadorTareas() {
    fetch('http://localhost/GestiondeTareas/app/views/student/contar_tareas.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            
            // Actualizar los contadores en la página
            document.querySelector(".c-orange + span").textContent = data.Pendiente || 0;
            document.querySelector(".c-blue + span").textContent = data["En Progreso"] || 0;
            document.querySelector(".c-green + span").textContent = data.Completadas || 0;
            document.querySelector(".c-red + span").textContent = data.Vencida || 0;
        })
        .catch(error => console.error("Error al obtener tareas:", error));
}

// Ejecutar la función cada 5 segundos para actualizar en tiempo real
document.addEventListener('DOMContentLoaded', () => {
    actualizarContadorTareas();
    setInterval(actualizarContadorTareas, 5000); // Actualiza cada 5 segundos
});
</script>


<!-- Script para cargar tareas con AJAX -->
<script>
    function cargarTareas(filtrar = false) {
        let materia = document.getElementById("filter-materia").value;
        let estado = document.getElementById("filter-estado").value;

        let url = filtrar 
            ? `http://localhost/GestiondeTareas/app/views/student/filtrar_tareas.php?materia=${materia}&estado=${estado}`
            : `http://localhost/GestiondeTareas/app/views/student/listar_tareas.php`;

        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById('tareas-list').innerHTML = data;
                actualizarContadorTareas(); // Actualizar contadores después de cargar tareas
            })
            .catch(error => console.error('Error cargando tareas:', error));
    }

    document.addEventListener('DOMContentLoaded', function () {
        cargarTareas();

        document.getElementById("filter-materia").addEventListener("change", function() {
            cargarTareas(true);
        });

        document.getElementById("filter-estado").addEventListener("change", function() {
            cargarTareas(true);
        });
    });
</script>