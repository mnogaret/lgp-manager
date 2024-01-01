<li class="mb-2 block">
    <a href="{{ route('groupe.show', $adhesion->groupe->id) }}" class="hover:underline">
        {{ $adhesion->groupe->nom }}
    </a>
    <x-adhesion-etat :adhesion="$adhesion" />
</li>
