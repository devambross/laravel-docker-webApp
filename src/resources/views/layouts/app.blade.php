<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Web')</title>
    <style>
        /*Estructura General */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #fdfdfd;
        }
        main {
            padding: 2rem;
            min-height: 70vh;
            background: #fdfdfd;
        }

        /*Cinta Superior*/
        .cinta-superior{
            display: flex;
            align-items: center;
            background: #78B548;
            color: #fff;
            padding: 0;
            flex-wrap: wrap;
        }
        .cinta-superior img{
            height: 50px;
            margin: 10px;
        }

        .cinta-opciones{
            background: #78B548;
            color: #fff;
            flex: 1;
        }
        .cinta-opciones h1{
            margin: 0;
            padding: 1rem;
            color: #003C3E;
        }

        /*NAV */
        nav {
            display: flex;
            background: #78B548;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        nav a:hover {
            background: #6aa23f;
        }

        nav a.active {
            background: #fdfdfd;
            color: #003C3E;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        /*MODO RESPONSIVO*/
        @media (max-width: 768px) {
            .cinta-superior {
                flex-direction: column;
                align-items: flex-start;
            }

            .cinta-opciones {
                width: 100%;
            }

            nav {
                width: 100%;
                justify-content: space-around;
            }

            nav a {
                flex: 1;
                text-align: center;
                border-top: 1px solid rgba(255,255,255,0.2);
            }
        }


    </style>
</head>
<body>
    <div class="cinta-superior">
        <img src="https://i0.wp.com/rinconadacountryclub.org.pe/wp-content/uploads/2022/02/logo-rcc-4.png?fit=1024%2C797&ssl=1" alt="Logo" style="height:50px; margin:10px;">
        <div class="cinta-opciones">
            <h1></h1>
            <nav>
                <a href="/registro" class="{{ request()->is('registro') ? 'active' : ''}}">Registro</a>
                <a href="/entrada" class="{{ request()->is('entrada') ? 'active' : ''}}">Entrada</a>
                <a href="/eventos" class="{{ request()->is('eventos') ? 'active' : ''}}">Eventos</a>
                <a href="/logout" style="margin-left:auto;">Salir</a>
            </nav>
        </div>
    </div>

    <main>
        @yield('content')
    </main>
</body>
</html>
