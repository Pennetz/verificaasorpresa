# Slim Framework 4 Skeleton Application

[![Coverage Status](https://coveralls.io/repos/github/slimphp/Slim-Skeleton/badge.svg?branch=master)](https://coveralls.io/github/slimphp/Slim-Skeleton?branch=master)

Use this skeleton application to quickly setup and start working on a new Slim Framework 4 application. This application uses the latest Slim 4 with Slim PSR-7 implementation and PHP-DI container implementation. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application. You will require PHP 7.4 or newer.

```bash
composer create-project slim/slim-skeleton [my-app-name]
```

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

To run the application in development, you can run these commands 

```bash
cd [my-app-name]
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:
```bash
cd [my-app-name]
docker-compose up -d
```
After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```

That's it! Now go build something cool.

## Dynamic API endpoint `/api/{n}`

This project now includes a dynamic endpoint:

- `GET /api` (HTML docs with endpoint list)
- `GET /api/{n}`

Where `n` is the index of the query to execute.

### Where to put your queries

Edit:

- `src/Application/Actions/Api/ApiQueryAction.php`

In method `resolveQueryByIndex()`, configure the map:

```php
$queries = [
	1 => 'YOUR SQL QUERY 1',
	2 => 'YOUR SQL QUERY 2',
];
```

### Database configuration

Database connection values are configured in:

- `app/settings.php`

Supported environment variables:

- `DB_HOST` (default: `127.0.0.1`)
- `DB_PORT` (default: `3306`)
- `DB_NAME` (default: `TestASorpresa`)
- `DB_USER` (default: `utente_phpmyadmin`)
- `DB_PASS` (default: `PasswordMoltoSicura`)
- `DB_CHARSET` (default: `utf8mb4`)

## Start, stop and restart the app server

From project root:

```bash
cd /workspaces/verificaasorpresa/[verificaasorpresa]
```

Start server:

```bash
composer start
```

Stop server:

- Press `Ctrl + C` in the terminal where server is running.

Restart server:

1. Press `Ctrl + C`
2. Run `composer start` again

If port `8080` is already in use:

```bash
lsof -i :8080
kill <PID>
composer start
```

## Quick request examples

```bash
curl http://localhost:8080/
curl http://localhost:8080/api
curl http://localhost:8080/api/1
curl http://localhost:8080/api/2
```
