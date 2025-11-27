<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm(Request $request)
    {
        if($request->session()->has('user')) {
            return redirect()->route('registro');
        }
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        // Datos simulados (hardcodeados)
        $user = [
            'email' => 'admin@example.com',
            'password' => '123456'
        ];

        // Validar campos del formulario
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Comparar con datos de prueba
        if ($credentials['email'] === $user['email'] && $credentials['password'] === $user['password']) {
            // Regenerar ID de sesión para seguridad
            $request->session()->regenerate();

            // Guardar sesión
            $request->session()->put('user', $user);

            // Forzar que se guarde la sesión inmediatamente
            $request->session()->save();

            return redirect()->route('registro');
        }

        return back()->withErrors(['login' => 'Credenciales incorrectas.']);
    }

    // Mostrar página después del login
    public function registro(Request $request)
    {
        if (!$request->session()->has('user')) {
            return redirect()->route('login.form');
        }

        $user = $request->session()->get('user');
        return view('registro', compact('user'));
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        $request->session()->forget('user');
        return redirect()->route('login.form');
    }
}
