<?php

namespace Entity\Collection;

use Database\MyPdo;
use Entity\Season;

class SeasonCollection
{
    /**
     * Retourne un tableau contenant toutes les saisons d'un série par
     * ordre de lecture dans la classe Season
     *
     * @return Season[]
     */
    public static function findByTvId(int $tvShowId): array
    {
        $req = MyPDO::getInstance()->prepare(
            <<<'SQL'
            SELECT id, tvShowId, name, seasonNumber, posterId
            FROM season
            WHERE tvShowId = ?
            ORDER BY seasonNumber
        SQL
        );
        $req->execute([$tvShowId]);
        $tab = $req->fetchAll(\PDO::FETCH_CLASS, Season::class);
        return $tab;
    }
}
