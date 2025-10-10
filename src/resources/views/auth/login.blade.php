<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Iniciar sesión</h1>

    @if ($errors->any())
        <p style="color:red;">{{ $errors->first('login') }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
