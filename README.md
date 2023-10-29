# Description

Petite appli pour gérer mon asso.

# Installation d’un poste de développemnent

## Prérequis

- Git
- Docker Desktop
- PHP
- composer

## Processus

### Déploiement local

composer install

Copier le .env.example => .env
Dans .env changer la valeur de GOOGLE_AUTH_ENABLED pour false
GOOGLE_AUTH_ENABLED=false

### Démarrage local

Lancer Docker Desktop
docker-compose up -d
docker-compose exec web php artisan key:generate

# Commandes artisan

docker-compose exec web php artisan migrate
docker-compose exec web php artisan db:seed --class=SaisonSeeder

# DONE

- Nettoyer le bazar que m'a mis WindMill
- Paufiner le layout
- Faire un dashboard minimal avec rien dedans
- Page d'import de personnes
- Notion de foyer

# TODO

- Page d'affichage de personnes
- Notion de paiement
- Notion de pièces de dossier
- Notion de pièces manquantes
- Commentaires
- Page de recherche de personnes
