@extends('layouts.app')

@section('title', 'Entrada')

@section('content')

<!-- Contenedor de notificaciones -->
<div class="notification-container" id="notificationContainer"></div>

@include('partials.entrada_club_tab')

@include('partials.entrada_scripts')

@endsection
