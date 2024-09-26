<?php
$saison = [];
foreach (\App\Models\Saison::all() as $saison) {
    $saisons[$saison->id] = $saison->nom;
}
?>
<div class="inline-flex mt-5">
    <form action="{{ route('saison.select') }}" method="POST">
        @csrf
        <x-dropdown id="saison" :options="$saisons" :value="session('saison_id')" submitOnChange="true" />
    </form>
</div>