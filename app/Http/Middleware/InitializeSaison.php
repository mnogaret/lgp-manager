<?php

namespace App\Http\Middleware;

use App\Models\Saison;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InitializeSaison
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si la saison est déjà stockée en session
        if (!$request->session()->has('saison_id')) {
            Log::info('Middleware exécuté : session vide, initialisation de la saison par défaut');
            // Chercher la saison par défaut (par exemple, 2024-2025)
            $defaultSaison = Saison::where('nom', '2024 - 2025')->first();

            // Si la saison par défaut existe, la stocker en session
            if ($defaultSaison) {
                Log::info('Middleware exécuté : set saison => ' . $defaultSaison->id);
                $request->session()->put('saison_id', $defaultSaison->id);
            }
        } else {
            Log::info('Middleware exécuté : session contient déjà une saison');
        }

        return $next($request);
    }
}
