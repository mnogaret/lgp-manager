<div
    class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <div class="flex justify-between items-center">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ $document->type }}
        </h5>
        <x-badge
            color="{{ $document->statut === 'OK' ? 'green' : 'red' }}">{{ $document->statut === 'OK' ? 'Valide' : 'Invalide' }}</x-badge>
    </div>
    <p class="font-normal text-gray-700 dark:text-gray-400">
        @if ($document->url)
            <a href="{{ $document->url }}" target="_blank" class="card-link">Voir le document</a>
        @endif
        @if ($document->description)
            <p class="card-text mt-2">
                Description: {{ $document->description }}
            </p>
        @endif
    </p>
</div>
