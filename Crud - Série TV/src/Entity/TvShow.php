<?php

declare(strict_types=1);

namespace Entity;

use Database\MyPdo;
use Entity\Collection\SeasonCollection;
use Entity\Collection\TvShowCollection;
use Entity\Exception\EntityNotFoundException;
use PDO;

class TvShow
{
    private ?int $id;
    private string $name;
    private string $originalName;
    private string $homepage;
    private string $overview;
    private ?int $posterId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getHomepage(): string
    {
        return $this->homepage;
    }

    public function getOverview(): string
    {
        return $this->overview;
    }

    public function getPosterId(): ?int
    {
        return $this->posterId;
    }

    private function setId(?int $id): TvShow
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): TvShow
    {
        $this->name = $name;
        return $this;
    }

    public function setOriginalName(string $originalName): TvShow
    {
        $this->originalName = $originalName;
        return $this;
    }

    public function setHomepage(string $homepage): TvShow
    {
        $this->homepage = $homepage;
        return $this;
    }

    public function setOverview(string $overview): TvShow
    {
        $this->overview = $overview;
        return $this;
    }

    /**
     * Retourne la série ayant comme identifiant celui donné en paramètre
     *
     * @param int $id
     * @return TvShow
     */
    public static function findById(int $id): TvShow
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
            SELECT id, name, originalName, homepage, overview, posterId
            FROM tvshow
            WHERE id = ?
        SQL
        );
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, TvShow::class);
        $tvshow = $stmt->fetch();
        if ($tvshow === false) {
            throw new EntityNotFoundException();
        }
        return $tvshow;
    }


    /**
     * @param int $genreId
     * @return TvShow[]
     */
    public static function findByGenreId(int $genreId): array
    {
        $stmt = MyPdo::getInstance()->prepare(
            <<<'SQL'
            SELECT tvshow.id, tvshow.name, tvshow.originalName, tvshow.homepage, tvshow.overview, tvshow.posterId
            FROM tvshow
            JOIN tvshow_genre ON (tvshow_genre.tvShowId = tvshow.id)
            JOIN genre on (genre.id = tvshow_genre.genreId)
            WHERE genre.id = ?
        SQL
        );
        $stmt->execute([$genreId]);
        $tvshows = $stmt->fetchAll(PDO::FETCH_CLASS, TvShow::class);
        if ($tvshows === false) {
            throw new EntityNotFoundException();
        }
        return $tvshows;
    }

    /**
     * Retourne toutes les séries par ordre alphabétique
     *
     * @return array
     */
    public function getSeason(): array
    {
        return SeasonCollection::findByTvId($this->getId());
    }

    public function delete(): TvShow
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            DELETE FROM tvshow
            WHERE id = ?
        SQL
        );
        $stmt->execute([$this->getId()]);
        $this->setId(null);
        return $this;
    }

    public function update(): TvShow
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            UPDATE tvshow
            SET name = ?, originalName = ?, homepage = ?, overview = ?
            WHERE id = ?
        SQL
        );
        $stmt->execute([$this->getName(),$this->getOriginalName(),$this->getHomepage(),$this->getOverview(),$this->getId()]);
        return $this;
    }

    public static function create(string $name, ?int $id = null, string $originalName, string $homepage, string $overview): TvShow
    {
        $tv = new TvShow();
        $tv->setName($name);
        $tv->setId($id);
        $tv->setOriginalName($originalName);
        $tv->setHomepage($homepage);
        $tv->setOverview($overview);
        return $tv;
    }

    protected function insert(): TvShow
    {
        $stmt = MyPDO::getInstance()->prepare(
            <<<'SQL'
            INSERT INTO tvshow(name, originalName, homepage, overview)
            VALUES(?, ?, ?, ?)
        SQL
        );
        $stmt->execute([$this->getName(),$this->getOriginalName(),$this->getHomepage(),$this->getOverview()]);
        $this->setId((int)MyPDO::getInstance()->lastInsertId());
        return $this;
    }

    public function save(): TvShow
    {
        if ($this->getId() === null) {
            $this->insert();
        } else {
            $this->update();
        }
        return $this;
    }
}
