# Slim

## Benchmarks (FrankenPHP @ Worker mode)

- Static JSON endpoint

```bash
$ wrk -t12 -c100 -d30s https://localhost/healthcheck
Running 30s test @ https://localhost/healthcheck
  12 threads and 100 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency     9.10ms   12.01ms 152.82ms   89.12%
    Req/Sec     1.34k   194.29     3.57k    73.66%
  479157 requests in 30.07s, 220.25MB read
Requests/sec:  15933.59
Transfer/sec:      7.32MB
```

## Documentation

**Instructions:**

1. Create a .env file: `cp .env.example .env`
2. Run the containers: `docker compose up -d`
3. Install dependencies: `docker compose exec php composer install`
4. Run tests `docker compose exec php vendor/bin/phpunit`
5. Visit https://localhost and accept the self-signed certificate

**Environment variables:**

- APP_ENV: the app environment (e.g "development", "production")
- DOMAIN: the domain on which the app is being served (e.g "https://localhost")
- DB_DRIVER: PDO driver to be used by Doctrine (e.g "pdo_sqlite")
- DB_NAME: database name (filename in case of Sqlite)
- DB_USER: database user (should be blank if using Sqlite)
- DB_PASSWORD: database password (should be blank if using Sqlite)
