<?php

namespace App\Tools;

use App\Models\Document;
use App\Models\Personne;
use App\Models\Saison;
use DateTime;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleServiceDrive;

class AdherentDriveScanner
{
    public $traces = [
        'personne_not_found' => [],
        'existing_document' => 0,
        'new_document' => 0,
        'no_match' => [],
        'loops' => 0,
        'pages' => [],
    ];
    public function from_drive()
    {
        $driveId = "0ABdZPtRW2HpGUk9PVA";
        $folderId = "1LIrj3Z5cWdZDo30_xlLexRXoPlTRfzAT";
        $token = session('google_token');
        if (!$token) {
            throw new \Exception("Token Google manquant");
        }

        $client = new GoogleClient();
        $client->setAccessToken($token);

        $driveService = new GoogleServiceDrive($client);
        $params = [
            'q' => "'{$folderId}' in parents",
            'driveId' => $driveId,
            'corpora' => 'drive',
            'includeItemsFromAllDrives' => true,
            'fields' => 'files(name, webViewLink, modifiedTime)',
            'supportsAllDrives' => true,
            'pageSize' => 1000,
        ];

        $files = [];
        do {
            $results = $driveService->files->listFiles($params);
            $this->traces['loops']++;
            $this->traces['pages'][] = count($results->getFiles());

            foreach ($results->getFiles() as $file) {
                $this->handle_file($file->getName(), $file->getWebViewLink(), new DateTime($file->getModifiedTime()));
            }

            // Vérifiez s'il y a plus de pages de résultats
            if ($results->getNextPageToken()) {
                $params['pageToken'] = $results->getNextPageToken();
            } else {
                break;  // Sortez de la boucle si nous avons tous les fichiers
            }
        } while (true);
        return $files;
    }

    private function handle_file($name, $url, $date)
    {
        if (preg_match('/^2023 - ([-A-ZÉÇÈ\'’ ]+) ([A-ZÉ][-a-zéïèë ]+([- ][A-ZÉ][-a-zéïèë ]+)*) - (Devis|Attestation Inscription|Licence|CNI|CAF|QM|Autorisations et Règlement|Autorisations|Assurance|CM|Attestation Sport|Règlement|QM et Règlement|QM et Autorisations|QM, Autorisations et Licence|Formulaire|Attestation - Recu|QM, Autorisations et Règlement|QM, CM et Règlement|QM, CM et Autorisations|Pass\'Sport|Carte Région|RIB|Remboursement|Permanence|Justificatif de départ|Pass\'Région|Règlement polaire|Formulaire et Autorisations|Formulaire et QM|Aide|Formulaire, QM et Autorisations)( - ([^\.]+))?\.(pdf|jpg|jpeg|JPG|png|PNG|bmp|docx)$/u', $name, $matches)) {
            $nom = $matches[1];
            $prenom = $matches[2];
            $type = $matches[4];
            $extra = $matches[6];
            if (!$nom || !$prenom) {
                return null;
            }
            $saison = Saison::where(['nom' => '2023 - 2024'])->first();
            $personne = Personne::where('nom', $nom)->where('prenom', $prenom)->first();
            if (!$personne) {
                $this->traces['personne_not_found'][] = $nom . ' ' . $prenom;
                return null;
            }

            $data = [
                'personne_id' => $personne->id,
                'saison_id' => $saison->id,
                'type' => $type,
                'extra' => $extra,
                'url' => $url,
                'date' => $date->format('Y-m-d H:i:s'),
            ];

            $document = Document::where('saison_id', $data['saison_id'])->where('personne_id', $data['personne_id'])->where('type', $data['type'])->where('extra', $data['extra'])->first();

            if ($document) {
                $this->traces['existing_document']++;
                return $document;
            }

            $this->traces['new_document']++;
            return Document::create($data);
        } else {
            $this->traces['no_match'][] = $name;
        }
    }
}
