<?php

declare(strict_types=1);

namespace Entity;

use Database\MyPdo;
use Entity\Collection\EpisodeCollection;
use Entity\Exception\EntityNotFoundException;

class Season
{
    private int $id;
    private int $tvShowId;
    private string $name;
    private int $seasonNumber;
    private ?int $posterId;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTvShowId(): int
    {
        return $this->tvShowId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSeasonNumber(): int
    {
        return $this->seasonNumber;
    }

    public function getPosterId(): ?int
    {
        return $this->posterId;
    }

    /**
     * Retourne la saison ayant comme identifiant celui donné en paramètre
     *
     * @param int $id
     * @return Season
     */
    public static function findById(int $id): Season
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
            SELECT id, tvShowId, name, seasonNumber, posterId
            FROM season
            WHERE id = ?
        SQL
        );
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, Season::class);
        $season = $stmt->fetch();
        if ($season === false) {
            throw new EntityNotFoundException();
        }
        return $season;
    }

    public function getEpisode(): array
    {
        return EpisodeCollection::findBySeasonId($this->getId());
    }
}
