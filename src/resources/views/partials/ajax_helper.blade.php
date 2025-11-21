{{-- Helper de AJAX con CSRF Token --}}
<script>
/**
 * Configuración global de AJAX con CSRF Token
 */
$(document).ready(function() {
    // Configurar AJAX para incluir CSRF token en todas las peticiones
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

/**
 * Helper para hacer peticiones API con manejo de errores consistente
 */
const API = {
    /**
     * GET request
     */
    get: function(url, params = {}) {
        return $.ajax({
            url: url,
            method: 'GET',
            data: params,
            dataType: 'json'
        });
    },

    /**
     * POST request
     */
    post: function(url, data = {}) {
        return $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json'
        });
    },

    /**
     * PUT request
     */
    put: function(url, data = {}) {
        return $.ajax({
            url: url,
            method: 'PUT',
            data: data,
            dataType: 'json'
        });
    },

    /**
     * DELETE request
     */
    delete: function(url) {
        return $.ajax({
            url: url,
            method: 'DELETE',
            dataType: 'json'
        });
    },

    /**
     * Manejo de errores estándar
     */
    handleError: function(xhr, defaultMessage = 'Error en la operación') {
        let message = defaultMessage;

        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        } else if (xhr.status === 404) {
            message = 'Recurso no encontrado';
        } else if (xhr.status === 422) {
            message = 'Datos inválidos';
        } else if (xhr.status === 500) {
            message = 'Error interno del servidor';
        }

        alert(message);
        console.error('Error:', xhr);
        return message;
    }
};

/**
 * Helper para mostrar mensajes de éxito/error
 */
function showMessage(message, type = 'success') {
    const bgColor = type === 'success' ? '#78B548' : '#e74c3c';
    const toast = $(`
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        ">
            ${message}
        </div>
    `);

    $('body').append(toast);

    setTimeout(() => {
        toast.fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}

// Animación para el toast
if (!$('style#toast-animation').length) {
    $('head').append(`
        <style id="toast-animation">
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        </style>
    `);
}
</script>
