cp .env.example .env

composer install -n

php console assets:install --symlink --relative
php console cache:clear --no-warmup
