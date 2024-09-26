# Déploiement local

composer install --optimize-autoloader --no-dev

# Démarrage local

Lancer Docker Desktop

docker-compose up -d

# Rebuild image docker

docker-compose up -d --build

# Commandes artisan

php artisan make:migration create_ma_table_table
php artisan make:model MaTable

docker-compose exec web php artisan migrate:reset
docker-compose exec web php artisan migrate
docker-compose exec web php artisan db:seed --class=SaisonSeeder

# OVH

Vérifier la version de PHP
cat ~/.ovhconfig

Exemple de commande
/usr/local/php8.2/bin/php artisan db:seed --class=Saison2024Seeder
