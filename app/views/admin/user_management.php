<?php
if (!defined('ROOT_PATH')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

require_once(CONTROLLERS_PATH . '/UsuarioController.php');

$controller = new UsuarioController();
$resultado = $controller->procesarAccion();
$usuario = new Usuario();
$usuarios = $usuario->getAllUsers();
$roles = $usuario->getRoles();
$estadisticas = $controller->getConteoUsuariosPorRol();

// Actualizar las estadísticas en la vista
$totalProfesores = 0;
$totalEstudiantes = 0;
$totalAdmins = 0;

foreach ($estadisticas as $stat) {
    switch(strtolower($stat['rol'])) {
        case 'profesor':
            $totalProfesores = $stat['activos']; // Cambiado de total a activos
            break;
        case 'estudiante':
            $totalEstudiantes = $stat['activos']; // Cambiado de total a activos
            break;
        case 'administrador':
            $totalAdmins = $stat['activos']; // Cambiado de total a activos
            break;
    }
}

?>

<h1 class="position-relative header-page">Gestión de Usuarios</h1>
<div class="dashboard-page">
    <!-- Resumen de Usuarios -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent py-3">
            <h5 class="mb-0 fw-semibold">Resumen de Usuarios</h5>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 justify-content-center">
                <?php foreach ($estadisticas as $stat): ?>
                    <?php
                    [$icon, $color, $gradientClass] = match(strtolower($stat['rol'])) {
                        'profesor' => ['fa-chalkboard-teacher', 'primary', 'bg-primary bg-opacity-10'],
                        'estudiante' => ['fa-user-graduate', 'warning', 'bg-warning bg-opacity-10'],
                        'administrador' => ['fa-user-shield', 'success', 'bg-success bg-opacity-10'],
                        default => ['fa-user', 'secondary', 'bg-secondary bg-opacity-10']
                    };
                    ?>
                    <div class="col">
                        <div class="card h-100 border-0 <?= $gradientClass ?> rounded-3">
                            <div class="card-body p-4">
                                <!-- Encabezado con ícono y título -->
                                <div class="d-flex align-items-center mb-4">
                                    <div class="flex-shrink-0">
                                        <span class="rounded-circle p-3 bg-white d-inline-flex align-items-center justify-content-center">
                                            <i class="fas <?= $icon ?> fa-lg text-<?= $color ?>"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <?php
                                        $rolTitle = match(strtolower($stat['rol'])) {
                                            'profesor' => 'Profesores',
                                            'estudiante' => 'Estudiantes',
                                            'administrador' => 'Administradores',
                                            default => ucfirst($stat['rol'])
                                        };
                                        ?>
                                        <h6 class="mb-0 fw-semibold"><?= $rolTitle ?></h6>
                                        <small class="text-muted">Total: <?= $stat['total'] ?></small>
                                    </div>
                                </div>
                                
                                <!-- Métricas -->
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="bg-white rounded-3 p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                                            <h3 class="mb-1 fw-bold text-success"><?= $stat['activos'] ?></h3>
                                            <span class="small text-success fw-semibold">
                                                <i class="fas fa-user-check me-1"></i>Activos
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-white rounded-3 p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                                            <h3 class="mb-1 fw-bold text-danger"><?= $stat['inactivos'] ?></h3>
                                            <span class="small text-danger fw-semibold">
                                                <i class="fas fa-user-slash me-1"></i>Inactivos
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Barra de progreso -->
                                <div class="mt-4">
                                    <?php $porcentajeActivos = ($stat['total'] > 0) ? ($stat['activos'] / $stat['total']) * 100 : 0; ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Tasa de actividad</small>
                                        <small class="fw-bold"><?= number_format($porcentajeActivos, 1) ?>%</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-<?= $color ?>" 
                                             role="progressbar" 
                                             style="width: <?= $porcentajeActivos ?>%" 
                                             aria-valuenow="<?= $porcentajeActivos ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Formulario Modal para Crear/Editar Usuario -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="userForm" action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Gestionar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="crear">
                        <input type="hidden" name="id" value="">
                        
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select class="form-control" name="rol_id" required>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?= $rol['id'] ?>"><?= $rol['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3 password-field">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-gray-800">Lista de Usuarios</h5>
            <button class="btn btn-primary btn-sm d-flex align-items-center gap-2" onclick="openUserModal()">
                <i class="fas fa-plus-circle"></i>
                <span>Nuevo Usuario</span>
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-3">Usuario</th>
                            <th class="border-0 py-3">Nombre Completo</th>
                            <th class="border-0 py-3">Email</th>
                            <th class="border-0 py-3">Rol</th>
                            <th class="border-0 py-3">Estado</th>
                            <th class="border-0 py-3 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($usuarios as $user): ?>
                            <tr class="border-bottom">
                                <td class="align-middle"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="align-middle"><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']) ?></td>
                                <td class="align-middle"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="align-middle">
                                    <?php
                                    $badgeClass = match(strtolower($user['rol_nombre'])) {
                                        'profesor' => 'primary',
                                        'estudiante' => 'warning',
                                        'administrador' => 'success',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= ucfirst($user['rol_nombre']) ?></span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-<?= $user['activo'] ? 'success' : 'danger' ?>">
                                        <?= $user['activo'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="align-middle text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="editUser(<?= htmlspecialchars(json_encode($user)) ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($user['activo']): ?>
                                            <button class="btn btn-outline-danger" onclick="toggleActivation(<?= $user['id'] ?>, false)" title="Desactivar usuario">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-outline-success" onclick="toggleActivation(<?= $user['id'] ?>, true)" title="Activar usuario">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para la interactividad -->
<script type="text/javascript">
    // Pasar el resultado PHP a JavaScript
    <?php if (isset($resultado)): ?>
    sessionStorage.setItem('operationResult', '<?= json_encode($resultado) ?>');
    <?php endif; ?>
</script>
<script src="<?= BASE_URL ?>/public/assets/js/user_management.js"></script>
