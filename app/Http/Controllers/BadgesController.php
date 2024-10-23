<?php

namespace App\Http\Controllers;

use App\Models\Personne;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleServiceDrive;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as ImageDriver;

class BadgesController extends Controller
{
    public function pdf()
    {
        $saisonId = session('saison_id');
        if (!$saisonId) {
            return redirect()->route('welcome')->withErrors('Aucune saison sélectionnée');
        }

        $groupes = [
            '2024-baby',
            '2024-initiation1',
            '2024-initiation2',
            '2024-ados',
        ];

        $adherents = Personne::whereHas('adhesions', function($query) use ($groupes, $saisonId) {
            $query->whereHas('groupe', function($query) use ($groupes, $saisonId) {
                // Filtrer selon les groupes et la saison active
                $query->whereIn('code', $groupes)
                      ->where('saison_id', $saisonId); // Filtrer par la saison active
            })
            ->where('etat', 'validé'); // Filtrer par l'état de l'adhésion (ici 'validée')
        })->with(['adhesions' => function($query) use ($saisonId) {
            // Ne charger que les adhésions de la saison active
            $query->whereHas('groupe', function($query) use ($saisonId) {
                $query->where('saison_id', $saisonId);
            });
        }])->get();

        // TODO ajouter la photo

        return Pdf::loadView('badges/pdf', ['adherents' => $adherents])->download(date('Ymd') . '-badges.pdf');
    }

    public function csv()
    {
        $saisonId = session('saison_id');
        if (!$saisonId) {
            return redirect()->route('welcome')->withErrors('Aucune saison sélectionnée');
        }

        $groupes = [
            '2024-baby',
            '2024-initiation1',
            '2024-initiation2',
            '2024-ados',
        ];

        $adherents = Personne::whereHas('adhesions', function($query) use ($groupes, $saisonId) {
            $query->whereHas('groupe', function($query) use ($groupes, $saisonId) {
                // Filtrer selon les groupes et la saison active
                $query->whereIn('code', $groupes)
                      ->where('saison_id', $saisonId); // Filtrer par la saison active
            })
            ->where('etat', 'validé'); // Filtrer par l'état de l'adhésion (ici 'validée')
        })->with(['adhesions' => function($query) use ($saisonId) {
            // Ne charger que les adhésions de la saison active
            $query->whereHas('groupe', function($query) use ($saisonId) {
                $query->where('saison_id', $saisonId);
            });
        }])->get();

        // Nom du fichier CSV
        $fileName = date('Ymd') . '-adherents.csv';

        // Entêtes pour le téléchargement du fichier CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        // Callback pour générer le fichier CSV en mémoire
        $callback = function() use ($adherents) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF"); // Ajoute le BOM UTF-8 (facultatif)

            // Première ligne avec les noms des colonnes
            fputcsv($file, ['Email', 'Nom', 'Prénom', 'Date de naissance', 'Sexe', 'Licence', 'Section', 'Groupe', 'Niveau', 'Photo', 'QR code'], ';');

            // Parcours de la collection et ajout des lignes CSV
            foreach ($adherents as $adherent) {
                foreach ($adherent->adhesions as $adhesion) {
                    fputcsv($file, [
                        $adherent->email1,
                        $adherent->nom,
                        $adherent->prenom,
                        $adherent->date_naissance,
                        $adherent->sexe,
                        $adherent->numero_licence,
                        'Loisir',
                        $adhesion->groupe->nom,
                        $adherent->niveau ?? 'Débutant',
                        '.\\\\photos\\\\' . $this->getPhotoName($adherent),
                        'https://manager.lyonglacepatinage.fr/fiche/' . $adherent->hash_code,
                    ], ';');
                }
            }

            fclose($file);
        };

        // Retourne le fichier CSV en téléchargement
        return response()->stream($callback, 200, $headers);
    }

    public function adultes_csv()
    {
        $saisonId = session('saison_id');
        if (!$saisonId) {
            return redirect()->route('welcome')->withErrors('Aucune saison sélectionnée');
        }

        $groupe_types = [
            'Adulte débutant',
            'Adulte intermédiaire',
            'Adulte danseur',
            'Adulte sauteur',
        ];

        $adherents = Personne::whereHas('adhesions', function($query) use ($groupe_types, $saisonId) {
            $query->whereHas('groupe', function($query) use ($groupe_types, $saisonId) {
                // Filtrer selon les groupes et la saison active
                $query->whereIn('type', $groupe_types)
                      ->where('saison_id', $saisonId); // Filtrer par la saison active
            })
            ->where('etat', 'validé'); // Filtrer par l'état de l'adhésion (ici 'validée')
        })->with(['adhesions' => function($query) use ($saisonId) {
            // Ne charger que les adhésions de la saison active
            $query->whereHas('groupe', function($query) use ($saisonId) {
                $query->where('saison_id', $saisonId);
            });
        }])->get();

        // Nom du fichier CSV
        $fileName = date('Ymd') . '-adherents-adultes.csv';

        // Entêtes pour le téléchargement du fichier CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        // Callback pour générer le fichier CSV en mémoire
        $callback = function() use ($adherents) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF"); // Ajoute le BOM UTF-8 (facultatif)

            // Première ligne avec les noms des colonnes
            fputcsv($file, ['Email', 'Nom', 'Prénom', 'Date de naissance', 'Sexe', 'Licence', 'Section', 'Groupe', 'Niveau', 'Photo', 'QR code'], ';');

            // Parcours de la collection et ajout des lignes CSV
            foreach ($adherents as $adherent) {
                $creneaux = [];
                $niveaux = [];
                foreach ($adherent->adhesions as $adhesion) {
                    $this->add($niveaux, $adhesion->groupe->type);
                    $this->add($creneaux, $adhesion->groupe->creneaux[0]->jour);
                }

                fputcsv($file, [
                    $adherent->email1,
                    $adherent->nom,
                    $adherent->prenom,
                    $adherent->date_naissance,
                    $adherent->sexe,
                    $adherent->numero_licence,
                    'Loisir',
                    $this->toCreneau($creneaux),
                    $this->toNiveau($niveaux),
                    '.\\\\photos\\\\' . $this->getPhotoName($adherent),
                    'https://manager.lyonglacepatinage.fr/fiche/' . $adherent->hash_code,
                ], ';');
            }

            fclose($file);
        };

        // Retourne le fichier CSV en téléchargement
        return response()->stream($callback, 200, $headers);
    }

    function add(array &$array, $value): void {
        // Vérifier si la valeur n'existe pas déjà dans le tableau
        if (!in_array($value, $array, true)) {
            // Ajouter la valeur si elle n'est pas présente
            $array[] = $value;
        }
    }

    function toNiveau($niveaux) {
        if (count($niveaux) === 1) {
            // Si un seul niveau, on affecte directement cet élément
            return $niveaux[0];
        }
        // Dictionnaire pour abréger les niveaux
        $abreviations = [
            'Adulte débutant'    => 'débutant',
            'Adulte intermédiaire' => 'interm.',
            'Adulte danseur'     => 'danseur',
            'Adulte sauteur'     => 'sauteur'
        ];
    
        // Priorités pour trier les niveaux
        $priorite = [
            'Adulte sauteur'       => 1,
            'Adulte danseur'       => 2,
            'Adulte intermédiaire' => 3,
            'Adulte débutant'      => 4,
            'PPG'                  => 5
        ];
    
        // Trier les niveaux selon leur priorité
        usort($niveaux, function($a, $b) use ($priorite) {
            return $priorite[$a] <=> $priorite[$b];
        });
    
        // On mappe les valeurs du tableau avec leurs abréviations
        $abr_values = array_map(function($niveau) use ($abreviations) {
            return $abreviations[$niveau] ?? $niveau; // Récupérer l'abréviation ou le niveau original si non trouvé
        }, $niveaux);
    
        // On joint les valeurs abrégées avec une virgule et on préfixe par "Adulte"
        return 'Adulte ' . implode(', ', $abr_values);
    }

    function toCreneau($creneaux) {
        // Priorités pour trier les créneaux
        $priorite = [
            'Mardi'    => 1,
            'Mercredi' => 2,
            'Samedi'   => 3,
        ];
    
        // Trier les créneaux selon leur priorité
        usort($creneaux, function($a, $b) use ($priorite) {
            return $priorite[$a] <=> $priorite[$b];
        });
    
        // On joint les valeurs
        return implode(', ', $creneaux);
    }

    public function zip($offset = 0)
    {
        $saisonId = session('saison_id');
        if (!$saisonId) {
            return redirect()->route('welcome')->withErrors('Aucune saison sélectionnée');
        }

        $groupes = [
            '2024-baby',
            '2024-initiation1',
            '2024-initiation2',
            '2024-ados',
        ];

        $adherents = Personne::whereHas('adhesions', function($query) use ($groupes, $saisonId) {
            $query->whereHas('groupe', function($query) use ($groupes, $saisonId) {
                $query->whereIn('code', $groupes)
                    ->where('saison_id', $saisonId);
            })
            ->where('etat', 'validé');
        })
        ->with('documents')
        ->orderBy('nom')->orderBy('prenom')
        ->limit(50)
        ->offset($offset)
        ->get();

        // Création du nom du fichier ZIP
        $zipFileName = date('Ymd') . '-photos.zip';
        $zipFilePath = public_path($zipFileName); // Chemin complet du fichier ZIP
        $zip = new \ZipArchive();

        // Ouvrir le fichier ZIP pour écriture
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($adherents as $adherent) {
                $photosCount = $adherent->documents->where('type', 'Photo')->count();
                // Loguer les adhérents avec 0 ou plus d'une photo
                if ($photosCount === 0) {
                    Log::warning("Adhérent sans photo : " . $adherent->nom . " " . $adherent->prenom);
                } elseif ($photosCount > 1) {
                    Log::warning("Adhérent avec plusieurs photos : " . $adherent->nom . " " . $adherent->prenom);
                }
                Log::info("Adhérent " . $adherent->nom . " " . $adherent->prenom);

                foreach ($adherent->documents as $document) {
                    if ($document->type === 'Photo') {
                        // Nom de la photo pour le ZIP
                        $photoId = $this->extractFileId($document->url);
                        $photoName = $this->getPhotoName($adherent);

                        // Télécharger la photo depuis l'URL
                        try {
                            $photoContent = $this->crop($this->downloadFile($photoId));
                        } catch (Exception $e) {
                            $message = "Erreur lors du traitement de la photo de $adherent->prenom $adherent->nom ; Détails: " . $e->getMessage();
                            throw new Exception($message, $e->getCode(), $e);
                        }

                        // Ajouter la photo au fichier ZIP
                        if ($photoContent) {
                            $zip->addFile($photoContent, $photoName);
                            // $zip->addFromString($photoName, $photoContent);
                        } else {
                            throw new Exception("Erreur lors du traitement de la photo de $adherent->prenom $adherent->nom ; Aucun contenu");
                        }
                    }
                }
            }

            // Fermer le fichier ZIP
            $zip->close();
            if (!file_exists($zipFilePath)) {
                return response()->json(['error' => 'Le fichier ZIP n\'a pas été créé. Adhérents : ' . $adherents], 500);
            }
        } else {
            return response()->json(['error' => 'Impossible d\'ouvrir le fichier ZIP.'], 500);
        }

        // Retourne le fichier ZIP pour le téléchargement
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    function extractFileId($url) {
        preg_match('/\/d\/(.*?)(\/|$)/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }

    function getPhotoName($adherent) {
        return '2024 - ' . $adherent->nom . ' ' . $adherent->prenom . ' - Photo.jpg';
    }

    function downloadFile($fileId) {
        // Crée une instance de Google Client
        $client = new GoogleClient();
        $token = session('google_token');
        $client->setAccessToken($token);
        $client->addScope(GoogleServiceDrive::DRIVE_READONLY);
    
        // Authentifie
        $service = new GoogleServiceDrive($client);
        $response = $service->files->get($fileId, ['alt' => 'media']);
    
        // Vérifier si la réponse est valide
        if ($response->getStatusCode() === 200) {
            // Créer un fichier temporaire
            $tempFilePath = tempnam(sys_get_temp_dir(), 'download_');

            // Ouvrir un flux pour écrire
            $stream = fopen($tempFilePath, 'w');
            
            // Vérifier si le fichier est ouvert avec succès
            if ($stream) {
                // Obtenir le corps de la réponse comme un flux
                $bodyStream = $response->getBody()->detach(); // Détacher le flux

                // Écrire le contenu du corps dans le fichier temporaire
                stream_copy_to_stream($bodyStream, $stream);
                
                fclose($stream);
                return $tempFilePath; // Retourne le chemin vers le fichier temporaire
            } else {
                throw new Exception("Erreur lors de l'ouverture du fichier temporaire pour l'écriture.");
            }
        } else {
            throw new Exception("Erreur lors du téléchargement du fichier. Code de statut: " . $response->getStatusCode());
        }
    }

    function crop($tmpFile, $targetWidth = 350, $targetHeight = 450) {
        // Forcer l'utilisation du driver GD
        $manager = new ImageManager(new ImageDriver());
    
        // Charger l'image depuis le fichier temporaire
        $img = $manager->read($tmpFile);
        
        // Dimensions originales de l'image
        $originalWidth = $img->width();
        $originalHeight = $img->height();
    
        // Ratio cible (35/45)
        $targetRatio = $targetWidth / $targetHeight;
    
        // Ratio actuel de l'image
        $originalRatio = $originalWidth / $originalHeight;
    
        // Calcul du redimensionnement
        if ($originalRatio > $targetRatio) {
            // L'image est plus large que le ratio cible, on découpe à gauche et à droite
            $newWidth = intval($originalHeight * $targetRatio);
            $newHeight = $originalHeight;
            $offsetX = intval(($originalWidth - $newWidth) / 2);
            $offsetY = 0;
        } else {
            // L'image est plus haute que le ratio cible, on découpe en haut et en bas
            $newWidth = $originalWidth;
            $newHeight = intval($originalWidth / $targetRatio);
            $offsetX = 0;
            $offsetY = intval(($originalHeight - $newHeight) / 2);
        }
    
        // Recadrer l'image pour obtenir le bon ratio
        $img->crop($newWidth, $newHeight, $offsetX, $offsetY);
    
        // Sauvegarder le résultat dans un autre fichier temporaire
        $outputFile = tempnam(sys_get_temp_dir(), 'output_');
        $img->toJpeg()->save($outputFile);
    
        // Supprimer les fichiers temporaires
        unlink($tmpFile);

        return $outputFile;
    }
}
