<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<h1 class="position-relative header-page">Gestión de Materias</h1>
<div class="dashboard-page">
    <div class="wrapper">
        <!-- Formulario Nueva Materia -->
        <div class="quick-draft mega" data-aos="fade-up">
            <h2 class="section-header">Nueva Materia</h2>
            <form>
                <input class="form-control mb-3" type="text" placeholder="Nombre de la Materia" required>
                <select class="form-control mb-3" required>
                    <option value="">Profesor Asignado</option>
                    <!-- Opciones dinámicas -->
                </select>
                <select class="form-control mb-3" required>
                    <option value="">Grupo</option>
                    <!-- Opciones dinámicas -->
                </select>
                <input class="save d-block btn btn-primary" type="submit" value="Crear Materia">
            </form>
        </div>

        <!-- Lista de Materias -->
        <div class="table mega" data-aos="fade-up">
            <h2 class="section-header">Materias Registradas</h2>
            <div class="responsive-table">
                <table>
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Profesor</th>
                            <th>Grupos</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contenido dinámico -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
