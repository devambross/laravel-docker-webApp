@extends('layouts.app')

@section('title', 'Mensaje Enviado')

@section('content')
    <h2>¡Gracias por contactarnos, {{ $name }}!</h2>
    <p>Tu mensaje fue: "{{ $message }}"</p>
@endsection
