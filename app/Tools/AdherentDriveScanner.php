<?php

namespace App\Tools;

use Laravel\Socialite\Facades\Socialite;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleServiceDrive;

class AdherentDriveScanner
{
    public $traces = [];
    public function from_drive($folderId)
    {
        $user = Socialite::driver('google')->user();
        $token = $user->token;

        $client = new GoogleClient();
        $client->setAccessToken($token);;

        $driveService = new GoogleServiceDrive($client);
        $results = $driveService->files->listFiles([
            'q' => "'{$folderId}' in parents"
        ]);

        foreach ($results->getFiles() as $file) {
            echo $file->getName(), PHP_EOL;
        }
    }
}
