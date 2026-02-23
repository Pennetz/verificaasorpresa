# verificaasorpresa

Repository con applicazione Slim API nella cartella:

- `[verificaasorpresa]/`

## Avvio rapido

```bash
cd /workspaces/verificaasorpresa/[verificaasorpresa]
composer start
```

Server disponibile su:

- `http://localhost:8080`

## Stop e riavvio server

- Stop: `Ctrl + C` nel terminale dove gira il server
- Riavvio: `Ctrl + C` poi `composer start`

Se la porta 8080 è occupata:

```bash
lsof -i :8080
kill <PID>
composer start
```

## Route disponibili

- `OPTIONS /{routes:.*}`
- `GET /`
- `GET /api/{n}`
- `GET /users`
- `GET /users/{id}`

## Endpoint dinamico API

L'endpoint `GET /api/{n}` esegue la query associata all'indice `n`.

Per impostare le query, modifica:

- `[verificaasorpresa]/src/Application/Actions/Api/ApiQueryAction.php`

Nel metodo `resolveQueryByIndex()`:

```php
$queries = [
	1 => 'YOUR SQL QUERY 1',
	2 => 'YOUR SQL QUERY 2',
];
```

## Configurazione database

Config in:

- `[verificaasorpresa]/app/settings.php`

Default correnti:

- `DB_HOST`: `127.0.0.1`
- `DB_PORT`: `3306`
- `DB_NAME`: `TestASorpresa`
- `DB_USER`: `utente_phpmyadmin`
- `DB_PASS`: `PasswordMoltoSicura`
- `DB_CHARSET`: `utf8mb4`

## Test richieste

```bash
curl http://localhost:8080/
curl http://localhost:8080/api/1
curl http://localhost:8080/api/2
curl http://localhost:8080/users
```