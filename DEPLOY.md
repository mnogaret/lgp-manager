# Déploiement local

composer install --optimize-autoloader --no-dev

# Démarrage local

Lancer Docker Desktop

docker-compose up -d

# Commandes artisan

docker-compose exec web php artisan migrate:reset
docker-compose exec web php artisan migrate
docker-compose exec web php artisan db:seed --class=SaisonSeeder
