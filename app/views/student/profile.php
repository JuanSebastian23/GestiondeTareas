<?php
if (!isset($perfilData)) {
    echo "<p>Error: No se han cargado los datos del perfil para mostrar. Contacta al administrador.</p>";
    return;
}
?>

<div class="profile-container p-4">
    <h1 class="position-relative header-page">Mi Perfil</h1>

    <div class="card p-4 mt-4 shadow-sm">
        <div class="card-body">
            <h4 class="card-title mb-4">Información Personal</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Usuario:</strong> <?= htmlspecialchars($perfilData['username'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Email:</strong> <?= htmlspecialchars($perfilData['email'] ?? 'N/A') ?></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($perfilData['nombre'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Apellido:</strong> <?= htmlspecialchars($perfilData['apellido'] ?? 'N/A') ?></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Rol:</strong> <?= htmlspecialchars($perfilData['rol'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Grupo:</strong> <?= htmlspecialchars($perfilData['nombre_grupo'] ?? 'No asignado') ?></p>
                </div>
            </div>
            <?php if (!empty($perfilData['descripcion_grupo'])): ?>
            <div class="row mb-3">
                <div class="col-12">
                    <p><strong>Descripción del Grupo:</strong> <?= htmlspecialchars($perfilData['descripcion_grupo']) ?></p>
                </div>
            </div>
            <?php endif; ?>

            </div>
    </div>
</div>