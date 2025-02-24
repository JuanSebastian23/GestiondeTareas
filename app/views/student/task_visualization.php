<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión solo si no está activa
}

if (!isset($_SESSION['user_id'])) {
    die("Error: Sesión no iniciada. Por favor, inicia sesión.");
}


require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/MateriaController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/controllers/EstadoController.php');

$materiaController = new MateriaController();
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
                    <i class="fa-solid fa-clock c-orange"></i>
                    <span class="d-block" data-goal="5">0</span>
                    Pendientes
                </div>
                <div class="col-lg-4 col-md-6 box text-center">
                    <i class="fa-solid fa-spinner c-blue"></i>
                    <span class="d-block" data-goal="3">0</span>
                    En Progreso
                </div>
                <div class="col-lg-4 col-md-6 box text-center">
                    <i class="fa-regular fa-circle-check c-green"></i>
                    <span class="d-block" data-goal="8">0</span>
                    Completadas
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
                        <select class="form-select form-select-sm d-inline-block w-auto me-2">
                            <option>Todas las Materias</option>
                            <option>Matemáticas</option>
                            <option>Literatura</option>
                            <option>Ciencias</option>
                        </select>
                        <select class="form-select form-select-sm d-inline-block w-auto">
                            <option>Todos los Estados</option>
                            <option>Pendiente</option>
                            <option>En Progreso</option>
                            <option>Completada</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="responsive-table">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Tarea</th>
                            <th scope="col">Materia</th>
                            <th scope="col">Fecha Asignación</th>
                            <th scope="col">Fecha Entrega</th>
                            <th scope="col">Grupo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tareas-list">
                        <!-- Las tareas se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tareas Próximas -->
    <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="900" data-aos-duration="1000">
        <div class="card-header bg-white">
            <h2 class="card-title h5 text-primary mb-0">Tareas Próximas a Vencer</h2>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <h6 class="mb-1">Proyecto de Matemáticas</h6>
                        <small class="text-muted">Entrega: Mañana - 8:00 AM</small>
                    </div>
                    <div>
                        <span class="badge bg-warning text-dark rounded-pill">Pendiente</span>
                        <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                    </div>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <h6 class="mb-1">Ensayo de Literatura</h6>
                        <small class="text-muted">Entrega: En 2 días</small>
                    </div>
                    <div>
                        <span class="badge bg-primary rounded-pill">En Progreso</span>
                        <button class="btn btn-sm btn-primary ms-2">Ver Detalles</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.circle-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
</style>

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