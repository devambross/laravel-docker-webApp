@extends('layouts.app')

@section('title', 'Formulario de Contacto')

@section('content')
    <h2>Formulario de Contacto</h2>

    <form action="/contact-form" method="POST">
        @csrf <!-- Token de seguridad obligatorio en Laravel -->

        <label for="name">Nombre:</label><br>
        <input type="text" name="name" required><br><br>

        <label for="message">Mensaje:</label><br>
        <textarea name="message" required></textarea><br><br>

        <button type="submit">Enviar</button>
    </form>
@endsection
