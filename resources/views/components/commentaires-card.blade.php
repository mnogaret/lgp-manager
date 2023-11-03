<div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Commentaires</h5>
    @foreach ($commentaires as $commentaire)
        <div class="px-6 py-4 my-2 border border-gray-200 shadow dark:border-gray-700">
            <h6 class="font-bold">{{ $commentaire->created_at }} - {{ $commentaire->user->first_name }}
                {{ $commentaire->user->last_name }}</h6>
            @if ($commentaire->personne)
                <p class="text-gray-600 dark:text-gray-400">À propos de {{ $commentaire->personne->prenom }} {{ $commentaire->personne->nom }}</p>
            @endif
            @php
            $converter = new League\CommonMark\CommonMarkConverter();
            // Assurez-vous d'échapper correctement le contenu pour la sécurité.
            $htmlCommentaire = $converter->convertToHtml($commentaire->commentaire);;
            @endphp
            <p>{!! $htmlCommentaire !!}</p>
        </div>
    @endforeach
</div>
