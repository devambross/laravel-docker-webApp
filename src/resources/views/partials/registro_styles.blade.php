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

    .btn-submit {
        padding: 0.9rem;
        background: #78B548;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }

    .btn-submit:hover {
        background: #6aa23f;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(120, 181, 72, 0.3);
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

    .btn-create {
        background: #78B548;
        color: white;
    }

    .btn-create:hover {
        background: #6aa23f;
        box-shadow: 0 4px 12px rgba(120, 181, 72, 0.3);
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
</style>
