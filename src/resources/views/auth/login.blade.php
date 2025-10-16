<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fdfdfd;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background-color: #fafafad2;
            border: 1px solid #003C3E;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        .logo-container {
            background-color: #78B548;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 110px;
            height: 110px;
            border-radius: 50%; /* redondo */
            margin-bottom: 1rem;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        h1 {
            color: #003C3E;
            margin-bottom: 0.5rem;
        }

        p.lead {
            color: #555;
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: bold;
            display: block;
            text-align: left;
            margin-top: 1rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.6rem;
            margin-top: 0.4rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #003C3E;
            color: white;
            border: none;
            width: 100%;
            padding: 0.75rem;
            margin-top: 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1rem;
        }

        button:hover {
            background-color: #6aa23f;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 0.5rem;
        }

        @media (max-width: 600px) {
            .login-container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="https://i0.wp.com/rinconadacountryclub.org.pe/wp-content/uploads/2022/02/logo-rcc-4.png?fit=1024%2C797&ssl=1"
            alt="Logo" class="logo">
        </div>

        <h1>Iniciar sesión</h1>
        <p class="lead">Por favor, ingresa tus credenciales</p>

        @if ($errors->any())
            <p class="error">{{ $errors->first('login') }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
