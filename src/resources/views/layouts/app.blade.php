<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mi Web')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #fdfdfd;
        }
        header {
            background: #78B548;
            color: #fff;
            padding: 0;
        }
        header h1 {
            margin: 0;
            padding: 1rem;
            color: #003C3E;
        }
        nav {
            display: flex;
            background: #78B548;
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
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
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
        header, nav{
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <header>
        <h1>Rinconada Country Club</h1>
        <nav>
            <a href="/registro" class="{{ request()->is('registro') ? 'active' : ''}}">Registro</a>
            <a href="/entrada" class="{{ request()->is('entrada') ? 'active' : ''}}">Entrada</a>
            <a href="/eventos" class="{{ request()->is('eventos') ? 'active' : ''}}">Eventos</a>
            <a href="/logout" style="margin-left:auto;">Salir</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>© 2025 Rinconada Country Club</p>
    </footer>
</body>
</html>
