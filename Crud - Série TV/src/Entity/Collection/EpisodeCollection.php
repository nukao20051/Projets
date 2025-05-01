<?php

namespace Entity\Collection;

use Database\MyPdo;
use Entity\Episode;

class EpisodeCollection
{
    /**
     * Retourne un tableau contenant tous les épisodes d'une saison d'une
     * série par ordre d'épisodes
     *
     * @return Episode[]
     */
    public static function findBySeasonId(int $seasonId): array
    {
        $req = MyPDO::getInstance()->prepare(
            <<<'SQL'
            SELECT id, seasonId, name, overview, episodeNumber
            FROM episode
            WHERE seasonId = ?
            ORDER BY episodeNumber
        SQL
        );
        $req->execute([$seasonId]);
        $tab = $req->fetchAll(\PDO::FETCH_CLASS, Episode::class);
        return $tab;
    }
}
