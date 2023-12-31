<?php

namespace App\Tools;

use App\Models\Adhesion;
use App\Models\Commentaire;
use App\Models\Document;
use App\Models\Foyer;
use App\Models\Groupe;
use App\Models\Personne;
use App\Models\Reglement;
use App\Models\Saison;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class AdherentImporter
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
        $saison = Saison::where(['nom' => '2023 - 2024'])->first();
        $foyer_ids = [];
        $groupes = Groupe::all()->keyBy('code');
        $csv_content = CsvParser::parse_csv($text_content);
        foreach ($csv_content as $raw_adherent) {
            $p1_adresse_email = [];
            if ($this->is_email($raw_adherent['P1 Adresse email'])) {
                $p1_adresse_email[] = $raw_adherent['P1 Adresse email'];
            }
            if ($this->is_email($raw_adherent['Adresse email']) && !in_array($raw_adherent['Adresse email'], $p1_adresse_email)) {
                $p1_adresse_email[] = $raw_adherent['Adresse email'];
            }

            $adherent_adresse_email = [];
            if ($this->is_email($raw_adherent['Adresse email 1'])) {
                $adherent_adresse_email[] = $raw_adherent['Adresse email 1'];
            }
            if ($this->is_email($raw_adherent['Adresse email 2']) && !in_array($raw_adherent['Adresse email 2'], $adherent_adresse_email)) {
                $adherent_adresse_email[] = $raw_adherent['Adresse email 2'];
            }
            if ($this->is_email($raw_adherent['Adresse email']) && !in_array($raw_adherent['Adresse email'], $adherent_adresse_email)) {
                $adherent_adresse_email[] = $raw_adherent['Adresse email'];
            }

            $parent1 = $this->create_personne(
                [
                    'nom' => mb_strtoupper($raw_adherent['P1 Nom']),
                    'prenom' => mb_convert_case(mb_strtolower($raw_adherent['P1 Prénom']), MB_CASE_TITLE),
                    'email1' => $p1_adresse_email[0],
                    'email2' => $p1_adresse_email[1] ?? null,
                    'telephone1' => $this->format_telephone($raw_adherent['P1 Téléphone']),
                ]
            );
            $foyer_id = $parent1?->foyer_id;
            $parent2 = $this->create_personne(
                [
                    'nom' => mb_strtoupper($raw_adherent['P2 Nom']),
                    'prenom' => mb_convert_case(mb_strtolower($raw_adherent['P2 Prénom']), MB_CASE_TITLE),
                    'email1' => $this->is_email($raw_adherent['P2 Adresse email']) ? $raw_adherent['P2 Adresse email'] : null,
                    'telephone1' => $this->format_telephone($raw_adherent['P2 Téléphone']),
                    'foyer_id' => $foyer_id,
                ]
            );
            if (!$foyer_id) {
                $foyer_id = $parent2?->foyer_id;
            }

            $adherent = $this->create_personne(
                [
                    'nom' => mb_strtoupper($raw_adherent['Nom']),
                    'prenom' => mb_convert_case(mb_strtolower($raw_adherent['Prénom']), MB_CASE_TITLE),
                    'email1' => $adherent_adresse_email[0],
                    'email2' => $adherent_adresse_email[1] ?? null,
                    'telephone1' => $this->format_telephone($raw_adherent['Téléphone 1']),
                    'telephone2' => $this->format_telephone($raw_adherent['Téléphone 2']),
                    'adresse_postale' => $raw_adherent['Adresse postale'],
                    'code_postal' => $raw_adherent['Code postal'],
                    'ville' => $raw_adherent['Ville'],
                    'date_naissance' => $this->format_date($raw_adherent['Date de naissance']),
                    'sexe' => $this->format_sexe($raw_adherent['Sexe']),
                    'nationalite' => $this->format_nationalite($raw_adherent['Nationalité']),
                    'ville_naissance' => $raw_adherent['Lieu de naissance'],
                    'date_certificat_medical' => $this->optional_format_date($raw_adherent['CM']),
                    'nom_assurance' => $raw_adherent['Nom de l\'assurance responsabilité civile'],
                    'numero_assurance' => $raw_adherent['Numéro de souscripteur à cette assurance'],
                    'droit_image' => $raw_adherent['Autorisations'] === 'Oui' ? 'O' : ($raw_adherent['Autorisations'] === 'Pas droit à l’image' ? 'N' : null),
                    'numero_licence' => $raw_adherent['N° Licence'],
                    'niveau' => empty($raw_adherent['Niveau']) ? null : $raw_adherent['Niveau'],
                    'foyer_id' => $foyer_id,
                ],
            );

            foreach ($this->get_groupe_code($raw_adherent['Groupe'], $raw_adherent['Créneaux']) as $code) {
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
                'montant_total' => $foyer->montant_total + $this->extract_montant($raw_adherent['total a payer']),
                'montant_regle' => $foyer->montant_regle + $this->extract_montant($raw_adherent['payé']),
            ]);

            if ($raw_adherent['Virements']) {
                Reglement::create([
                    'type' => 'Virement',
                    'date' => $this->extract_date($raw_adherent['mode paiement']),
                    'montant' => $this->extract_montant($raw_adherent['Virements']),
                    'code' => null,
                    'depose' => true,
                    'acquitte' => true,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }
            if ($raw_adherent['Chq Sept']) {
                Reglement::create([
                    'type' => 'Chèque',
                    'date' => '2023-09-01',
                    'montant' => $this->extract_montant($raw_adherent['Chq Sept']),
                    'depose' => true,
                    'acquitte' => true,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            if ($raw_adherent['chq Oct']) {
                Reglement::create([
                    'type' => 'Chèque',
                    'date' => '2023-10-01',
                    'montant' => $this->extract_montant($raw_adherent['chq Oct']),
                    'depose' => true,
                    'acquitte' => true,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            if ($raw_adherent['chq Nov']) {
                Reglement::create([
                    'type' => 'Chèque',
                    'date' => '2023-11-01',
                    'montant' => $this->extract_montant($raw_adherent['chq Nov']),
                    'depose' => false,
                    'acquitte' => false,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            if ($raw_adherent['chq Dec.']) {
                Reglement::create([
                    'type' => 'Chèque',
                    'date' => '2023-12-01',
                    'montant' => $this->extract_montant($raw_adherent['chq Dec.']),
                    'depose' => false,
                    'acquitte' => false,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            if ($raw_adherent['chq Janv']) {
                Reglement::create([
                    'type' => 'Chèque',
                    'date' => '2023-12-01',
                    'montant' => $this->extract_montant($raw_adherent['chq Janv']),
                    'depose' => false,
                    'acquitte' => false,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            if ($raw_adherent['chq Fev']) {
                Reglement::create([
                    'type' => 'Chèque',
                    'date' => '2023-12-01',
                    'montant' => $this->extract_montant($raw_adherent['chq Fev']),
                    'depose' => false,
                    'acquitte' => false,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            if ($raw_adherent['chq Vacance']) {
                Reglement::create([
                    'type' => 'Chèque vacance',
                    'montant' => $this->extract_montant($raw_adherent['chq Vacance']),
                    'depose' => false,
                    'acquitte' => false,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            if ($raw_adherent['Pass\'Sport 50€']) {
                Reglement::create([
                    'type' => 'Pass’Sport',
                    'montant' => 50,
                    'code' => $raw_adherent['Pass\'Sport 50€'],
                    'depose' => false,
                    'acquitte' => false,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            if ($raw_adherent['PassRegion 30€']) {
                Reglement::create([
                    'type' => 'Pass’Région',
                    'montant' => 30,
                    'code' => $raw_adherent['PassRegion 30€'],
                    'depose' => false,
                    'acquitte' => false,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            if ($raw_adherent['espece']) {
                Reglement::create([
                    'type' => 'Espèces',
                    'montant' => $this->extract_montant($raw_adherent['espece']),
                    'depose' => true,
                    'acquitte' => true,
                    'saison_id' => $saison->id,
                    'foyer_id' => $foyer->id,
                ]);
            }

            $commentaire = "";
            if ($raw_adherent['Commentaires']) {
                if (!empty($commentaire)) {
                    $commentaire .= "\n---\n\n";
                }
                $commentaire .= "**Commentaire adhérent** : " . $raw_adherent['Commentaires'] . "\n";
            }
            if ($raw_adherent['Commentaire permanence']) {
                if (!empty($commentaire)) {
                    $commentaire .= "\n---\n\n";
                }
                $commentaire .= "**Commentaire permanence** : " . $raw_adherent['Commentaire permanence'] . "\n";
            }
            if ($raw_adherent['Commentaire Mathieu']) {
                if (!empty($commentaire)) {
                    $commentaire .= "\n---\n\n";
                }
                $commentaire .= "**Commentaire Mathieu** : " . $raw_adherent['Commentaire Mathieu'] . "\n";
            }
            if ($raw_adherent['Commentaires règlement']) {
                if (!empty($commentaire)) {
                    $commentaire .= "\n---\n\n";
                }
                $commentaire .= "**Commentaires règlement** : " . $raw_adherent['Commentaires règlement'] . "\n";
            }
            if ($raw_adherent['mode paiement']) {
                if (!empty($commentaire)) {
                    $commentaire .= "\n---\n\n";
                }
                $commentaire .= "**Mode paiement** : " . $raw_adherent['mode paiement'] . "\n";
            }
            if ($raw_adherent['infos']) {
                if (!empty($commentaire)) {
                    $commentaire .= "\n---\n\n";
                }
                $commentaire .= "**Infos** : " . $raw_adherent['infos'] . "\n";
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

    private function extract_date($str)
    {
        if (preg_match('/^vir (\d+)\/(\d+)/u', $str, $matches)) {
            $jour = $matches[1];
            $mois = $matches[2];
            return '2023-' . sprintf("%02d", $mois) . '-' . sprintf("%02d", $jour);
        }
        return null;
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

    private function get_groupe_code($groupe, $creneau)
    {
        if (str_contains($groupe, '(liste d’attente)')) {
            $groupe = str_replace(' (liste d’attente)', '', $groupe);
        }
        if ($groupe === 'Groupe Ados - 335 €/an') {
            $groupe = 'Groupe Ados (tous niveaux) - 335 €/an';
        }
        if ($groupe === 'Baby du mardi - 335 €/an') {
            return ['2023-baby-mar'];
        }
        if ($groupe === 'Baby du vendredi - 335 €/an') {
            return ['2023-baby-ven'];
        }
        if ($groupe === 'Lame 1 - 335 €/an') {
            return ['2023-lame1'];
        }
        if ($groupe === 'Lames 2 à 8 - 335 €/an') {
            return ['2023-lame2+'];
        }
        if ($groupe === 'Groupe Ados (tous niveaux) - 335 €/an') {
            return ['2023-ados'];
        }
        if ($groupe === 'Groupe 2 : Débutants' && $creneau === 'Mardi - 240 €/an') {
            return ['2023-adulte-deb-mar'];
        }
        if ($groupe === 'Groupe 2 : Débutants' && $creneau === 'Vendredi - 240 €/an') {
            return ['2023-adulte-deb-ven'];
        }
        if ($groupe === 'Groupe 2 : Débutants' && $creneau === 'Samedi - 240 €/an') {
            return ['2023-adulte-deb-sam'];
        }
        if ($groupe === 'Groupe 2 : Débutants' && $creneau === 'Mardi et vendredi - 450 €/an') {
            return ['2023-adulte-deb-mar', '2023-adulte-deb-ven'];
        }
        if ($groupe === 'Groupe 1 : Intermédiaires' && $creneau === 'Mardi - 240 €/an') {
            return ['2023-adulte-int-mar'];
        }
        if ($groupe === 'Groupe 1 : Intermédiaires' && $creneau === 'Vendredi - 240 €/an') {
            return ['2023-adulte-int-ven'];
        }
        if ($groupe === 'Groupe 1 : Intermédiaires' && $creneau === 'Samedi - 240 €/an') {
            return ['2023-adulte-int-sam'];
        }
        if ($groupe === 'Groupe 1 : Intermédiaires' && $creneau === 'Mardi et vendredi - 450 €/an') {
            return ['2023-adulte-int-mar', '2023-adulte-int-ven'];
        }
        if ($groupe === 'Groupe 2 : Danseurs' && $creneau === 'Mardi - 240 €/an') {
            return ['2023-adulte-dan-mar'];
        }
        if ($groupe === 'Groupe 2 : Danseurs' && $creneau === 'Vendredi - 240 €/an') {
            return ['2023-adulte-dan-ven'];
        }
        if ($groupe === 'Groupe 2 : Danseurs' && $creneau === 'Samedi - 240 €/an') {
            return ['2023-adulte-dan-sam'];
        }
        if ($groupe === 'Groupe 2 : Danseurs' && $creneau === 'Mardi et vendredi - 450 €/an') {
            return ['2023-adulte-dan-mar', '2023-adulte-dan-ven'];
        }
        if ($groupe === 'Groupe 1 : Sauteurs' && $creneau === 'Mardi - 240 €/an') {
            return ['2023-adulte-sau-mar'];
        }
        if ($groupe === 'Groupe 1 : Sauteurs' && $creneau === 'Vendredi - 240 €/an') {
            return ['2023-adulte-sau-ven'];
        }
        if ($groupe === 'Groupe 1 : Sauteurs' && $creneau === 'Samedi - 240 €/an') {
            return ['2023-adulte-sau-sam'];
        }
        if ($groupe === 'Groupe 1 : Sauteurs' && $creneau === 'Mardi et vendredi - 450 €/an') {
            return ['2023-adulte-sau-mar', '2023-adulte-sau-ven'];
        }
        if ($groupe === 'PPG' && $creneau === 'Mardi 20h à 21h - 200 €/an') {
            return ['2023-ppg-mar-20'];
        }
        if ($groupe === 'PPG' && $creneau === 'Mardi 21h à 22h - 200 €/an') {
            return ['2023-ppg-mar-21'];
        }
        if ($groupe === 'PPG' && $creneau === 'Mardi 22h à 23h - 200 €/an') {
            return [];
        }
        if ($groupe === 'PPG' && $creneau === 'Vendredi 19h30 à 20h30 - 200 €/an') {
            return [];
        }
        throw new Exception("Groupe inconnu " . $groupe . " - " . $creneau);
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
        if ($raw_adherent['Contact email'] === 'En attente') {
            return 'liste d’attente';
        }
        $complet = $raw_adherent['Assurance'] === 'Oui' && ($raw_adherent['QM'] === 'Oui' || !empty($raw_adherent['CM'])) && ($raw_adherent['Autorisations'] !== 'Oui' || $raw_adherent['Autorisations'] !== 'Pas droit à l’image');
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

        foreach (['adresse_postale', 'code_postal', 'ville', 'sexe', 'nationalite', 'date_naissance', 'ville_naissance', 'date_certificat_medical', 'nom_assurance', 'numero_assurance', 'droit_image', 'niveau', 'numero_licence', 'foyer_id'] as $attribut) {
            if (!isset($personne[$attribut]) && isset($data[$attribut])) {
                $personne[$attribut] = $data[$attribut];
                $change = true;
            } else if (isset($personne[$attribut]) && isset($data[$attribut]) && mb_strtolower($personne[$attribut]) !== mb_strtolower($data[$attribut])) {
                $this->traces['diff'][] = $attribut . " " . print_r($personne[$attribut], true) . " => " . print_r($data[$attribut], true);
                $diff = true;
            }
        }

        if ($diff) {
            $personne->refresh();
            $this->traces['diffPersonne']++;
        } else
        if ($change) {
            $personne->save();
            $this->traces['changedPersonne']++;
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
