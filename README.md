# Slim

## Documentation

**Instructions:**

1. Create a .env file: ```cp .env.example .env```
2. Run the containers: ```docker compose up -d```
3. Install dependencies: ```docker exec php composer install```
4. Run tests ```docker exec php vendor/bin/phpunit```
5. Visit https://localhost and accept the self-signed certificate

**Environment variables:**

- APP_ENV: the app environment (e.g "development", "production")
- DOMAIN: the domain on which the app is being served (e.g "https://localhost")
- DB_DRIVER: PDO driver to be used by Doctrine (e.g "pdo_sqlite")
- DB_NAME: database name (filename in case of Sqlite)
- DB_USER: database user (should be blank if using Sqlite)
- DB_PASSWORD: database password (should be blank if using Sqlite)