<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Web')</title>
    <style>
        /*Estructura General */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #fdfdfd;
            overflow-x: hidden;
        }
        main {
            padding: 0;
            min-height: 70vh;
            background: #fdfdfd;
            max-width: 100%;
        }

        /*Header del Sistema*/
        .sistema-header {
            background: linear-gradient(135deg, #78B548 0%, #6aa23f 100%);
            padding: 0.8rem 2rem;
        }

        .header-container {
            display: flex;
            align-items: center;
            gap: 2rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        .header-logo {
            height: 55px;
            width: auto;
            flex-shrink: 0;
        }

        .header-nav {
            display: flex;
            gap: 0.3rem;
        }

        .header-nav a {
            color: white;
            padding: 0.6rem 1.2rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            white-space: nowrap;
        }

        .header-nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .header-nav a.active {
            background: white;
            color: #78B548;
        }

        .header-text {
            color: white;
            flex: 1;
            text-align: center;
        }

        .header-text h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            line-height: 1.2;
        }

        .header-subtitle {
            margin: 0.2rem 0 0 0;
            font-size: 0.85rem;
            opacity: 0.95;
            color: white;
        }

        .btn-salir-header {
            padding: 0.6rem 1.5rem;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-salir-header:hover {
            background: #c0392b;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }



        /*MODO RESPONSIVO*/
        @media (max-width: 1100px) {
            .header-container {
                gap: 1rem;
            }

            .header-text h1 {
                font-size: 1.3rem;
            }

            .header-subtitle {
                font-size: 0.8rem;
            }

            .header-nav a {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
            }

            .btn-salir-header {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 900px) {
            .sistema-header {
                padding: 0.6rem 1rem;
            }

            .header-container {
                flex-wrap: wrap;
                gap: 0.8rem;
                justify-content: center;
            }

            .header-logo {
                height: 45px;
                order: 1;
            }

            .header-nav {
                order: 2;
                width: 100%;
                justify-content: center;
            }

            .header-text {
                order: 3;
                width: calc(100% - 100px);
            }

            .header-text h1 {
                font-size: 1.2rem;
            }

            .btn-salir-header {
                order: 4;
            }
        }

        @media (max-width: 768px) {
            .sistema-header {
                padding: 0.5rem 0.8rem;
            }

            .header-logo {
                height: 40px;
            }

            .header-nav {
                gap: 0.2rem;
            }

            .header-nav a {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }

            .header-text h1 {
                font-size: 1rem;
            }

            .header-subtitle {
                font-size: 0.7rem;
            }

            .btn-salir-header {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .sistema-header {
                padding: 0.4rem 0.5rem;
            }

            .header-container {
                gap: 0.5rem;
            }

            .header-logo {
                height: 35px;
            }

            .header-nav {
                gap: 0.15rem;
            }

            .header-nav a {
                padding: 0.35rem 0.5rem;
                font-size: 0.75rem;
            }

            .header-text h1 {
                font-size: 0.9rem;
            }

            .header-subtitle {
                font-size: 0.65rem;
            }

            .btn-salir-header {
                padding: 0.35rem 0.6rem;
                font-size: 0.75rem;
            }
        }


    </style>
</head>
<body>
    <div class="sistema-header">
        <div class="header-container">
            <img src="https://i0.wp.com/rinconadacountryclub.org.pe/wp-content/uploads/2022/02/logo-rcc-4.png?fit=1024%2C797&ssl=1" alt="Logo Rinconada Country Club" class="header-logo">

            <nav class="header-nav">
                <a href="/registro" class="{{ request()->is('registro') ? 'active' : ''}}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Registro
                </a>
                <a href="/entrada" class="{{ request()->is('entrada') ? 'active' : ''}}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Entrada Club
                </a>
                <a href="/eventos" class="{{ request()->is('eventos') ? 'active' : ''}}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Entrada Evento
                </a>
            </nav>

            <div class="header-text">
                <h1>Sistema de Gestión de Eventos</h1>
                <p class="header-subtitle">Administración de participantes y control de asistencia</p>
            </div>

            <a href="/logout" class="btn-salir-header">Salir</a>
        </div>
    </div>

    <main>
        @yield('content')
    </main>
</body>
</html>
