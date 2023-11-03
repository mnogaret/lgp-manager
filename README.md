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

- ☑ Page de recherche de personnes
- ☑ Modèle : Règlement
  - ☑ Facture
  - ☑ Total à payer
  - ☑ Règlement (virement, chèque, CV, Pass'Sport, Pass'Région, espèce)
- ☑ Modèle : Gestion des pièces
  - ☑ CM => personne->date_certificat_medical
  - ☑ Essai => adhesion->essai
  - ☑ Licence => table licence ?
  - ☑ Niveau => personne->niveau
- ☑ Import et affichage
  - ☑ Date du CM
  - ☑ Droit à l’image
  - ☑ Adhésions dans la fiche de l’adhérent
  - ☑ Commentaires (permanence, mathieu, facturation, infos)
- ☐ Grille Excel