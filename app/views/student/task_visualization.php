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
<div class="container mt-4">
    <div class="row">
        <!-- Contador de Tareas -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Estado de Mis Tareas</h2>
                    <p class="card-text">Resumen de tareas asignadas</p>
                    <div class="row text-center">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <i class="fa-solid fa-spinner text-warning"></i>
                                    <h5 class="card-title">En Progreso</h5>
                                    <p class="card-text"><span>0</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <i class="fa-regular fa-circle-check text-success"></i>
                                    <h5 class="card-title">Completadas</h5>
                                    <p class="card-text"><span>0</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <i class="far fa-times-circle text-danger"></i>
                                    <h5 class="card-title">Vencida</h5>
                                    <p class="card-text"><span>0</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de Tareas -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Todas mis Tareas</h2>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="card-text">Lista completa de tareas asignadas</p>
                        <div class="d-flex">
                            <select id="filter-materia" class="form-select form-select-sm me-2">
                                <option value="">Todas las Materias</option>
                                <?php foreach ($materias as $materia): ?>
                                    <option value="<?= htmlspecialchars($materia['nombre']) ?>">
                                        <?= htmlspecialchars($materia['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filter-estado" class="form-select form-select-sm">
                                <option value="">Todos los Estados</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="En Progreso">En Progreso</option>
                                <option value="Completada">Completada</option>
                                <option value="Vencida">Vencida</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
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
            </div>
        </div>

        <!-- Tareas Próximas -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Tareas Próximas a Vencer</h2>
                    <p class="card-text">Tareas con fecha límite cercana</p>
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Proyecto de Matemáticas</h5>
                                <small>Entrega: Mañana - 8:00 AM</small>
                            </div>
                            <div>
                                <span class="badge bg-warning">Pendiente</span>
                                <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Ensayo de Literatura</h5>
                                <small>Entrega: En 2 días</small>
                            </div>
                            <div>
                                <span class="badge bg-primary">En Progreso</span>
                                <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                            </div>
                        </div>
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