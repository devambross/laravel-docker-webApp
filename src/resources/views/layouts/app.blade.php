<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mi Web')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header { background: #333; color: white; padding: 1rem; }
        nav a { color: white; margin-right: 1rem; text-decoration: none; }
        main { padding: 2rem; }
        footer { background: #f1f1f1; text-align: center; padding: 1rem; margin-top: 2rem; }
    </style>
</head>
<body>
    <header>
        <h1>Mi Sitio Web</h1>
        <nav>
            <a href="/">Inicio</a>
            <a href="/about">About</a>
            <a href="/contact">Contacto</a>
            <a href="/services">Servicios</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>© 2025 Mi Sitio Web</p>
    </footer>
</body>
</html>
