<li class="mb-2 block">
    {{ $passage->date }} {{ $passage->niveau }} {{ $passage->etat }}{{ $passage->examinateur ? " ($passage->examinateur)" : "" }}
    @if ($passage->medaille)
        <br/>
        Médaille "{{ $passage->medaille }}" {{ $passage->medaille_remise ? "remise" : "non remise" }}
    @endif
</li>
