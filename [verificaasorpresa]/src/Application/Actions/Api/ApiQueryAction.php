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
            1 => 'SELECT * from Pezzi',
            2 => 'SHOW TABLES',
        ];

        return $queries[$index] ?? null;
    }

    private function isReadQuery(string $query): bool
    {
        return (bool) preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $query);
    }
}
