<li class="mb-2">
    <a href="{{ route('groupe.show', $adhesion->groupe->id) }}" class="block hover:underline">
        {{ $adhesion->groupe->nom }} <x-adhesion-etat :adhesion="$adhesion" />
    </a>
</li>
