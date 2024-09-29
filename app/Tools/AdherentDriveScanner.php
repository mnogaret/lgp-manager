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

    public function from_drives()
    {
        // TODO il faudra retirer ça un jour...
        Document::where('type', 'Photo')->delete();

        $files = [];

        // 2023
        // $files = array_merge($files, $this->from_drive("1LIrj3Z5cWdZDo30_xlLexRXoPlTRfzAT"));

        // 2024
        $files = array_merge($files, $this->from_drive("1Fwh4Hlt4gHYmcz0bHvhSZ4KUS6WSl1Op")); // A
        $files = array_merge($files, $this->from_drive("1Il_-cd2QuIREQTy0fpnOrbiwR7wfwP-M")); // B
        $files = array_merge($files, $this->from_drive("1YKtIuVDdv2XzGdMiobk4Hcjxn2G6VwTN")); // C
        $files = array_merge($files, $this->from_drive("1bvywvVMhXRN1F1t637NpAlIx6ulsPkf4")); // DE
        $files = array_merge($files, $this->from_drive("1gIVuHQnTh406graBk_epXkVwLXyLTknH")); // FGH
        $files = array_merge($files, $this->from_drive("1GCxY5xxrptqXrHXmhQFwJMRhF4j9giQ3")); // IJKL
        $files = array_merge($files, $this->from_drive("14Yfom6gnhzOng4M23HVPaiPxpcQgfvTD")); // M
        $files = array_merge($files, $this->from_drive("1VR72-9Bel7Tw8nyJ_8c680Ls5zb59AmO")); // NOPQ
        $files = array_merge($files, $this->from_drive("1q-Ig85tyzMEC1FuN7Q7_BBqsJKgrvfb4")); // RS
        $files = array_merge($files, $this->from_drive("1KLCYEvPnToW1RHrw82ASfI-yKsmzb1xS")); // TUVWXYZ

        return $files;
    }

    public function from_drive($folderId)
    {
        $driveId = "0ABdZPtRW2HpGUk9PVA";
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
            'fields' => 'nextPageToken, files(name, webViewLink, modifiedTime)',
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
        if (preg_match('/^2024 - ([-A-ZÉÇÈ\'’ ]+) ([A-ZÉ][-a-zéïèë ]+([- ][A-ZÉ][-a-zéïèë ]+)*) - (Devis|Attestation Inscription|Licence|CNI|CAF|QM|Autorisations et Règlement|Autorisations|Assurance|CM|Attestation Sport|Règlement|QM et Règlement|QM et Autorisations|QM, Autorisations et Licence|Formulaire|Attestation - Recu|QM, Autorisations et Règlement|QM, CM et Règlement|QM, CM et Autorisations|Pass\'Sport|Carte Région|RIB|Remboursement|Permanence|Justificatif de départ|Pass\'Région|Règlement polaire|Formulaire et Autorisations|Formulaire et QM|Aide|Formulaire, QM et Autorisations|Photo)( - ([^\.]+))?\.(pdf|jpg|jpeg|JPG|png|PNG|bmp|docx|HEIC|heic)$/u', $name, $matches)) {
            $nom = $matches[1];
            $prenom = $matches[2];
            $type = $matches[4];
            $extra = $matches[6];
            if (!$nom || !$prenom) {
                return null;
            }
            $saison = Saison::where(['nom' => '2024 - 2025'])->first();
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
