<?php

namespace App\Tools;

use App\Models\Adhesion;
use App\Models\Commentaire;
use App\Models\Foyer;
use App\Models\Groupe;
use App\Models\Personne;
use App\Models\Reglement;
use App\Models\Saison;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;

class Adherent2024Importer
{
    public $traces = [
        'newPersonne' => 0,
        'existingPersonne' => 0,
        'diffPersonne' => 0,
        'changedPersonne' => 0,
        'existingAdhesion' => 0,
        'newAdhesion' => 0,
        'newFoyer' => 0,
        'newDocument' => 0,
        'existingDocument' => 0,
        'diff' => [],
    ];
    public function from_csv($text_content)
    {
        $saison = Saison::where(['nom' => '2024 - 2025'])->first();
        $foyer_ids = [];
        $groupes = Groupe::all()->keyBy('code');
        $csv_content = CsvParser::parse_csv($text_content);
        foreach ($csv_content as $raw_adherent) {
            $p1_adresse_email = [];
            if ($this->is_email($raw_adherent['Adresse email (inscr)'])) {
                $p1_adresse_email[] = $raw_adherent['Adresse email (inscr)'];
            }
            if ($this->is_email($raw_adherent['P1 Adresse email']) && !in_array($raw_adherent['P1 Adresse email'], $p1_adresse_email)) {
                $p1_adresse_email[] = $raw_adherent['P1 Adresse email'];
            }
            if ($this->is_email($raw_adherent['Adresse email']) && !in_array($raw_adherent['Adresse email'], $p1_adresse_email)) {
                $p1_adresse_email[] = $raw_adherent['Adresse email'];
            }

            $adherent_adresse_email = [];
            if ($this->is_email($raw_adherent['Adresse email (inscr)'])) {
                $adherent_adresse_email[] = $raw_adherent['Adresse email (inscr)'];
            }
            if ($this->is_email($raw_adherent['Adresse email']) && !in_array($raw_adherent['Adresse email'], $adherent_adresse_email)) {
                $adherent_adresse_email[] = $raw_adherent['Adresse email'];
            }

            $parent = $this->create_personne(
                [
                    'nom' => mb_strtoupper($raw_adherent['P1 Nom']),
                    'prenom' => mb_convert_case(mb_strtolower($raw_adherent['P1 Prénom']), MB_CASE_TITLE),
                    'email1' => $p1_adresse_email[0],
                    'email2' => $p1_adresse_email[1] ?? null,
                    'telephone1' => $this->format_telephone($raw_adherent['P1 Téléphone']),
                ]
            );
            $foyer_id = $parent?->foyer_id;

            $adherent = $this->create_personne(
                [
                    'nom' => mb_strtoupper($raw_adherent['Nom']),
                    'prenom' => mb_convert_case(mb_strtolower($raw_adherent['Prénom']), MB_CASE_TITLE),
                    'email1' => $adherent_adresse_email[0],
                    'email2' => $adherent_adresse_email[1] ?? null,
                    'telephone1' => $this->format_telephone($raw_adherent['Téléphone']),
                    'adresse_postale' => $raw_adherent['Adresse postale'],
                    'code_postal' => $raw_adherent['Code postal'],
                    'ville' => $raw_adherent['Ville'],
                    'date_naissance' => $this->format_date($raw_adherent['Date de naissance']),
                    'sexe' => $this->format_sexe($raw_adherent['Sexe']),
                    'nationalite' => $this->format_nationalite($raw_adherent['Nationalité']),
                    'ville_naissance' => $raw_adherent['Ville et pays de naissance'],
                    'date_certificat_medical' => $this->optional_format_date($raw_adherent['CM']),
                    'nom_assurance' => $raw_adherent['Nom de l\'assurance responsabilité civile'],
                    'numero_assurance' => $raw_adherent['Numéro de souscripteur à cette assurance'],
                    'droit_image' => $raw_adherent['Autorisations'] === 'Oui' ? 'O' : ($raw_adherent['Autorisations'] === 'Pas droit à l’image' ? 'N' : null),
                    'numero_licence' => $raw_adherent['N° Licence'],
                    'niveau' => empty($raw_adherent['Médaille obtenue']) ? null : $raw_adherent['Médaille obtenue'],
                    'foyer_id' => $foyer_id,
                    'badge' => $raw_adherent['Badge'],
                    'hash_code' => $raw_adherent['Hash'],
                ],
            );

            foreach ($this->get_groupe_code($raw_adherent['Groupe']) as $code) {
                $this->create_adhesion($adherent, $this->compute_etat($raw_adherent), $groupes[$code], $this->format_date_time($raw_adherent['Horodateur']));
            }

            $foyer = Foyer::findOrFail($adherent->foyer_id);
            if (!in_array($foyer->id, $foyer_ids)) {
                Reglement::where('foyer_id', $foyer->id)->delete();
                $foyer->update([
                    'montant_total' => 0,
                    'montant_regle' => 0,
                ]);
                $foyer_ids[] = $foyer->id;
            }
            $foyer->update([
                'montant_total' => $foyer->montant_total + $this->extract_montant($raw_adherent['Cotis a payer']),
                'montant_regle' => $foyer->montant_regle + $this->extract_montant($raw_adherent['payé']),
            ]);

            $commentaire = "";
            if ($raw_adherent['Commentaire']) {
                if (!empty($commentaire)) {
                    $commentaire .= "\n---\n\n";
                }
                $commentaire .= "**Commentaire** : " . $raw_adherent['Commentaire'] . "\n";
            }
            if ($raw_adherent['Méthode de paiement']) {
                if (!empty($commentaire)) {
                    $commentaire .= "\n---\n\n";
                }
                $commentaire .= "**Mode paiement** : " . $raw_adherent['Méthode de paiement'] . "\n";
            }
            if (!empty($commentaire)) {
                Commentaire::create([
                    'user_id' => Auth::user()->id,
                    'type' => 'Import',
                    'foyer_id' => $foyer->id,
                    'personne_id' => $adherent->id,
                    'commentaire' => $commentaire,
                ]);
            }
        }

        $this->remove_commentaire_duplicates();
    }

    private function remove_commentaire_duplicates()
    {
        // Récupération des IDs des commentaires les plus anciens à conserver
        $commentairesAConserver = Commentaire::selectRaw('MIN(id) as id')
            ->groupBy('user_id', 'type', 'foyer_id', 'personne_id', 'commentaire')
            ->pluck('id');

        // Suppression des doublons en ne conservant que les commentaires les plus anciens
        Commentaire::whereNotIn('id', $commentairesAConserver)->delete();
    }

    private function extract_montant($str)
    {
        if (empty($str)) {
            return 0;
        }
        // Suppression des espaces insécables
        $str = str_replace(' ', '', $str);
        if (preg_match('/^([-\d,]+)( €)?$/u', $str, $matches)) {
            $montant = str_replace(',', '.', $matches[1]);
            return floatval($montant);
        }
        throw new Exception("Le montant n'est pas valide : $str");
    }

    private function get_groupe_code($groupe)
    {
        if ($groupe === 'Baby') {
            return ['2024-baby'];
        }
        if ($groupe === 'Initiation 1') {
            return ['2024-initiation1'];
        }
        if ($groupe === 'Initiation 2') {
            return ['2024-initiation2'];
        }
        if ($groupe === 'Groupe ados') {
            return ['2024-ados'];
        }
        if ($groupe === 'Débutant - mardi') {
            return ['2024-adulte-deb-mar'];
        }

        if ($groupe === 'Débutant - mercredi') {
            return ['2024-adulte-deb-mer'];
        }

        if ($groupe === 'Débutant - samedi') {
            return ['2024-adulte-deb-sam'];
        }

        if ($groupe === 'Intermédiaire - mardi') {
            return ['2024-adulte-int-mar'];
        }

        if ($groupe === 'Intermédiaire - samedi') {
            return ['2024-adulte-int-sam'];
        }

        if ($groupe === 'Danseur - mardi') {
            return ['2024-adulte-dan-mar'];
        }

        if ($groupe === 'Danseur - mercredi') {
            return ['2024-adulte-dan-mer'];
        }

        if ($groupe === 'Sauteur - mardi') {
            return ['2024-adulte-sau-mar'];
        }

        if ($groupe === 'Sauteur - samedi') {
            return ['2024-adulte-sau-sam'];
        }

        if ($groupe === 'PPG - mardi - 1er créneau') {
            return ['2024-ppg-mar-1'];
        }
        if ($groupe === 'PPG - mardi - 2ème créneau') {
            return ['2024-ppg-mar-2'];
        }
        if ($groupe === 'PPG - mercredi') {
            return ['2024-ppg-mer'];
        }
        if ($groupe === 'Débutant - dimanche - 1er créneau') {
            return ['2024-adulte-deb-dim1'];
        }
        if ($groupe === 'Débutant - dimanche - 2ème créneau') {
            return ['2024-adulte-deb-dim2'];
        }
        if ($groupe === 'P1') {
            return ['2024-p1'];
        }
        if ($groupe === 'P2') {
            return ['2024-p2'];
        }
        throw new Exception("Groupe inconnu " . $groupe);
    }

    private function is_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function format_date($date)
    {
        list($day, $month, $year) = explode('/', $date);
        return "$year-$month-$day";
    }

    function optional_format_date($date)
    {
        return $date ? $this->format_date($date) : null;
    }

    function format_date_time($date)
    {
        return DateTime::createFromFormat('d/m/Y H:i:s', $date)->format('Y-m-d H:i:s');
    }

    private function format_telephone($telephone)
    {
        if (!$telephone) {
            return null;
        }
        $telephone = preg_replace("/[\. ]/", '', $telephone);
        if (preg_match("/^0\d{9}$/", $telephone)) {
            return $telephone;
        }
        if (preg_match("/^\d{9}$/", $telephone)) {
            return '0' . $telephone;
        }
        if (preg_match("/^33\d{9}$/", $telephone)) {
            return '0' . substr($telephone, 2);
        }
        return $telephone;
    }

    private function compute_etat($raw_adherent)
    {
        if ($raw_adherent['Contact email'] === 'Annulé') {
            return 'annulé';
        }
        if ($raw_adherent['Liste d’attente'] === 'Oui') {
            return 'liste d’attente';
        }
        $complet = ($raw_adherent['QM'] === 'Oui' || $raw_adherent['QM'] === 'Majeur' || !empty($raw_adherent['CM'])) && ($raw_adherent['Autorisations'] !== 'Oui' || $raw_adherent['Autorisations'] !== 'Pas droit à l’image') && $raw_adherent['Photo'] !== 'Non';
        $regle = $raw_adherent['Règlement'] === 'Acquitté' || $raw_adherent['Règlement'] === 'Reçu';
        if ($complet && $regle) {
            return "validé";
        }
        if ($regle) {
            return "regle";
        }
        if ($raw_adherent['Essai'] === 'Oui') {
            return 'essai';
        }
        if ($complet) {
            return "complet";
        }
        return "créé";
    }

    private function create_adhesion($adherent, $etat, $groupe, $date)
    {
        $data = [
            'personne_id' => $adherent->id,
            'date_creation_dossier' => $date,
            'groupe_id' => $groupe->id,
            'etat' => $etat,
        ];

        $adhesion = Adhesion::where('personne_id', $data['personne_id'])->where('groupe_id', $data['groupe_id'])->first();
        if ($adhesion) {
            $this->traces['existingAdhesion']++;
            $adhesion['etat'] = $etat;
            $adhesion->save();
            return $adhesion;
        }
        $this->traces['newAdhesion']++;
        return Adhesion::create($data);
    }

    private function create_personne($data)
    {
        if (!$data['nom'] || !$data['prenom']) {
            return null;
        }
        $personne = Personne::where('nom', $data['nom'])->where('prenom', $data['prenom'])->first();
        if ($personne) {
            $this->merge_personne($personne, $data);
            $personne->save();
            return $personne;
        }
        $this->traces['newPersonne']++;
        if (!isset($data['foyer_id'])) {
            $this->traces['newFoyer']++;
            $foyer = Foyer::create();
            $data['foyer_id'] = $foyer->id;
        }
        return Personne::create($data);
    }

    private function merge_personne($personne, $data)
    {
        $change = false;
        $diff = false;
        $emails = [];
        if (isset($personne['email1'])) {
            $emails[] = $personne['email1'];
        }
        if (isset($personne['email2'])) {
            $emails[] = $personne['email2'];
        }
        if (isset($data['email1']) && !in_array($data['email1'], $emails)) {
            $emails[] = $data['email1'];
        }
        if (isset($data['email2']) && !in_array($data['email2'], $emails)) {
            $emails[] = $data['email2'];
        }
        $telephones = [];
        if (isset($personne['telephone1'])) {
            $telephones[] = $personne['telephone1'];
        }
        if (isset($personne['telephone2'])) {
            $emaitelephonesls[] = $personne['telephone2'];
        }
        if (isset($data['telephone1']) && !in_array($data['telephone1'], $telephones)) {
            $telephones[] = $data['telephone1'];
        }
        if (isset($data['telephone2']) && !in_array($data['telephone2'], $telephones)) {
            $telephones[] = $data['telephone2'];
        }
        if (!isset($personne['email1']) && (count($emails) > 0) && $personne['email1'] !== $emails[0]) {
            $personne['email1'] = $emails[0];
            $change = true;
        }
        if (!isset($personne['email2']) && (count($emails) > 1) && $personne['email2'] !== $emails[1]) {
            $personne['email2'] = $emails[1];
            $change = true;
        }
        if (!isset($personne['telephone1']) && (count($telephones) > 0) && $personne['telephone1'] !== $telephones[0]) {
            $personne['telephone1'] = $telephones[0];
            $change = true;
        }
        if (!isset($personne['telephone2']) && (count($telephones) > 1) && $personne['telephone2'] !== $telephones[1]) {
            $personne['telephone2'] = $telephones[1];
            $change = true;
        }

        // Modifications affichées mais non appliquées
        foreach (['adresse_postale', 'code_postal', 'ville', 'sexe', 'nationalite', 'date_naissance', 'ville_naissance', 'date_certificat_medical', 'nom_assurance', 'numero_assurance', 'droit_image', 'foyer_id'] as $attribut) {
            if (!isset($personne[$attribut]) && isset($data[$attribut])) {
                $personne[$attribut] = $data[$attribut];
                $change = true;
            } else if (isset($personne[$attribut]) && isset($data[$attribut]) && $personne[$attribut] !== $data[$attribut]) {
                $this->traces['diff'][] = $attribut . " " . print_r($personne[$attribut], true) . " => " . print_r($data[$attribut], true);
                $diff = true;
            }
        }

        // Modifications appliquées
        foreach (['niveau', 'hash_code', 'badge', 'numero_licence'] as $attribut) {
            if (!isset($personne[$attribut]) && isset($data[$attribut])) {
                $this->traces['update'][] = $attribut . " => " . print_r($data[$attribut], true);
                $personne[$attribut] = $data[$attribut];
                $change = true;
            } else if (isset($personne[$attribut]) && isset($data[$attribut]) && $personne[$attribut] !== $data[$attribut]) {
                $this->traces['update'][] = $attribut . " " . print_r($personne[$attribut], true) . " => " . print_r($data[$attribut], true);
                $personne[$attribut] = $data[$attribut];
                $change = true;
            }
        }

        if ($change) {
            $personne->save();
            $this->traces['changedPersonne']++;
        } else
        if ($diff) {
            $personne->refresh();
            $this->traces['diffPersonne']++;
        } else {
            $this->traces['existingPersonne']++;
        }
    }

    private function format_sexe($sexe)
    {
        if ($sexe === 'Masculin') {
            return 'M';
        }
        if ($sexe === 'Féminin') {
            return 'F';
        }
        return $sexe;
    }

    private function format_nationalite($nationalite)
    {
        if (mb_strtolower($nationalite) === 'allemande') {
            return 'Allemand';
        }
        if (mb_strtolower($nationalite) === 'américain') {
            return 'Américain';
        }
        if (mb_strtolower($nationalite) === 'brésilienne') {
            return 'Brésilien';
        }
        if (mb_strtolower($nationalite) === 'canadienne' || mb_strtolower($nationalite) === 'canada') {
            return 'Canadien';
        }
        if (mb_strtolower($nationalite) === 'chinoise') {
            return 'Chinois';
        }
        if (mb_strtolower($nationalite) === 'espagnole') {
            return 'Espagnol';
        }
        if (mb_strtolower($nationalite) === 'estonienne') {
            return 'Estonien';
        }
        if (mb_strtolower($nationalite) === 'française' || mb_strtolower($nationalite) === 'français' || mb_strtolower($nationalite) === 'fr' || mb_strtolower($nationalite) === 'francaise' || mb_strtolower($nationalite) === 'francaiss' || mb_strtolower($nationalite) === 'francaisz' || mb_strtolower($nationalite) === 'france' || mb_strtolower($nationalite) === 'française / americaine') {
            return 'Français';
        }
        if (mb_strtolower($nationalite) === 'lesotho') {
            return 'Lésothien';
        }
        if (mb_strtolower($nationalite) === 'libanaise') {
            return 'Libanais';
        }
        if (mb_strtolower($nationalite) === 'lituanienne') {
            return 'Lituanien';
        }
        if (mb_strtolower($nationalite) === 'marocaine') {
            return 'Marocain';
        }
        if (mb_strtolower($nationalite) === 'russe') {
            return 'Russe';
        }
        if (mb_strtolower($nationalite) === 'swedish') {
            return 'Suédois';
        }
        if (mb_strtolower($nationalite) === 'tunisienne') {
            return 'Tunisien';
        }
        if (mb_strtolower($nationalite) === 'ukrainienne') {
            return 'Ukrainien';
        }
        if (mb_strtolower($nationalite) === 'algérienne') {
            return 'Algérien';
        }

        return $nationalite;
    }
}
