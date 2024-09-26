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

http://localhost:8000

# Commandes artisan

docker-compose exec web php artisan migrate
docker-compose exec web php artisan db:seed --class=SaisonSeeder
