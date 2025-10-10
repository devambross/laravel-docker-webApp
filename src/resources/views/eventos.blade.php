@extends('layouts.app')

@section('title', 'Eventos')

@section('content')
    <h2>Servicios Disponibles</h2>
    <ul>
        @foreach ($eventos as $evento)
            <li>
                <strong>{{ $evento['name'] }}</strong> - ${{ $evento['price'] }}
            </li>
        @endforeach
    </ul>
@endsection
