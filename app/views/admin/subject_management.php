<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
require_once(CONTROLLERS_PATH . '/MateriaController.php');

$materiaController = new MateriaController();
$stats = $materiaController->obtenerEstadisticas();

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $materiaController->procesarAccion();
    if ($resultado) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlert('" . 
                ($resultado['success'] ?? $resultado['error']) . "', '', '" . 
                (isset($resultado['success']) ? 'success' : 'error') . 
                "');
            });
        </script>";
    }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Materias</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Inicio</a></li>
        <li class="breadcrumb-item active">Gestión de Materias</li>
    </ol>

    <!-- Estadísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_materias'] ?></h4>
                            <div class="small">Materias Activas</div>
                        </div>
                        <i class="fas fa-book fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_grupos'] ?></h4>
                            <div class="small">Grupos Asignados</div>
                        </div>
                        <i class="fas fa-users fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_profesores'] ?></h4>
                            <div class="small">Profesores</div>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_tareas'] ?></h4>
                            <div class="small">Tareas Asignadas</div>
                        </div>
                        <i class="fas fa-tasks fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario y Lista -->
    <div class="row">
        <!-- Formulario Nueva Materia -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-book me-1"></i>
                    Nueva Materia
                </div>
                <div class="card-body">
                    <form id="materiaForm" method="POST">
                        <input type="hidden" name="accion" value="crear">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Materia</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear Materia</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Materias -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Materias Registradas
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="materiasTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Grupos</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materiaController->obtenerTodas() as $materia): ?>
                                <tr>
                                    <td><?= htmlspecialchars($materia['codigo']) ?></td>
                                    <td><?= htmlspecialchars($materia['nombre']) ?></td>
                                    <td class="text-center"><?= $materia['total_grupos'] ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $materia['activo'] ? 'success' : 'danger' ?>">
                                            <?= $materia['activo'] ? 'Activa' : 'Inactiva' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary" onclick="editarMateria(<?= $materia['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-<?= $materia['activo'] ? 'danger' : 'success' ?>" 
                                                onclick="cambiarEstado(<?= $materia['id'] ?>, <?= $materia['activo'] ? 0 : 1 ?>)">
                                            <i class="fas fa-<?= $materia['activo'] ? 'times' : 'check' ?>"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="editarMateriaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Materia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editarMateriaForm" method="POST">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre de la Materia</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="edit_codigo" name="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="editarMateriaForm" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
// Definir todas las funciones en el ámbito global
const subjectFunctions = {
    editarMateria: function(id) {
        $.get('<?= BASE_URL ?>/app/ajax/get_materia.php', {
            id: id
        }).done(function(response) {
            if (response.success) {
                const materia = response.data;
                $('#edit_id').val(materia.id);
                $('#edit_nombre').val(materia.nombre);
                $('#edit_codigo').val(materia.codigo);
                $('#edit_descripcion').val(materia.descripcion);
                $('#editarMateriaModal').modal('show');
            } else {
                showAlert('Error', 'No se pudieron cargar los datos de la materia', 'error');
            }
        }).fail(function() {
            showAlert('Error', 'Error en la conexión', 'error');
        });
    },

    cambiarEstado: function(id, estado) {
        confirmAction(
            '¿Estás seguro?',
            'Esta acción cambiará el estado de la materia',
            'warning'
        ).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="accion" value="cambiarEstado">
                    <input type="hidden" name="id" value="${id}">
                    <input type="hidden" name="activo" value="${estado}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
};

// Asignar todas las funciones al objeto window
Object.keys(subjectFunctions).forEach(key => {
    window[key] = subjectFunctions[key];
});

// Actualizar los onclick en el HTML para usar las funciones
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización de DataTables
    if ($.fn.DataTable) {
        $('#materiasTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[1, 'asc']]
        });
    }
});
</script>
