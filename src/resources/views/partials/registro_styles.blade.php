<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .registro-modern-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    /* Header de la página */
    .page-header {
        background: linear-gradient(135deg, #78B548 0%, #6aa23f 100%);
        color: white;
        padding: 1.5rem 2rem;
        text-align: center;
    }

    .page-header h1 {
        font-size: 1.8rem;
        margin-bottom: 0.3rem;
        font-weight: 600;
    }

    .subtitle {
        font-size: 0.95rem;
        opacity: 0.95;
    }

    /* Pestañas de navegación */
    .tabs-container {
        display: flex;
        background: #f5f5f5;
        border-bottom: 3px solid #78B548;
        overflow-x: auto;
    }

    .tab-btn {
        flex: 1;
        padding: 1rem 1.5rem;
        border: none;
        background: #e8e8e8;
        color: #666;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .tab-btn:hover {
        background: #f0f0f0;
        color: #333;
    }

    .tab-btn.active {
        background: white;
        color: #78B548;
        border-bottom: 3px solid #78B548;
    }

    .icon-clipboard, .icon-users, .icon-calendar {
        font-size: 1.2rem;
    }

    /* Contenido de pestañas */
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .content-wrapper {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem;
        background: #fafafa;
        min-height: calc(100vh - 250px);
    }

    /* Paneles */
    .left-panel {
        flex: 0 0 400px;
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .right-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .panel-header h2 {
        color: #333;
        font-size: 1.4rem;
        margin-bottom: 0.3rem;
    }

    .panel-subtitle {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    /* Botones de acción superiores */
    .action-buttons-top {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .btn-action {
        flex: 1;
        padding: 0.7rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .btn-primary {
        background: #78B548;
        color: white;
    }

    .btn-primary:hover {
        background: #6aa23f;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(120, 181, 72, 0.3);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #333;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    .btn-icon {
        font-size: 1.1rem;
    }

    /* Formulario */
    .registro-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #333;
    }

    .required {
        color: #e74c3c;
    }

    input, select {
        padding: 0.7rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: border-color 0.3s ease;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #78B548;
        box-shadow: 0 0 0 3px rgba(120, 181, 72, 0.1);
    }

    /* Acciones del formulario */
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .btn-clear {
        flex: 1;
        padding: 0.9rem;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-clear:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(108, 117, 125, 0.3);
    }

    .btn-submit {
        flex: 2;
        padding: 0.9rem;
        background: #78B548;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit:hover {
        background: #6aa23f;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(120, 181, 72, 0.3);
    }

    /* Wrappers con scroll para secciones */
    .capacity-scroll-wrapper, .mesas-scroll-wrapper {
        max-height: 215px; /* ~2 elementos (cada evento ~100px + margin) */
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .disposition-scroll-wrapper {
        max-height: 480px; /* ~3 filas */
        overflow-y: auto;
    }

    /* Scrollbar personalizado */
    .capacity-scroll-wrapper::-webkit-scrollbar,
    .mesas-scroll-wrapper::-webkit-scrollbar,
    .disposition-scroll-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .capacity-scroll-wrapper::-webkit-scrollbar-track,
    .mesas-scroll-wrapper::-webkit-scrollbar-track,
    .disposition-scroll-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .capacity-scroll-wrapper::-webkit-scrollbar-thumb,
    .mesas-scroll-wrapper::-webkit-scrollbar-thumb,
    .disposition-scroll-wrapper::-webkit-scrollbar-thumb {
        background: #78B548;
        border-radius: 3px;
    }

    .capacity-scroll-wrapper::-webkit-scrollbar-thumb:hover,
    .mesas-scroll-wrapper::-webkit-scrollbar-thumb:hover,
    .disposition-scroll-wrapper::-webkit-scrollbar-thumb:hover {
        background: #6aa23f;
    }

    /* Secciones del panel derecho */
    .capacity-section, .mesas-section, .disposition-section {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .capacity-section h3, .mesas-section h3, .disposition-section h3 {
        color: #78B548;
        font-size: 1.2rem;
        margin-bottom: 0.3rem;
    }

    .section-subtitle {
        color: #999;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    /* Tarjetas de eventos */
    .event-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid #78B548;
    }

    .event-info h4 {
        color: #333;
        font-size: 1rem;
        margin-bottom: 0.3rem;
    }

    .event-date {
        color: #666;
        font-size: 0.85rem;
    }

    .capacity-badge {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
        font-size: 0.85rem;
    }

    .capacity-fill {
        color: #78B548;
        font-weight: 600;
    }

    .capacity-total {
        color: #666;
    }

    /* Tarjetas de mesas */
    .mesa-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 1px solid #e0e0e0;
    }

    .mesa-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .mesa-number {
        font-weight: 700;
        color: #333;
        font-size: 1rem;
    }

    .mesa-event {
        flex: 1;
        color: #78B548;
        font-size: 0.9rem;
    }

    .mesa-actions {
        display: flex;
        gap: 0.3rem;
    }

    .btn-icon-action {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 0.3rem;
        transition: transform 0.2s ease;
    }

    .btn-icon-action:hover {
        transform: scale(1.2);
    }

    .mesa-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
    }

    .mesa-date {
        color: #666;
    }

    .capacity-indicator {
        display: flex;
        gap: 0.75rem;
    }

    /* Tabla de disposición */
    .disposition-table-wrapper {
        overflow-x: auto;
    }

    .disposition-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .disposition-table th {
        background: #78B548;
        color: white;
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
    }

    .disposition-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .disposition-table tr:hover {
        background: #f8f9fa;
    }

    .badge-type {
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-type.socio {
        background: #2c3e50;
        color: white;
    }

    .badge-type.invitado {
        background: #e8e8e8;
        color: #666;
    }

    .btn-remove {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1.1rem;
        transition: transform 0.2s ease;
    }

    .btn-remove:hover {
        transform: scale(1.2);
    }

    /* Modales */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 2px solid #78B548;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        color: #333;
        font-size: 1.4rem;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        color: #999;
        cursor: pointer;
        line-height: 1;
        transition: color 0.3s ease;
    }

    .modal-close:hover {
        color: #e74c3c;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-subtitle {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-cancel, .btn-create {
        flex: 1;
        padding: 0.8rem;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background: #e0e0e0;
        color: #666;
    }

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    .btn-create, .btn-save {
        background: #78B548;
        color: white;
    }

    .btn-create:hover, .btn-save:hover {
        background: #6aa23f;
        box-shadow: 0 4px 12px rgba(120, 181, 72, 0.3);
    }

    .btn-save {
        flex: 1;
        padding: 0.8rem;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #2c3e50;
    }

    .btn-save:hover {
        background: #1a252f;
        box-shadow: 0 4px 12px rgba(44, 62, 80, 0.3);
    }

    /* Info message in modal */
    .info-message {
        background: #f0f7ff;
        border-left: 4px solid #3498db;
        padding: 1rem;
        margin-top: 1rem;
        border-radius: 4px;
    }

    .info-message p {
        margin: 0.3rem 0;
        font-size: 0.9rem;
        color: #555;
    }

    .capacity-info strong,
    .occupied-info {
        color: #333;
    }

    .occupied-info span {
        font-weight: 600;
        color: #78B548;
    }

    /* Readonly and disabled inputs in edit modal */
    input[readonly],
    select[disabled] {
        background-color: #f5f5f5;
        cursor: not-allowed;
        color: #888;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-wrapper {
            flex-direction: column;
        }

        .left-panel {
            flex: 1;
            max-width: 100%;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.4rem;
        }

        .subtitle {
            font-size: 0.85rem;
        }

        .tab-btn {
            padding: 0.8rem 0.5rem;
            font-size: 0.85rem;
        }

        .icon-clipboard, .icon-users, .icon-calendar {
            font-size: 1rem;
        }

        .content-wrapper {
            padding: 1rem;
        }

        .left-panel {
            padding: 1rem;
        }

        .action-buttons-top {
            flex-direction: column;
        }

        .modal-content {
            width: 95%;
        }
    }

    /* Estilos para selector de socio/familiar */
    .selector-personas-container {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
        max-height: 400px;
        overflow-y: auto;
    }

    .persona-card {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .persona-card:hover {
        border-color: #78B548;
        background: #f8fdf5;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(120, 181, 72, 0.15);
    }

    .persona-card.selected {
        border-color: #78B548;
        background: #e8f5e9;
    }

    .persona-info {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .persona-nombre {
        font-weight: 600;
        font-size: 1.05rem;
        color: #333;
    }

    .persona-codigo {
        font-size: 0.9rem;
        color: #666;
    }

    .persona-dni {
        font-size: 0.85rem;
        color: #888;
    }

    .persona-parentesco {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        background: #e3f2fd;
        color: #1565c0;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 0.3rem;
    }

    .persona-principal-badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        background: #78B548;
        color: white;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.3rem;
    }

    /* Mensaje de invitado */
    .invitado-info-message {
        margin-top: 0.5rem;
        padding: 0.6rem 0.8rem;
        background: #e8f5e9;
        border-left: 4px solid #78B548;
        border-radius: 4px;
        font-size: 0.9rem;
        color: #2e7d32;
        display: none;
    }

    .invitado-info-message.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    .invitado-info-message strong {
        font-weight: 600;
    }

    /* Wrapper para código con botón */
    .codigo-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .codigo-input-wrapper input {
        flex: 1;
    }

    /* Botón de búsqueda de socio */
    .btn-buscar-socio {
        padding: 0.6rem 0.8rem;
        background: #78B548;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
    }

    .btn-buscar-socio:hover {
        background: #6aa23f;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(120, 181, 72, 0.3);
    }

    .btn-buscar-socio:active {
        transform: translateY(0);
    }

    .btn-buscar-socio:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    /* Spinner de búsqueda */
    .spinner-busqueda {
        width: 20px;
        height: 20px;
        border: 3px solid rgba(120, 181, 72, 0.2);
        border-top-color: #78B548;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    /* Mensaje de error */
    .error-message-codigo {
        margin-top: 0.5rem;
        padding: 0.6rem 0.8rem;
        background: #ffebee;
        border-left: 4px solid #e74c3c;
        border-radius: 4px;
        font-size: 0.9rem;
        color: #c62828;
        display: none;
        align-items: center;
        gap: 0.5rem;
    }

    .error-message-codigo.show {
        display: flex;
        animation: slideDown 0.3s ease;
    }

    .error-message-codigo svg {
        flex-shrink: 0;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Loading overlay para secciones */
    .section-loading {
        position: relative;
        min-height: 100px;
    }

    .section-loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .loading-spinner-large {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(120, 181, 72, 0.2);
        border-top-color: #78B548;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 2rem auto;
    }

    .error-message-section {
        padding: 1rem;
        background: #ffebee;
        border-left: 4px solid #e74c3c;
        border-radius: 4px;
        color: #c62828;
        margin: 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Estilos para modal de confirmación de eliminación */
    .modal-confirm-delete .warning-icon {
        text-align: center;
        margin-bottom: 1rem;
    }

    .modal-confirm-delete .delete-info-list {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin: 1rem 0;
    }

    .modal-confirm-delete .delete-info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .modal-confirm-delete .delete-info-item:last-child {
        border-bottom: none;
    }

    .modal-confirm-delete .delete-info-item svg {
        flex-shrink: 0;
    }

    .modal-confirm-delete .delete-info-item strong {
        color: #78B548;
        font-size: 1.1rem;
    }

    .btn-delete-confirm {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
    }

    .btn-delete-confirm:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    /* Header de tarjeta de evento con botones a la derecha */
    .event-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    /* Botones de acción en tarjetas de eventos */
    .event-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Reutilizar estilos de btn-icon-action para eventos */
    .event-actions .btn-icon-action.export {
        background: rgba(52, 152, 219, 0.15);
        border-color: rgba(52, 152, 219, 0.3);
    }

    .event-actions .btn-icon-action.export:hover {
        background: rgba(52, 152, 219, 0.25);
        border-color: rgba(52, 152, 219, 0.5);
    }

    .event-actions .btn-icon-action.export svg {
        stroke: #3498db;
    }

    .event-actions .btn-icon-action.edit {
        background: rgba(241, 196, 15, 0.15);
        border-color: rgba(241, 196, 15, 0.3);
    }

    .event-actions .btn-icon-action.edit:hover {
        background: rgba(241, 196, 15, 0.25);
        border-color: rgba(241, 196, 15, 0.5);
    }

    .event-actions .btn-icon-action.edit svg {
        stroke: #f39c12;
    }

    .event-actions .btn-icon-action.delete {
        background: rgba(231, 76, 60, 0.15);
        border-color: rgba(231, 76, 60, 0.3);
    }

    .event-actions .btn-icon-action.delete:hover {
        background: rgba(231, 76, 60, 0.25);
        border-color: rgba(231, 76, 60, 0.5);
    }

    .event-actions .btn-icon-action.delete svg {
        stroke: #e74c3c;
    }
</style>
