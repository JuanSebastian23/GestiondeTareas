function openUserModal(user = null) {
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    
    // Resetear el formulario
    form.reset();
    
    if (user) {
        // Modo edición
        form.accion.value = 'actualizar';
        form.id.value = user.id;
        form.username.value = user.username;
        form.email.value = user.email;
        form.nombre.value = user.nombre;
        form.apellidos.value = user.apellidos;
        form.rol_id.value = user.rol_id;
        document.querySelector('.password-field').style.display = 'none';
    } else {
        // Modo creación
        form.accion.value = 'crear';
        form.id.value = '';
        document.querySelector('.password-field').style.display = 'block';
    }
    
    new bootstrap.Modal(modal).show();
}

function editUser(user) {
    openUserModal(user);
}

function deleteUser(id) {
    Swal.fire({
        title: '¿Desactivar usuario?',
        text: '¿Está seguro de que desea desactivar este usuario?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            submitForm({
                accion: 'eliminar',
                id: id
            });
        }
    });
}

function toggleActivation(id, activate) {
    const action = activate ? 'activar' : 'desactivar';
    const title = activate ? 'Activar' : 'Desactivar';
    
    Swal.fire({
        title: `¿${title} usuario?`,
        text: `¿Está seguro de que desea ${action} este usuario?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: activate ? '#28a745' : '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, ${action}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            submitForm({
                accion: activate ? 'activar' : 'eliminar',
                id: id
            });
        }
    });
}

// Función helper para enviar formularios
function submitForm(data) {
    const form = document.createElement('form');
    form.method = 'POST';
    
    // Agregar parámetros para indicar que hay un resultado
    const url = new URL(window.location.href);
    url.searchParams.set('result', 'true');
    url.searchParams.set('reload', 'true');
    form.action = url.toString();
    
    Object.entries(data).forEach(([key, value]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

// Nueva función para manejar resultados después de cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Obtener parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const hasResult = urlParams.get('result');
    
    // Verificar si hay un resultado en sessionStorage
    const resultado = sessionStorage.getItem('operationResult');
    if (resultado && hasResult) {
        const result = JSON.parse(resultado);
        
        Swal.fire({
            title: result.error ? 'Error' : '¡Éxito!',
            text: result.error || result.success,
            icon: result.error ? 'error' : 'success',
            confirmButtonColor: '#0075ff'
        }).then(() => {
            // Limpiar el resultado y recargar si es necesario
            sessionStorage.removeItem('operationResult');
            if (urlParams.has('reload')) {
                window.location.href = window.location.pathname;
            }
        });
    }
});