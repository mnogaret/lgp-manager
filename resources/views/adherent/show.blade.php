@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<h1>{{ $adherent->prenom }} {{ $adherent->nom }}
@if ($adherent->sexe === 'M')
<i class="fas fa-mars text-blue-500"></i>
@endif
@if ($adherent->sexe === 'F')
<i class="fas fa-venus text-pink-400"></i>
@endif
<p>{{ $adherent->email1 }}</p>
@endsection