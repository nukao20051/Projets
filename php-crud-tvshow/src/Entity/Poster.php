<?php

namespace Entity;

use Database\MyPdo;
use Entity\Exception\EntityNotFoundException;

class Poster
{
    private ?int $id;
    private string $jpeg;

    public static function findById(?int $id): Poster
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            SELECT id, jpeg
            FROM poster
            WHERE id = ?
        SQL
        );
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Poster::class);
        //fetch() ne prend pas de paramètres contrairement à fetchAll() donc on utilise setFetchMode()
        $ligne = $stmt->fetch();
        if ($ligne === false) {
            throw new EntityNotFoundException("Aucun poster ne correspond à l'id");
        }
        return $ligne;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getJpeg(): string
    {
        return $this->jpeg;
    }

}
