<!DOCTYPE html>
<html>

<head>
    <title>Attestation de paiement</title>
    <style>
        body {
            font-family: 'Inter,ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji';
        }
        .destinataire {
            float: right;
            max-width: 30%;
        }
        .destinataire>p {
            margin: 0;
        }
        .lgp>p {
            margin: 0;
            font-size: 80%;
            color: #cccccc;
        }
        .titre {
            clear: both;
            text-align: center;
            margin: 60px;
        }
        .groupe,.prix {
            font-weight: bold;
        }
        .contenu {
            padding: 0 40px;
        }
        .signature {
            padding-left: 50%;
        }
        .signature>img {
            width: 200px;
        }
    </style>
</head>

@php
$adhesions = [];
foreach ($adherent->adhesions as $adhesion) {
    if ($adhesion->etat === 'validé' || $adhesion->etat === 'réglé') {
        $adhesions[] = $adhesion;
    }
}
@endphp

<body>
    <div class="destinataire">
        <p>{{ $adherent->nom }} {{ $adherent->prenom }}</p>
        <p>{{ $adherent->adresse_postale }}</p>
        <p>{{ $adherent->code_postal }} {{ $adherent->ville }}</p>
        <p>{{ $adherent->email1 }}</p>
    </div>
    <div class="lgp">
        <img src="{{ public_path('assets/img/lgp-pdf-logo.PNG') }}"/>
        <p>Association loi 1901 siret 449 910 751 00014</p>
        <p>52 rue Baraban – 69003 Lyon</p>
        <p>info@lyonglacepatinage.fr</p>
    </div>
    <div class="titre">
        <h1>Attestation de paiement</h1>
        <h2>Saison 2023-2024</h2>
    </div>
    <div class="contenu">
        <p>Je soussigné, trésorier du club sportif <strong>L</strong>yon <strong>G</strong>lace
            <strong>P</strong>atinage, atteste que l’adhérent{{ $adherent->sexe === 'F' ? 'e' : '' }} {{ $adherent->nom }} {{ $adherent->prenom }},
            inscrit{{ $adherent->sexe === 'F' ? 'e' : '' }} dans
            {{ count($adhesions) > 1 ? 'les' : 'le' }} cours
            @foreach ($adhesions as $adhesion)
                <span
                    class="groupe">{{ $adhesion->groupe->nom }}</span>{{ $loop->remaining > 1 ? ',' : (!$loop->last ? ' et ' : ',') }}
            @endforeach
            a acquitté sa cotisation pour la saison 2023-2024 pour un montant de
            <span class="prix">{{ str_replace('.', ',', $adherent->foyer->montant_regle) }}&nbsp;€</span>.
        </p>
        <p>Fait à Lyon le {{ date('d/m/Y') }}.</p>
        <p>Pour faire valoir ce que de droit.</p>
    </div>
    <div class="signature">
        <p>Le trésorier&nbsp;:</p>
        <img src="{{ public_path('assets/img/lgp-pdf-jep.PNG') }}"/>
    </div>
</body>

</html>
