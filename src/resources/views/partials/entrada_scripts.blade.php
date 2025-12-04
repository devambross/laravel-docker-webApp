<script>
    // Sistema de notificaciones (reutilizado de registro)
    function mostrarNotificacion(mensaje, tipo = 'success', titulo = null) {
        const container = document.getElementById('notificationContainer');
        const notification = document.createElement('div');
        notification.className = `notification ${tipo}`;

        const titulos = {
            'success': titulo || '✓ Éxito',
            'error': titulo || '✗ Error',
            'warning': titulo || '⚠ Advertencia',
            'info': titulo || 'ℹ Información'
        };

        const iconos = {
            'success': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>',
            'error': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
            'warning': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            'info': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
        };

        notification.innerHTML = `
            <div class="notification-icon">
                ${iconos[tipo]}
            </div>
            <div class="notification-content">
                <div class="notification-title">${titulos[tipo]}</div>
                <div class="notification-message">${mensaje}</div>
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">×</button>
        `;

        container.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>
