<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(135deg, #2c5f2d 0%, #1e4620 100%);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background-color: #fff;
            border-radius: 10px;
            padding: 2.5rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo-container {
            background-color: #78B548;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 110px;
            height: 110px;
            border-radius: 50%;
            margin-bottom: 1.5rem;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        h1 {
            color: #78B548;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        p.lead {
            color: #555;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        label {
            font-weight: 600;
            display: block;
            text-align: left;
            margin-top: 1rem;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.4rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #78B548;
        }

        button {
            background-color: #78B548;
            color: white;
            border: none;
            width: 100%;
            padding: 0.85rem;
            margin-top: 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1rem;
            font-weight: 600;
        }

        button:hover {
            background-color: #6aa23f;
        }

        .error {
            color: #cc3d3d;
            background-color: #ffe6e6;
            padding: 0.75rem;
            border-radius: 6px;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        @media (max-width: 600px) {
            body {
                padding: 0.5rem;
            }

            .login-container {
                padding: 1.5rem;
                max-width: 100%;
            }

            h1 {
                font-size: 1.5rem;
            }

            .logo-container {
                width: 90px;
                height: 90px;
            }

            .logo {
                width: 60px;
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

            <label>Correo/Usuario:</label>
            <input type="email" name="email" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
