<?php
if (!defined('ROOT_PATH')) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/GestiondeTareas/app/config/dirs.php');
}
?>

<div class="sidebar bg-white position-relative">
    <div class="holder position-fixed">
        <h3 class="position-relative text-center fw-bold">Profesor</h3>
        <div class="links">
            <ul>
                <li>
                    <a class="d-flex align-items-center" href="<?= BASE_URL ?>?page=task_management&role=teacher">
                        <i class="fa-solid fa-tasks fa-fw"></i>
                        <span>Gestión de Tareas</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center" href="<?= BASE_URL ?>?page=assigned_tasks&role=teacher">
                        <i class="fa-solid fa-clipboard-check fa-fw"></i>
                        <span>Tareas Asignadas</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center" href="<?= BASE_URL ?>?page=notifications&role=teacher">
                        <i class="fa-solid fa-bell fa-fw"></i>
                        <span>Notificaciones</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="log-out">
            <span onclick="window.location.href='<?= BASE_URL ?>/app/controllers/logout.php'">
                <i class="fa-solid fa-arrow-right-from-bracket" style="color: #e10000;"></i>
                <a href="<?= BASE_URL ?>/app/controllers/logout.php">Cerrar Sesión</a>
            </span>
        </div>
    </div>
</div>