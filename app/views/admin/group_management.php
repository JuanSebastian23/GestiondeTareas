<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
require_once(CONTROLLERS_PATH . '/GrupoController.php');

$grupoController = new GrupoController();
$stats = $grupoController->obtenerEstadisticas();
$profesores = $grupoController->obtenerProfesores();

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $grupoController->procesarAccion();
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
<!-- Asegurarse de que jQuery esté cargado -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Grupos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Inicio</a></li>
        <li class="breadcrumb-item active">Gestión de Grupos</li>
    </ol>

    <!-- Estadísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_grupos'] ?></h4>
                            <div class="small">Grupos Activos</div>
                        </div>
                        <i class="fas fa-users fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_estudiantes'] ?></h4>
                            <div class="small">Estudiantes</div>
                        </div>
                        <i class="fas fa-user-graduate fa-2x text-white-50"></i>
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
            <div class="card bg-danger text-white mb-4">
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
    </div>

    <!-- Formulario y Lista -->
    <div class="row">
        <!-- Formulario Nuevo Grupo -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-users-class me-1"></i>
                    Nuevo Grupo
                </div>
                <div class="card-body">
                    <form id="grupoForm" method="POST">
                        <input type="hidden" name="accion" value="crear">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Grupo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="profesor_id" class="form-label">Profesor Titular</label>
                            <select class="form-select" id="profesor_id" name="profesor_id">
                                <option value="">Seleccione un profesor...</option>
                                <?php foreach ($profesores as $profesor): ?>
                                <option value="<?= $profesor['id'] ?>">
                                    <?= htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellidos']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear Grupo</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Grupos -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Grupos Registrados
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="gruposTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Grupo</th>
                                    <th>Profesor Titular</th>
                                    <th>Estudiantes</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grupoController->obtenerTodos() as $grupo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($grupo['nombre']) ?></td>
                                    <td><?= htmlspecialchars($grupo['profesor_nombre'] . ' ' . $grupo['profesor_apellidos']) ?></td>
                                    <td class="text-center"><?= $grupo['total_estudiantes'] ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $grupo['activo'] ? 'success' : 'danger' ?>">
                                            <?= $grupo['activo'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary" onclick="editarGrupo(<?= $grupo['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-<?= $grupo['activo'] ? 'danger' : 'success' ?>" 
                                                onclick="cambiarEstado(<?= $grupo['id'] ?>, <?= $grupo['activo'] ? 0 : 1 ?>)">
                                            <i class="fas fa-<?= $grupo['activo'] ? 'times' : 'check' ?>"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info" onclick="abrirMatriculacion(<?= $grupo['id'] ?>)">
                                            <i class="fas fa-users"></i>
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

<!-- Reemplazar el modal por una sección de matriculación -->
<div class="container-fluid px-4 matriculacion-section" id="matriculacionSection" style="display:none;">
    <div class="row">
        <div class="col-12">
            <button class="btn btn-secondary mb-3" onclick="volverAGrupos()">
                <i class="fas fa-arrow-left"></i> Volver a Grupos
            </button>
            <h2 class="mb-4">Gestión de Estudiantes del Grupo: <span id="nombreGrupo"></span></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Estudiantes Disponibles</h5>
                </div>
                <div class="card-body">
                    <input type="hidden" id="grupo_id_matricula">
                    <select multiple class="form-select" id="estudiantes_disponibles" 
                            style="height: 400px" size="15">
                    </select>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-success w-100" onclick="matricularSeleccionados()">
                        <i class="fas fa-user-plus"></i> Matricular Seleccionados
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Estudiantes Matriculados</h5>
                </div>
                <div class="card-body">
                    <select multiple class="form-select" id="estudiantes_matriculados" 
                            style="height: 400px" size="15">
                    </select>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger w-100" onclick="desmatricularSeleccionados()">
                        <i class="fas fa-user-minus"></i> Desmatricular Seleccionados
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="editarGrupoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editarGrupoForm" method="POST">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre del Grupo</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_profesor_id" class="form-label">Profesor Titular</label>
                        <select class="form-select" id="edit_profesor_id" name="profesor_id">
                            <option value="">Seleccione un profesor...</option>
                            <?php foreach ($profesores as $profesor): ?>
                            <option value="<?= $profesor['id'] ?>">
                                <?= htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellidos']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="editarGrupoForm" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Funciones globales
    window.editarGrupo = function(id) {
        $.get('<?= BASE_URL ?>/app/ajax/get_grupo.php', {
            id: id
        }).done(function(response) {
            if (response.success) {
                const grupo = response.data;
                $('#edit_id').val(grupo.id);
                $('#edit_nombre').val(grupo.nombre);
                $('#edit_descripcion').val(grupo.descripcion);
                $('#edit_profesor_id').val(grupo.profesor_id);
                $('#editarGrupoModal').modal('show');
            } else {
                showAlert('Error', 'No se pudieron cargar los datos del grupo', 'error');
            }
        }).fail(function() {
            showAlert('Error', 'Error en la conexión', 'error');
        });
    };

    window.cambiarEstado = function(id, estado) {
        confirmAction(
            '¿Estás seguro?',
            'Esta acción cambiará el estado del grupo',
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
    };

    window.abrirMatriculacion = function(grupo_id) {
        $('.container-fluid').first().hide();
        $('#matriculacionSection').show();
        $('#grupo_id_matricula').val(grupo_id);
        
        // Obtener nombre del grupo
        $.get('<?= BASE_URL ?>/app/ajax/get_grupo.php', {
            id: grupo_id
        }).done(function(response) {
            if (response.success) {
                $('#nombreGrupo').text(response.data.nombre);
            }
        });
        
        cargarEstudiantes(grupo_id);
    };

    window.volverAGrupos = function() {
        $('#matriculacionSection').hide();
        $('.container-fluid').first().show();
    };

    window.matricularSeleccionados = function() {
        const grupo_id = $('#grupo_id_matricula').val();
        const estudiantes = $('#estudiantes_disponibles').val();
        
        if (!estudiantes || !estudiantes.length) {
            showAlert('Error', 'Selecciona al menos un estudiante', 'error');
            return;
        }

        $.ajax({
            url: '<?= BASE_URL ?>/app/ajax/matricular.php',
            method: 'POST',
            dataType: 'json',
            data: {
                accion: 'matricular',
                grupo_id: grupo_id,
                estudiantes: estudiantes
            },
            success: function(response) {
                if (response.success) {
                    showAlert('Éxito', response.message, 'success');
                    cargarEstudiantes(grupo_id);
                } else if (response.partial_success) {
                    showAlert('Advertencia', response.message, 'warning');
                    cargarEstudiantes(grupo_id);
                } else {
                    showAlert('Error', response.error || 'Error al matricular estudiantes', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error detallado:", xhr.responseText);
                showAlert('Error', 'Error en la conexión: ' + error, 'error');
            }
        });
    };

    window.desmatricularSeleccionados = function() {
        const grupo_id = $('#grupo_id_matricula').val();
        const estudiantes = $('#estudiantes_matriculados').val();
        
        if (!estudiantes.length) {
            showAlert('Error', 'Selecciona al menos un estudiante', 'error');
            return;
        }

        confirmAction('¿Estás seguro?', 'Esta acción desmatriculará a los estudiantes seleccionados', 'warning')
        .then((result) => {
            if (result.isConfirmed) {
                estudiantes.forEach(function(estudiante_id) {
                    $.post('<?= BASE_URL ?>/app/ajax/matricular.php', {
                        accion: 'desmatricular',
                        grupo_id: grupo_id,
                        estudiante_id: estudiante_id
                    }, function(response) {
                        if (response.success) {
                            cargarEstudiantes(grupo_id);
                        } else {
                            showAlert('Error', response.error, 'error');
                        }
                    });
                });
            }
        });
    };

    window.cargarEstudiantes = function(grupo_id) {
        // Cargar estudiantes no matriculados
        $.get('<?= BASE_URL ?>/app/ajax/get_estudiantes.php', {
            accion: 'no_matriculados',
            grupo_id: grupo_id
        }).done(function(data) {
            $('#estudiantes_disponibles').empty();
            if (Array.isArray(data)) {
                data.forEach(function(estudiante) {
                    $('#estudiantes_disponibles').append(new Option(
                        estudiante.nombre + ' ' + estudiante.apellidos,
                        estudiante.id
                    ));
                });
            }
        });

        // Cargar estudiantes matriculados
        $.get('<?= BASE_URL ?>/app/ajax/get_estudiantes.php', {
            accion: 'matriculados',
            grupo_id: grupo_id
        }).done(function(data) {
            $('#estudiantes_matriculados').empty();
            if (Array.isArray(data)) {
                data.forEach(function(estudiante) {
                    $('#estudiantes_matriculados').append(new Option(
                        estudiante.nombre + ' ' + estudiante.apellidos,
                        estudiante.id
                    ));
                });
            }
        });
    };

    // DataTables initialization
    $('#gruposTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'asc']]
    });
});
</script>
