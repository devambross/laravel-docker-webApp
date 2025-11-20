<script>
    // Manejo de pestañas
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');

                // Remover active de todos
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Activar el seleccionado
                this.classList.add('active');
                document.getElementById(tabName + '-tab').classList.add('active');
            });
        });
    });

    // Funciones para modales
    function openEventModal() {
        document.getElementById('modalNuevoEvento').classList.add('show');
    }

    function closeEventModal() {
        document.getElementById('modalNuevoEvento').classList.remove('show');
    }

    function openMesaModal() {
        document.getElementById('modalNuevaMesa').classList.add('show');
    }

    function closeMesaModal() {
        document.getElementById('modalNuevaMesa').classList.remove('show');
    }

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    });

    // Manejar envío de formulario de registro de participante
    document.getElementById('formRegistroParticipante')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            tipo: document.getElementById('tipo').value,
            codigo_socio: document.getElementById('codigo_socio').value,
            dni: document.getElementById('dni').value,
            nombre: document.getElementById('nombre').value,
            n_recibo: document.getElementById('n_recibo').value,
            n_boleta: document.getElementById('n_boleta').value,
            evento: document.getElementById('evento').value,
            mesa: document.getElementById('mesa').value,
            n_silla: document.getElementById('n_silla').value
        };

        console.log('[Registro] Formulario enviado:', formData);

        // Aquí iría la llamada AJAX al backend
        $.ajax({
            url: '/api/participantes/registro',
            method: 'POST',
            data: formData,
            success: function(response) {
                alert('Participante registrado exitosamente');
                document.getElementById('formRegistroParticipante').reset();
                // Recargar tabla de disposición
            },
            error: function(xhr) {
                alert('Error al registrar participante: ' + xhr.responseText);
            }
        });
    });

    // Manejar envío de formulario de nuevo evento
    document.getElementById('formNuevoEvento')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            nombre: document.getElementById('nombre_evento').value,
            fecha: document.getElementById('fecha_evento').value,
            area: document.getElementById('area_evento').value
        };

        console.log('[Nuevo Evento] Formulario enviado:', formData);

        // Aquí iría la llamada AJAX al backend
        $.ajax({
            url: '/api/eventos/crear',
            method: 'POST',
            data: formData,
            success: function(response) {
                alert('Evento creado exitosamente');
                closeEventModal();
                document.getElementById('formNuevoEvento').reset();
                // Recargar lista de eventos
            },
            error: function(xhr) {
                alert('Error al crear evento: ' + xhr.responseText);
            }
        });
    });

    // Manejar envío de formulario de nueva mesa
    document.getElementById('formNuevaMesa')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            numero: document.getElementById('numero_mesa').value,
            evento: document.getElementById('evento_mesa').value,
            capacidad: document.getElementById('capacidad_mesa').value
        };

        console.log('[Nueva Mesa] Formulario enviado:', formData);

        // Aquí iría la llamada AJAX al backend
        $.ajax({
            url: '/api/mesas/crear',
            method: 'POST',
            data: formData,
            success: function(response) {
                alert('Mesa creada exitosamente');
                closeMesaModal();
                document.getElementById('formNuevaMesa').reset();
                // Recargar lista de mesas
            },
            error: function(xhr) {
                alert('Error al crear mesa: ' + xhr.responseText);
            }
        });
    });
</script>
