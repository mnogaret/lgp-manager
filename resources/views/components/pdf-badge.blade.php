<div class="card">
    {{ $adherent->prenom }}
    {{ $adherent->nom }}
    {{ $adherent->niveau }}
    @foreach ($adherent->adhesions as $adhesion)
        <br> {{ $adhesion->groupe->code }}
    @endforeach
</div>