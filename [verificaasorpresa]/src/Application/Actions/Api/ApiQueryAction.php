<?php

declare(strict_types=1);

namespace App\Application\Actions\Api;

use App\Application\Actions\Action;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class ApiQueryAction extends Action
{
    private PDO $pdo;

    public function __construct(LoggerInterface $logger, PDO $pdo)
    {
        parent::__construct($logger);
        $this->pdo = $pdo;
    }

    protected function action(): \Psr\Http\Message\ResponseInterface
    {
        $rawIndex = (string) $this->resolveArg('n');
        if (!ctype_digit($rawIndex)) {
            throw new HttpBadRequestException($this->request, 'Il parametro n deve essere numerico.');
        }

        $index = (int) $rawIndex;
        $query = $this->resolveQueryByIndex($index);
        if ($query === null) {
            return $this->respondWithData([
                'message' => 'Nessuna query configurata per questo indice.',
                'requestIndex' => $index,
            ], 404);
        }

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute();

            if ($this->isReadQuery($query)) {
                $rows = $statement->fetchAll();

                return $this->respondWithData([
                    'requestIndex' => $index,
                    'rows' => $rows,
                ]);
            }

            return $this->respondWithData([
                'requestIndex' => $index,
                'affectedRows' => $statement->rowCount(),
            ]);
        } catch (PDOException $exception) {
            $this->logger->error('Errore esecuzione query API', [
                'requestIndex' => $index,
                'message' => $exception->getMessage(),
            ]);

            return $this->respondWithData([
                'message' => 'Errore durante l\'esecuzione della query.',
                'requestIndex' => $index,
            ], 500);
        }
    }

    private function resolveQueryByIndex(int $index): ?string
    {
        $queries = [
            0 => 'SHOW TABLES',
            1 => 'SELECT DISTINCT p.Pnome FROM Pezzi p JOIN Catalogo c ON c.Pid = p.Pid',
            2 => 'SELECT f.Fnome FROM Fornitori f WHERE NOT EXISTS (SELECT * FROM Pezzi p WHERE NOT EXISTS (SELECT * FROM Catalogo c WHERE c.Fid = f.Fid AND c.Pid = p.Pid))',
            3 => 'SELECT f.Fnome FROM Fornitori f WHERE NOT EXISTS (SELECT * FROM Pezzi p WHERE p.colore = \'rosso\' AND NOT EXISTS (SELECT * FROM Catalogo c WHERE c.Fid = f.Fid AND c.Pid = p.Pid))',
            4 => 'SELECT DISTINCT p.Pid, p.Pnome FROM Pezzi p JOIN Catalogo c ON c.Pid = p.Pid JOIN Fornitori f ON f.Fid = c.Fid WHERE f.Fnome = \'Acme\' AND NOT EXISTS (SELECT * FROM Catalogo c2 WHERE c2.Pid = p.Pid AND c2.Fid <> c.Fid)',
            5 => 'SELECT DISTINCT c.Fid FROM Catalogo c WHERE c.costo > (SELECT AVG(c2.costo) FROM Catalogo c2 WHERE c2.Pid = c.Pid)',
            6 => 'SELECT p.Pnome, f.Fnome FROM Catalogo c JOIN Pezzi p ON p.Pid = c.Pid JOIN Fornitori f ON f.Fid = c.Fid WHERE c.costo = (SELECT MAX(c2.costo) FROM Catalogo c2 WHERE c2.Pid = c.Pid) ORDER BY p.Pid, f.Fnome',
            7 => 'SELECT c.Fid FROM Catalogo c JOIN Pezzi p ON p.Pid = c.Pid GROUP BY c.Fid HAVING SUM(CASE WHEN p.colore <> \'rosso\' THEN 1 ELSE 0 END) = 0',
            8 => 'SELECT c.Fid FROM Catalogo c JOIN Pezzi p ON p.Pid = c.Pid GROUP BY c.Fid HAVING SUM(p.colore = \'rosso\') > 0 AND SUM(p.colore = \'verde\') > 0',
            9 => 'SELECT DISTINCT c.Fid FROM Catalogo c JOIN Pezzi p ON p.Pid = c.Pid WHERE p.colore IN (\'rosso\', \'verde\')',
            10 => 'SELECT c.Pid FROM Catalogo c GROUP BY c.Pid HAVING COUNT(DISTINCT c.Fid) >= 2',
        ];

        return $queries[$index] ?? null;
    }

    private function isReadQuery(string $query): bool
    {
        return (bool) preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $query);
    }
}
