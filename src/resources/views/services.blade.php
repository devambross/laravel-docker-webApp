@extends('layouts.app')

@section('title', 'Servicios')

@section('content')
    <h2>Servicios Disponibles</h2>
    <ul>
        @foreach ($services as $service)
            <li>
                <strong>{{ $service['name'] }}</strong> - ${{ $service['price'] }}
            </li>
        @endforeach
    </ul>
@endsection
