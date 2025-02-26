<?php
if (!defined('ROOT_PATH')) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}

function asset($path) {
    return '/GestiondeTareas/public/assets/' . ltrim($path, '/');
}
?>
<nav class="navbar navbar-expand bg-white shadow-sm px-4 py-2">
    <div class="container-fluid">
        <!-- Buscador -->
        <div class="d-flex position-relative me-auto" style="width: 300px;">
            <input type="text" class="form-control form-control-sm ps-4" placeholder="Buscar...">
            <i class="fa-solid fa-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
        </div>

        <!-- Contenedor derecho para notificaciones y perfil -->
        <div class="d-flex align-items-center">
            <!-- Notificaciones -->
            <div class="dropdown position-relative">
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" 
                    style="width: 300px; position: absolute; z-index: 1500;">
                    <li class="border-bottom p-3">
                        <h6 class="mb-0">Notificaciones</h6>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="#">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <img src="<?php echo asset('images/avatar.png'); ?>" 
                                         class="rounded-circle" 
                                         width="32" 
                                         height="32">
                                </div>
                                <div>
                                    <p class="mb-1">Nueva tarea asignada</p>
                                    <small class="text-muted">Hace 5 minutos</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <!-- Más notificaciones aquí -->
                </ul>
            </div>

            <!-- Separador vertical -->
            <div class="vr me-3"></div>

            <!-- Perfil de usuario -->
            <div class="d-flex align-items-center">
                <img src="<?php echo asset('images/avatar.png'); ?>" 
                     class="rounded-circle me-2" 
                     width="32" 
                     height="32">
                <div class="d-none d-sm-block">
                    <p class="mb-0 text-dark"><?= $_SESSION['nombre_completo'] ?? 'Usuario' ?></p>
                    <small class="text-muted"><?= ucfirst($_SESSION['rol']) ?? 'Rol' ?></small>
                </div>
            </div>
        </div>
    </div>
</nav>