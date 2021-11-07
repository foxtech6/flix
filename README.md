# Reserve & Cancel spots on a trip

## How Install

- Setup Docker or server with **PHP >8.0**, nginx/apache, mysql/mariadb/postgresql
- Run composer
```shell
$~ composer install
```
- Copy .env.example to .env and setup project env variables **(DB credentials, app url, key, etc.)**

- Run migrations:
```shell 
$~ php artisan migrate
```
- Run seeders
```shell 
$~ php artisan db:seed
```

### How run test
```shell
$~ php $(pwd)/vendor/phpunit/phpunit/phpunit --configuration $(pwd)/phpunit.xml
```
