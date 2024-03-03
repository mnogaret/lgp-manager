<?php

namespace App\Tools;

use App\Models\PassageDeLame;
use App\Models\Personne;
use DateTime;

class PassageDeLameImporter
{
    public $traces = [
        'newPassage' => 0,
        'existingPassage' => 0,
        'changedPassage' => 0,
        'missing' => [],
        'noLevel' => [],
        'update' => [],
    ];
    public function from_csv($text_content)
    {
        $csv_content = CsvParser::parse_csv($text_content);
        foreach ($csv_content as $raw_passage) {
            $personne = Personne::where('nom', $raw_passage['Nom'])->where('prenom', $raw_passage['Prénom'])->first();
            if (!$personne) {
                $this->traces['missing'][] = $raw_passage['Nom'] . ' ' . $raw_passage['Prénom'];
                continue;
            }

            if (!$raw_passage['Niveau (sous-groupe)']) {
                $this->traces['noLevel'][] = $raw_passage['Nom'] . ' ' . $raw_passage['Prénom'];
                continue;
            }

            $this->create_passage_de_lame([
                'personne_id' => $personne->id,
                'niveau' => $raw_passage['Niveau (sous-groupe)'],
                'date' => $this->optional_format_date($raw_passage['Date passage']),
                'examinateur' => $raw_passage['Examinateur'],
                'etat' => $this->format_etat($raw_passage['Résultat']),
                'medaille' => $raw_passage['Médaille'],
                'medaille_remise' => false,
            ]);
        }
    }

    function format_date($date)
    {
        list($day, $month, $year) = explode('/', $date);
        return "$year-$month-$day";
    }

    function format_etat($resultat)
    {
        if ($resultat === 'Oui') {
            return 'Passé';
        }
        if ($resultat === 'Non') {
            return 'Échoué';
        }
        if ($resultat === 'Annulé') {
            return 'Annulé';
        }
        return 'Planifié';
    }

    function optional_format_date($date)
    {
        return $date ? $this->format_date($date) : null;
    }

    function format_date_time($date)
    {
        return DateTime::createFromFormat('d/m/Y H:i:s', $date)->format('Y-m-d H:i:s');
    }

    private function create_passage_de_lame($data)
    {
        if (!$data['personne_id']) {
            return null;
        }
        $passage = PassageDeLame::where('personne_id', $data['personne_id'])->first();
        if ($passage) {
            $this->merge_passage_de_lame($passage, $data);
            $passage->save();
            return $passage;
        }
        $this->traces['newPassage']++;
        return PassageDeLame::create($data);
    }

    private function merge_passage_de_lame($passage, $data)
    {
        $change = false;

        // Modifications appliquées
        foreach (['date', 'examinateur', 'niveau', 'etat', 'medaille', 'medaille_remise'] as $attribut) {
            if (!isset($passage[$attribut]) && isset($data[$attribut])) {
                $this->traces['update'][] = $attribut . " => " . print_r($data[$attribut], true);
                $passage[$attribut] = $data[$attribut];
                $change = true;
            } else if (isset($passage[$attribut]) && isset($data[$attribut]) && $passage[$attribut] !== $data[$attribut]) {
                $this->traces['update'][] = $attribut . " " . print_r($passage[$attribut], true) . " => " . print_r($data[$attribut], true);
                $passage[$attribut] = $data[$attribut];
                $change = true;
            }
        }

        if ($change) {
            $passage->save();
            $this->traces['changedPassage']++;
        } else {
            $this->traces['existingPassage']++;
        }
    }

}
