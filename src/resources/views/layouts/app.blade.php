<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mi Web')</title>
    <style>
        .cinta-superior{
            display: flex;
            background: #78B548;
            color: #fff;
            padding: 0;
        }
        .cinta-opciones{
            background: #78B548;
            color: #fff;
            padding: 0;
            flex: 1;
        }
        .cinta-opciones h1{
            margin: 0;
            padding: 1rem;
            color: #003C3E;
        }
        .cinta-opciones nav{
            display: flex;
            background: #78B548;
        }
        .cinta-opciones nav a{
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.2s ease;
            text-align: end;
        }
        .cinta-opciones nav a:hover{
            background: #6aa23f;
        }
        .cinta-opciones nav a.active{
            background: #fdfdfd;
            color: #003C3E;
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
        }


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
        footer {
            background: #f1f1f1;
            text-align: center;
            padding: 1rem;
            color: #555;
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

    <footer>
        <p>© 2025 Rinconada Country Club</p>
    </footer>
</body>
</html>
