document.addEventListener('DOMContentLoaded', function () {
    // Verificar si hay un resultado de operación almacenado
    const storedResult = sessionStorage.getItem('operationResult');
    if (storedResult) {
        try {
            const result = JSON.parse(storedResult);
            if (result.success) {
                showAlert('Éxito', result.success, 'success');
            } else if (result.error) {
                showAlert('Error', result.error, 'error');
            }
        } catch (e) {
            console.error('Error al parsear el resultado:', e);
        }
        
        // Limpiar el storage después de mostrar
        sessionStorage.removeItem('operationResult');
    }
});

/**
 * Abre el modal para crear un nuevo usuario
 */
function openUserModal() {
    // Resetear el formulario
    document.getElementById('userForm').reset();
    
    // Configurar el formulario para creación
    document.querySelector('#userForm input[name="accion"]').value = 'crear';
    document.querySelector('#userForm input[name="id"]').value = '';
    
    // Mostrar el campo de contraseña y hacerlo requerido
    const passwordField = document.querySelector('.password-field');
    passwordField.style.display = 'block';
    passwordField.querySelector('input').setAttribute('required', 'required');
    
    // Cambiar el título del modal
    document.querySelector('#userModal .modal-title').textContent = 'Nuevo Usuario';
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

/**
 * Abre el modal para editar un usuario existente
 * @param {Object} user - Datos del usuario a editar
 */
function editUser(user) {
    // Configurar el formulario para edición
    document.querySelector('#userForm input[name="accion"]').value = 'actualizar';
    document.querySelector('#userForm input[name="id"]').value = user.id;
    document.querySelector('#userForm input[name="username"]').value = user.username;
    document.querySelector('#userForm input[name="email"]').value = user.email;
    document.querySelector('#userForm input[name="nombre"]').value = user.nombre;
    document.querySelector('#userForm input[name="apellidos"]').value = user.apellidos;
    
    // Seleccionar el rol correcto
    const rolSelect = document.querySelector('#userForm select[name="rol_id"]');
    for (let i = 0; i < rolSelect.options.length; i++) {
        if (rolSelect.options[i].value == user.rol_id) {
            rolSelect.selectedIndex = i;
            break;
        }
    }
    
    // Ocultar el campo de contraseña y hacerlo opcional
    const passwordField = document.querySelector('.password-field');
    passwordField.style.display = 'block';
    passwordField.querySelector('input').removeAttribute('required');
    
    // Cambiar el título del modal
    document.querySelector('#userModal .modal-title').textContent = 'Editar Usuario';
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

/**
 * Cambia el estado de activación de un usuario
 * @param {number} userId - ID del usuario
 * @param {boolean} activate - True para activar, False para desactivar
 */
function toggleActivation(userId, activate) {
    const action = activate ? 'activar' : 'desactivar';
    const message = `¿Está seguro de que desea ${action} este usuario?`;

    // Usar SweetAlert2 para la confirmación
    confirmAction('Confirmar acción', message, 'warning').then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('accion', activate ? 'activar' : 'eliminar'); // 'eliminar' es la acción para desactivar
            formData.append('id', userId);

            fetch('<?= BASE_URL ?>app/controllers/UsuarioController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.indexOf('application/json') !== -1) {
                    return response.json();
                } else {
                    // Si no es JSON, puede ser una redirección o un error de PHP no-JSON
                    // Recargar la página y manejar el mensaje a través de sessionStorage
                    location.reload();
                    throw new Error('Respuesta no JSON esperada, recargando página.');
                }
            })
            .then(data => {
                if (data.success) {
                    showAlert('Éxito', data.success, 'success');
                    // Recargar la página después de mostrar la alerta de SweetAlert2
                    // para que los cambios se reflejen en la tabla
                    setTimeout(() => { // Pequeño retraso para que el usuario vea la alerta
                        location.reload();
                    }, 1500); 
                } else {
                    showAlert('Error', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                if (error.message !== 'Respuesta no JSON esperada, recargando página.') {
                    showAlert('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
                }
            });
        }
    });
}

/**
 * Función auxiliar para mostrar alertas estilizadas
 * Ya está definida globalmente en index.php
 */
function showAlert(title, text, icon) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonColor: '#0075ff'
        });
    } else {
        alert(title + ': ' + text);
    }
}

/**
 * Función auxiliar para mostrar diálogos de confirmación
 * Ya está definida globalmente en index.php
 */
function confirmAction(title, text, icon) {
    if (typeof Swal !== 'undefined') {
        return Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#0075ff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        });
    } else {
        const confirmed = confirm(text);
        // Emular la estructura de retorno de SweetAlert2
        return Promise.resolve({
            isConfirmed: confirmed
        });
    }
}