<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function home()
    {
        return view('home');
    }

    public function services()
    {
        //Datos de ejemplo
        $services = [
            ['name' => 'Diseño Web', 'price' => 500],
            ['name' => 'Desarrollo backend', 'price' => 800],
            ['name' => 'SEO y Marketing', 'price' => 300],
        ];

        //Pasar datos a la vista
        return view('services', ['services' => $services]);
    }

    public function contactForm()
    {
        return view('contact-form');
    }

    public function handleContact(\Illuminate\Http\Request $request)
    {
        //Obtener datos enviados
        $name = $request->input('name');
        $message = $request->input('message');

        //Simulamos que gaurdamos o enviamos los datos
        //Aquí solo retornamos a una vista
        return view('contact-success', [
            'name' => $name,
            'message' => $message
        ]);
    }
}
