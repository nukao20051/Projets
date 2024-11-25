<?php

declare(strict_types=1);

namespace Entity\Collection;

use Database\MyPdo;
use Entity\Genre;
use PDO;

class GenreCollection
{
    public static function findAll(): array
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
            SELECT id, name
            FROM genre
            ORDER BY id
        SQL
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Genre::class);
    }
}
