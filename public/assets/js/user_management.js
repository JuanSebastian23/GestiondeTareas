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
    // Preguntar confirmación
    const action = activate ? 'activar' : 'desactivar';
    const message = `¿Está seguro que desea ${action} este usuario?`;
    
    confirmAction('Confirmar acción', message, 'warning').then((result) => {
        if (result.isConfirmed) {
            // Crear un formulario y enviarlo
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            // Agregar los campos necesarios
            const fields = {
                'accion': 'cambiarEstado',
                'id': userId,
                'activo': activate ? 1 : 0
            };
            
            // Crear campos y agregarlos al formulario
            Object.keys(fields).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            });
            
            // Agregar el formulario al documento y enviarlo
            document.body.appendChild(form);
            form.submit();
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