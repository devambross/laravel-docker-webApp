<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Class PageController extends Controller
{
    public function entrada(Request $request)
    {
        // Verificar sesión
        if (!$request->session()->has('user')) {
            return redirect()->route('login.form');
        }
        return view('entrada');
    }

    public function registro(Request $request)
    {
        // Verificar sesión
        if (!$request->session()->has('user')) {
            return redirect()->route('login.form');
        }
        return view('registro');
    }

    public function eventos()
    {
        //Datos de ejemplo
        $eventos = [
            ['name' => 'Diseño Web', 'price' => 500],
            ['name' => 'Desarrollo backend', 'price' => 800],
            ['name' => 'SEO y Marketing', 'price' => 300],
        ];

        //Pasar datos a la vista
        return view('eventos', ['eventos' => $eventos]);
    }

}
